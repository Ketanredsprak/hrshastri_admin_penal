-- =============================================================================
-- HR Shastri: Form 16 Generation & Employee Delivery
-- Date: 01-10-2026
--
-- Official reference (ITD / TRACES / CBDT):
--   Form 16 = TDS certificate u/s 203 for salary (Rule 31)
--   Part A  = TRACES-generated (download after Form 24Q); do NOT fabricate
--   Part B  = Employer annexure (salary / exemptions / deductions / tax)
--
-- Run on production MySQL. Safe to re-run: CREATE IF NOT EXISTS.
-- If you previously created these tables with different columns, migrate
-- carefully or DROP only empty/dev tables before re-applying.
-- =============================================================================

-- -----------------------------------------------------------------------------
-- 1) Main Form 16 records (versioned per employee + FY + company)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `form16_records` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` BIGINT UNSIGNED NOT NULL COMMENT 'users.id of company (created_by scope)',
  `employee_id` BIGINT UNSIGNED NOT NULL COMMENT 'employees.id',
  `financial_year` VARCHAR(9) NOT NULL COMMENT 'e.g. 2025-26',
  `assessment_year` VARCHAR(9) NOT NULL COMMENT 'e.g. 2026-27',
  `fy_start_date` DATE NOT NULL,
  `fy_end_date` DATE NOT NULL,
  `employment_period_from` DATE NULL COMMENT 'Actual period with this employer in FY',
  `employment_period_to` DATE NULL,
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  `previous_version_id` BIGINT UNSIGNED NULL,
  `is_latest` TINYINT(1) NOT NULL DEFAULT 1,
  `regeneration_reason` VARCHAR(500) NULL,

  -- Workflow status (company-facing)
  `status` VARCHAR(40) NOT NULL DEFAULT 'not_started'
    COMMENT 'not_started|data_pending|draft|ready_for_review|verified|part_a_uploaded|part_b_generated|generated|issued|sent|failed',

  -- Tax regime for this employee/FY (not assumed; company confirms)
  `tax_regime` VARCHAR(20) NULL COMMENT 'old|new|unknown',

  -- Snapshots at generation time (audit-safe)
  `employee_name` VARCHAR(255) NULL,
  `employee_code` VARCHAR(100) NULL,
  `employee_pan` VARCHAR(20) NULL,
  `employee_email` VARCHAR(255) NULL,
  `employee_address` TEXT NULL,
  `employer_name` VARCHAR(255) NULL,
  `employer_address` TEXT NULL,
  `employer_pan` VARCHAR(20) NULL,
  `employer_tan` VARCHAR(20) NULL,
  `employer_email` VARCHAR(255) NULL,

  -- Part A: TRACES upload ONLY (never fabricate certificate numbers)
  `part_a_source` VARCHAR(30) NOT NULL DEFAULT 'upload'
    COMMENT 'upload|none — HRMS does not generate official Part A',
  `part_a_file_path` VARCHAR(500) NULL,
  `part_a_original_name` VARCHAR(255) NULL,
  `part_a_mime` VARCHAR(100) NULL,
  `part_a_size` INT UNSIGNED NULL,
  `part_a_uploaded_by` BIGINT UNSIGNED NULL,
  `part_a_uploaded_at` DATETIME NULL,
  `part_a_certificate_number` VARCHAR(100) NULL
    COMMENT 'Optional: copied from TRACES PDF by company — not auto-generated',
  `part_a_notes` TEXT NULL,

  -- Part B: employer-prepared annexure (HRMS-generated)
  `part_b_data` LONGTEXT NULL COMMENT 'JSON snapshot of Part B calculation',
  `part_b_pdf_path` VARCHAR(500) NULL,
  `part_b_generated_at` DATETIME NULL,
  `part_b_generated_by` BIGINT UNSIGNED NULL,

  -- Aggregated financial summary (from payroll / Part B)
  `gross_salary` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `exempt_allowances` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `net_salary` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `deduction_under_section_16` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `income_chargeable_salaries` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `other_income` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `chapter_via_deductions` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `taxable_income` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `tax_on_total_income` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `rebate` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `surcharge` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `cess` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `tax_payable` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `tds_deducted` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `relief_under_89` DECIMAL(14,2) NOT NULL DEFAULT 0.00,

  -- Final merged / employer package PDF (Part B + cover; Part A attached separately when uploaded)
  `final_pdf_path` VARCHAR(500) NULL,
  `email_status` VARCHAR(20) NOT NULL DEFAULT 'not_sent'
    COMMENT 'not_sent|sent|failed|queued',
  `last_emailed_at` DATETIME NULL,
  `last_emailed_to` VARCHAR(255) NULL,

  `validation_errors` LONGTEXT NULL COMMENT 'JSON list of missing/invalid fields',
  `missing_data_flags` LONGTEXT NULL COMMENT 'JSON flags for UI warnings',

  `verified_by` BIGINT UNSIGNED NULL,
  `verified_at` DATETIME NULL,
  `issued_at` DATETIME NULL,
  `generated_by` BIGINT UNSIGNED NULL,
  `generated_at` DATETIME NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `updated_by` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,

  PRIMARY KEY (`id`),
  KEY `idx_form16_company` (`company_id`),
  KEY `idx_form16_employee` (`employee_id`),
  KEY `idx_form16_fy` (`financial_year`),
  KEY `idx_form16_status` (`status`),
  KEY `idx_form16_latest` (`company_id`, `employee_id`, `financial_year`, `is_latest`),
  KEY `idx_form16_prev` (`previous_version_id`),
  UNIQUE KEY `uniq_form16_version` (`company_id`, `employee_id`, `financial_year`, `version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 2) Optional Part A quarterly TDS rows (manual entry from TRACES — NOT invented)
