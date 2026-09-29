# CRM Company Invoices — Master Plan & Live SQL

**Product:** HR Shastri  
**Audience:** Super Admin CRM billing  
**Billing rule (locked):** Active employees × configurable price (default **₹200** / employee / month). Staff/HR users are **not** billed.  
**Module:** New tables (`crm_*`) — separate from plan `orders`.

---

## 1. System prompt (for developers)

Build a Super Admin CRM to manage company invoices:

1. **Auto generation** — Cron on last day of month finds active companies (`users.type=company`, `is_active=1`), counts active employees (`employee_exit=0` + linked user active when present), creates invoice at settings price, stores PDF, optionally emails company email.
2. **Manual create/edit** — Super Admin can create invoices, override counts/amounts/notes. Editable until **Payment Complete**.
3. **Payment lock** — Status `paid` makes invoice immutable.
4. **Email** — Send modal with recipient list; PDF attached; auto-email on monthly generate when enabled.
5. **Config** — Price, due days, seller/logo, payment instructions, auto-email, skip-zero-employee.

**Out of scope v1:** Online payment gateway, GST engine, company self-serve portal.

---

## 2. Active employee count

```sql
SELECT COUNT(*) AS active_employees
FROM employees e
LEFT JOIN users u ON u.id = e.user_id
WHERE e.created_by = :company_id
  AND e.employee_exit = 0
  AND (e.user_id IS NULL OR e.user_id = 0 OR u.is_active = 1);
```

---

## 3. Live server — full MySQL DDL

Run once on production (or use `php artisan migrate`).

```sql
-- --------------------------------------------------------------------------
-- CRM Company Invoices (HR Shastri)
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `crm_invoice_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `price_per_employee` DECIMAL(12,2) NOT NULL DEFAULT 200.00,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'INR',
  `due_days` INT NOT NULL DEFAULT 15,
  `invoice_prefix` VARCHAR(20) NOT NULL DEFAULT 'HRSI',
  `next_number` INT NOT NULL DEFAULT 1,
  `seller_name` VARCHAR(255) NULL,
  `seller_address` TEXT NULL,
  `seller_email` VARCHAR(255) NULL,
  `seller_phone` VARCHAR(50) NULL,
  `product_name` VARCHAR(255) NULL DEFAULT 'HR Shastri',
  `logo_path` VARCHAR(255) NULL,
  `payment_instructions` TEXT NULL,
  `auto_email_on_generate` TINYINT(1) NOT NULL DEFAULT 1,
  `skip_zero_employee` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_invoices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` VARCHAR(50) NOT NULL,
  `company_id` BIGINT UNSIGNED NOT NULL,
  `company_name` VARCHAR(255) NOT NULL,
  `company_email` VARCHAR(255) NULL,
  `company_address` TEXT NULL,
  `company_phone` VARCHAR(50) NULL,
  `billing_year` SMALLINT NOT NULL,
  `billing_month` TINYINT NOT NULL,
  `invoice_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `source` ENUM('auto','manual') NOT NULL DEFAULT 'auto',
  `status` ENUM('draft','generated','sent','paid','cancelled') NOT NULL DEFAULT 'generated',
  `auto_period_key` VARCHAR(64) NULL COMMENT 'Set only for auto: {company_id}-{year}-{month}',
  `active_employees_count` INT NOT NULL DEFAULT 0,
  `price_per_employee` DECIMAL(12,2) NOT NULL,
  `subtotal` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'INR',
  `notes` TEXT NULL,
  `payment_instructions` TEXT NULL,
  `pdf_path` VARCHAR(255) NULL,
  `paid_at` DATETIME NULL,
  `paid_by` BIGINT UNSIGNED NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `updated_by` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_crm_inv_number` (`invoice_number`),
  UNIQUE KEY `uq_crm_auto_period_key` (`auto_period_key`),
  KEY `idx_crm_inv_company` (`company_id`),
  KEY `idx_crm_inv_status` (`status`),
  KEY `idx_crm_inv_period` (`billing_year`,`billing_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_invoice_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` BIGINT UNSIGNED NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(12,2) NOT NULL,
  `line_total` DECIMAL(14,2) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_crm_item_invoice` (`invoice_id`),
  CONSTRAINT `fk_crm_item_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `crm_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_invoice_email_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` BIGINT UNSIGNED NOT NULL,
  `recipients` TEXT NOT NULL,
  `subject` VARCHAR(255) NULL,
  `status` ENUM('queued','sent','failed') NOT NULL DEFAULT 'queued',
  `error_message` TEXT NULL,
  `sent_by` BIGINT UNSIGNED NULL,
  `sent_at` DATETIME NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_crm_email_invoice` (`invoice_id`),
  CONSTRAINT `fk_crm_email_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `crm_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `crm_invoice_settings` (
  `price_per_employee`, `currency`, `due_days`, `invoice_prefix`, `next_number`,
  `seller_name`, `product_name`, `payment_instructions`,
  `auto_email_on_generate`, `skip_zero_employee`, `created_at`, `updated_at`
) SELECT
  200.00, 'INR', 15, 'HRSI', 1,
  'HR Shastri', 'HR Shastri',
  'Please pay via bank transfer / UPI and share the UTR with support. Invoice becomes final once marked Payment Complete.',
  1, 1, NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `crm_invoice_settings` LIMIT 1);
