-- =============================================================================
-- HR Shastri: Zoho-style TAX INVOICE fields for CRM invoices
-- Date: 29-09-2026
-- Run on production (skip Duplicate column errors if already applied)
-- =============================================================================

ALTER TABLE `crm_invoice_settings`
  ADD COLUMN `seller_gstin` VARCHAR(20) NULL AFTER `seller_phone`,
  ADD COLUMN `seller_state` VARCHAR(100) NULL AFTER `seller_gstin`,
  ADD COLUMN `seller_state_code` VARCHAR(10) NULL AFTER `seller_state`,
  ADD COLUMN `bank_name` VARCHAR(150) NULL AFTER `payment_instructions`,
  ADD COLUMN `bank_account_no` VARCHAR(50) NULL AFTER `bank_name`,
  ADD COLUMN `bank_ifsc` VARCHAR(30) NULL AFTER `bank_account_no`,
  ADD COLUMN `bank_swift` VARCHAR(30) NULL AFTER `bank_ifsc`,
  ADD COLUMN `default_hsn_sac` VARCHAR(20) NULL AFTER `bank_swift`,
  ADD COLUMN `default_terms` VARCHAR(100) NULL AFTER `default_hsn_sac`,
  ADD COLUMN `default_notes` TEXT NULL AFTER `default_terms`,
  ADD COLUMN `signature_path` VARCHAR(255) NULL AFTER `logo_path`,
  ADD COLUMN `use_fy_numbering` TINYINT(1) NOT NULL DEFAULT 1 AFTER `next_number`;

ALTER TABLE `crm_invoices`
  ADD COLUMN `company_gstin` VARCHAR(20) NULL AFTER `company_phone`,
  ADD COLUMN `ship_to_name` VARCHAR(255) NULL AFTER `company_gstin`,
  ADD COLUMN `ship_to_address` TEXT NULL AFTER `ship_to_name`,
  ADD COLUMN `place_of_supply` VARCHAR(100) NULL AFTER `ship_to_address`,
  ADD COLUMN `place_of_supply_code` VARCHAR(10) NULL AFTER `place_of_supply`,
  ADD COLUMN `terms` VARCHAR(100) NULL AFTER `place_of_supply_code`,
  ADD COLUMN `total_in_words` VARCHAR(500) NULL AFTER `total_amount`,
  ADD COLUMN `seller_gstin` VARCHAR(20) NULL AFTER `total_in_words`,
  ADD COLUMN `seller_name_snapshot` VARCHAR(255) NULL AFTER `seller_gstin`,
  ADD COLUMN `seller_address_snapshot` TEXT NULL AFTER `seller_name_snapshot`;

ALTER TABLE `crm_invoice_items`
  ADD COLUMN `hsn_sac` VARCHAR(20) NULL AFTER `description`,
  ADD COLUMN `unit` VARCHAR(20) NULL DEFAULT 'Nos' AFTER `hsn_sac`,
  ADD COLUMN `cgst_percent` DECIMAL(8,2) NOT NULL DEFAULT 0 AFTER `line_total`,
  ADD COLUMN `cgst_amount` DECIMAL(14,2) NOT NULL DEFAULT 0 AFTER `cgst_percent`,
  ADD COLUMN `sgst_percent` DECIMAL(8,2) NOT NULL DEFAULT 0 AFTER `cgst_amount`,
  ADD COLUMN `sgst_amount` DECIMAL(14,2) NOT NULL DEFAULT 0 AFTER `sgst_percent`;

-- Seed HR Shastri Tech LLP defaults (optional)
UPDATE `crm_invoice_settings` SET
  `seller_name` = COALESCE(NULLIF(`seller_name`, ''), 'HR SHASTRI TECH LLP'),
  `seller_address` = COALESCE(NULLIF(`seller_address`, ''), '5th Floor, Darshanam Oxy Park\nVasna Bhayli Road\nVadodara Gujarat 390015\nIndia'),
  `seller_gstin` = COALESCE(`seller_gstin`, '24AAQFH2769H1Z1'),
  `seller_state` = COALESCE(`seller_state`, 'Gujarat'),
  `seller_state_code` = COALESCE(`seller_state_code`, '24'),
  `bank_name` = COALESCE(`bank_name`, 'AXIS BANK'),
  `bank_account_no` = COALESCE(`bank_account_no`, '922020049777281'),
  `bank_ifsc` = COALESCE(`bank_ifsc`, 'UTIB0001491'),
  `bank_swift` = COALESCE(`bank_swift`, 'AXISINBB013'),
  `default_hsn_sac` = COALESCE(`default_hsn_sac`, '997331'),
  `default_terms` = COALESCE(`default_terms`, 'Due on Receipt'),
  `default_notes` = COALESCE(`default_notes`, 'Thanks for your business.'),
  `invoice_prefix` = 'INV',
  `use_fy_numbering` = 1,
  `cgst_percent` = IF(`cgst_percent` = 0, 9, `cgst_percent`),
  `sgst_percent` = IF(`sgst_percent` = 0, 9, `sgst_percent`)
WHERE `id` = 1;