--    Use only when company copies figures from official Part A / Form 24Q.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `form16_quarterly_tds` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `form16_id` BIGINT UNSIGNED NOT NULL,
  `quarter` TINYINT UNSIGNED NOT NULL COMMENT '1=Apr-Jun,2=Jul-Sep,3=Oct-Dec,4=Jan-Mar',
  `receipt_number` VARCHAR(50) NULL COMMENT 'Form 24Q acknowledgement',
  `amount_paid_credited` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `tax_deducted` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `tax_deposited` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `bsr_code` VARCHAR(20) NULL,
  `challan_date` DATE NULL,
  `challan_serial` VARCHAR(50) NULL,
  `notes` VARCHAR(500) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_form16_q_record` (`form16_id`),
  UNIQUE KEY `uniq_form16_quarter` (`form16_id`, `quarter`),
  CONSTRAINT `fk_form16_q_record`
    FOREIGN KEY (`form16_id`) REFERENCES `form16_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 3) Email delivery log
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `form16_email_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `form16_id` BIGINT UNSIGNED NOT NULL,
  `company_id` BIGINT UNSIGNED NOT NULL,
  `employee_id` BIGINT UNSIGNED NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(500) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'queued' COMMENT 'queued|sent|failed|retry',
  `error_message` TEXT NULL,
  `attempt` INT UNSIGNED NOT NULL DEFAULT 1,
  `sent_by` BIGINT UNSIGNED NULL,
  `sent_at` DATETIME NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_form16_email_record` (`form16_id`),
  KEY `idx_form16_email_employee` (`employee_id`),
  KEY `idx_form16_email_status` (`status`),
  CONSTRAINT `fk_form16_email_record`
    FOREIGN KEY (`form16_id`) REFERENCES `form16_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 4) Audit log (sensitive payroll/tax actions)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `form16_audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `form16_id` BIGINT UNSIGNED NULL,
  `company_id` BIGINT UNSIGNED NOT NULL,
  `employee_id` BIGINT UNSIGNED NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `action` VARCHAR(80) NOT NULL
    COMMENT 'created|data_updated|part_a_uploaded|part_a_deleted|part_b_generated|pdf_generated|verified|issued|downloaded|emailed|email_failed|regenerated',
  `metadata` LONGTEXT NULL COMMENT 'JSON (no need to duplicate full PAN/salary in logs)',
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(500) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_form16_audit_record` (`form16_id`),
  KEY `idx_form16_audit_company` (`company_id`),
  KEY `idx_form16_audit_action` (`action`),
  KEY `idx_form16_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 5) Company settings keys for employer PAN (TAN already exists as company_tan_numbar)
--    settings table uses name/value/created_by pattern in this HRMS.
--    Insert defaults only where missing — adjust created_by for each company as needed.
-- -----------------------------------------------------------------------------
-- Example: add company_pan_number for all company users who have settings rows.
-- Uncomment and run after verifying your `settings` table columns:

-- INSERT INTO `settings` (`name`, `value`, `created_by`, `created_at`, `updated_at`)
-- SELECT 'company_pan_number', '', u.id, NOW(), NOW()
-- FROM `users` u
-- WHERE u.type = 'company'
--   AND NOT EXISTS (
--     SELECT 1 FROM `settings` s
--     WHERE s.name = 'company_pan_number' AND s.created_by = u.id
--   );

-- -----------------------------------------------------------------------------
-- 6) Optional verification queries
-- -----------------------------------------------------------------------------
-- SHOW TABLES LIKE 'form16%';
-- DESCRIBE form16_records;
-- SELECT COUNT(*) FROM form16_records;