```

---

## 4. Ops queries (production)

```sql
-- Unpaid invoices
SELECT id, invoice_number, company_name, billing_year, billing_month, total_amount, status
FROM crm_invoices
WHERE status IN ('draft','generated','sent')
ORDER BY billing_year DESC, billing_month DESC, id DESC;

-- Auto invoices for a period
SELECT * FROM crm_invoices
WHERE source = 'auto' AND billing_year = 2026 AND billing_month = 9;

-- Mark paid (prefer UI; SQL fallback)
UPDATE crm_invoices
SET status = 'paid', paid_at = NOW(), updated_at = NOW()
WHERE id = :invoice_id AND status <> 'paid';

-- Email failures
SELECT * FROM crm_invoice_email_logs WHERE status = 'failed' ORDER BY id DESC LIMIT 50;

-- Settings
SELECT * FROM crm_invoice_settings LIMIT 1;

-- Update price
UPDATE crm_invoice_settings SET price_per_employee = 200.00, updated_at = NOW() WHERE id = 1;
```

---

## 5. Deploy runbook (live)

1. Backup DB.
2. Run migration: `php artisan migrate --force` **or** execute DDL above in MySQL.
3. Confirm settings row exists; set seller/logo/payment text via Super Admin → Company Invoices → Settings.
4. Ensure scheduler: `* * * * * php /path/to/artisan schedule:run`  
   Or HTTP: `GET /cron/crm_monthly_invoices`
5. Smoke test:  
   `php artisan crm:invoices-generate-monthly --dry-run`  
   then without `--dry-run` for one company: `--company=ID --month=9 --year=2026`
6. Verify PDF download + send email from UI.

### Artisan / cron

| Command | Purpose |
|---------|---------|
| `php artisan crm:invoices-generate-monthly` | Generate for current (or last) month |
| `--month= --year= --company= --dry-run` | Options |
| Schedule | Last day of month 23:00 Asia/Kolkata |

---

## 6. Test checklist

- [x] Active company with N employees → total = N × price (verified: company 2, 8 × 200 = 1600)
- [x] Dry-run scans companies and reports created/skipped (70 scanned, 36 would create, 34 zero)
- [x] PDF generated and stored under `storage/app/crm_invoices/...`
- [x] Second auto run same month → skipped_existing
- [ ] Exited employee (`employee_exit=1`) excluded — use SQL count vs UI
- [ ] Inactive linked user excluded when `user_id` set
- [ ] Manual create with override qty/price — UI at `/crm/invoices/create`
- [ ] Edit before paid OK; after paid blocked — UI show page
- [ ] Send email modal + log row — requires SMTP configured
- [ ] Cron dry-run / HTTP cron returns JSON — `GET /cron/crm_monthly_invoices?dry_run=1`

### Local verification notes (2026-09-22)

```
php artisan migrate --path=database/migrations/2026_09_22_120000_create_crm_invoice_tables.php
php artisan crm:invoices-generate-monthly --dry-run --no-email
php artisan crm:invoices-generate-monthly --company=2 --no-email
# Invoice HRSI-202609-00001 total 1600, PDF ok
php artisan crm:invoices-generate-monthly --company=2 --no-email
# Skipped existing
```

---

## 7. Code map

| Area | Path |
|------|------|
| Migration | `database/migrations/2026_09_22_120000_create_crm_invoice_tables.php` |
| Models | `app/Models/CrmInvoice*.php` |
| Service | `app/Services/CrmInvoiceService.php` |
| Command | `app/Console/Commands/CrmMonthlyInvoiceGenerateCommand.php` |
| Controller | `app/Http/Controllers/CrmInvoiceController.php` |
| Views | `resources/views/crm_invoice/*` |
| Routes | `routes/web.php` (`crm/invoices/*`) |
