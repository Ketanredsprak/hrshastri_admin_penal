-- =============================================================================
-- HR Shastri: Company billing mode (Global plan vs Custom employee/user pricing)
-- Run on MySQL / MariaDB before using the new company-create options.
-- =============================================================================

-- 1) Per-company billing profile
CREATE TABLE IF NOT EXISTS `company_billing_profiles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` BIGINT UNSIGNED NOT NULL,
  `billing_mode` ENUM('global','custom') NOT NULL DEFAULT 'global',
  `price_per_employee` DECIMAL(12,2) NULL,
  `price_per_user` DECIMAL(12,2) NULL,
  `invoice_plan_id` BIGINT UNSIGNED NULL COMMENT 'Plan used for custom billing (e.g. 9)',
  `manual_invoice_only` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = no auto CRM invoice',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_billing_profiles_company_id_unique` (`company_id`),
  KEY `idx_billing_mode` (`billing_mode`),
  CONSTRAINT `fk_billing_company` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) Snapshot columns on invoices (employees + users)
-- Skip any statement that errors with "Duplicate column name"

ALTER TABLE `crm_invoices`
  ADD COLUMN `active_users_count` INT NOT NULL DEFAULT 0 AFTER `active_employees_count`;

ALTER TABLE `crm_invoices`
  ADD COLUMN `price_per_user` DECIMAL(12,2) NULL AFTER `price_per_employee`;

-- 3) Temporarily disable automatic CRM invoice generation (manual only)
ALTER TABLE `crm_invoice_settings`
  ADD COLUMN `auto_generate_enabled` TINYINT(1) NOT NULL DEFAULT 0 AFTER `auto_email_on_generate`;

UPDATE `crm_invoice_settings` SET `auto_generate_enabled` = 0;

-- 4) Ensure plan 9 stays Unlimited (no subscription lock) with open caps
UPDATE `plans`
SET
  `duration` = 'Unlimited',
  `max_users` = -1,
  `max_employees` = -1
WHERE `id` = 9;

-- 5) Seed global billing profile for existing companies
INSERT INTO `company_billing_profiles` (`company_id`, `billing_mode`, `manual_invoice_only`, `created_at`, `updated_at`)
SELECT u.id, 'global', 0, NOW(), NOW()
FROM `users` u
WHERE u.type = 'company'
  AND NOT EXISTS (
    SELECT 1 FROM `company_billing_profiles` b WHERE b.company_id = u.id
  );

-- 6) OPTIONAL: convert existing plan-9 companies to custom billing
--    (set prices in Super Admin → edit company after running)
-- UPDATE `company_billing_profiles` b
-- INNER JOIN `users` u ON u.id = b.company_id
-- SET b.billing_mode = 'custom',
--     b.invoice_plan_id = 9,
--     b.manual_invoice_only = 1,
--     b.updated_at = NOW()
-- WHERE u.plan = 9 AND u.type = 'company';
--
-- UPDATE `users` SET custom_max_users = -1, custom_max_employees = -1
-- WHERE type = 'company' AND plan = 9;
