-- =============================================================================
-- HR Shastri: Add CGST / SGST % to CRM invoice settings + invoice snapshot
-- Run on production DB (skip any "Duplicate column" errors if already applied)
-- =============================================================================

ALTER TABLE `crm_invoice_settings`
  ADD COLUMN `cgst_percent` DECIMAL(8,2) NOT NULL DEFAULT 0 AFTER `price_per_employee`;

ALTER TABLE `crm_invoice_settings`
  ADD COLUMN `sgst_percent` DECIMAL(8,2) NOT NULL DEFAULT 0 AFTER `cgst_percent`;

ALTER TABLE `crm_invoices`
  ADD COLUMN `cgst_percent` DECIMAL(8,2) NOT NULL DEFAULT 0 AFTER `subtotal`;

ALTER TABLE `crm_invoices`
  ADD COLUMN `sgst_percent` DECIMAL(8,2) NOT NULL DEFAULT 0 AFTER `cgst_percent`;

ALTER TABLE `crm_invoices`
  ADD COLUMN `cgst_amount` DECIMAL(14,2) NOT NULL DEFAULT 0 AFTER `sgst_percent`;

ALTER TABLE `crm_invoices`
  ADD COLUMN `sgst_amount` DECIMAL(14,2) NOT NULL DEFAULT 0 AFTER `cgst_amount`;

-- Optional: set default GST rates (example 9% + 9% = 18%)
-- UPDATE `crm_invoice_settings` SET `cgst_percent` = 9, `sgst_percent` = 9;
