# Company Invoices (CRM TAX INVOICE) — Admin Guide

## What this module does

Super Admin creates **manual TAX INVOICES** for companies on **custom employee/user billing** (typically plan #9).

- **Global subscription** companies are **not** invoiced here (they pay via plan/Stripe).
- Auto monthly generation is **paused** — use **Create Invoice**.
- PDF follows Zoho-style TAX INVOICE (GSTIN, HSN/SAC, CGST/SGST, FY number `INV/YY-YY/######`).

## Employees vs Users

| Line | Meaning | Formula |
|------|---------|---------|
| Employees | Active employees for the company | Qty × Price per employee |
| Users | Active HR/staff users (optional) | Qty × Price per user |

Subtotal = employee line + user line (if users billed).  
Tax = CGST% + SGST% on subtotal.  
Total = subtotal + tax.

## Do not charge for Users

When a company should **only** pay for employees this period:

1. Open **Create Invoice** (or Edit).
2. Tick **“Do not charge for Users (set Active Users = 0)”**,  
   **OR** manually set **Active Users = 0**.
3. Save.

### What happens

- `active_users_count` is stored as **0**.
- **Users line item is not added** on the invoice / PDF.
- Totals use **employees + GST only**.
- A **note** is appended automatically, for example:
  - `User seats not billed on this invoice (Active Users = 0).`
  - Optionally: `Detected active users in system: N (not charged).` (audit only)

On the list page, Users shows a yellow **0** badge when not billed.  
On the invoice view, Users shows **Not billed**.

## Suggested workflow

1. **Settings** — seller GSTIN, bank, CGST/SGST %, HSN, FY numbering.
2. **Create company** with custom billing + prices per employee/user.
3. **Create Invoice** — select company (counts/prices auto-fill).
4. Adjust employees/users or exclude users if needed.
5. Review PDF → Email → Mark paid when payment received.

## No extra SQL for this change

Exclude-users / Active Users = 0 uses existing columns (`active_users_count`, `notes`, line items).  
No new production migration is required for this feature alone.
