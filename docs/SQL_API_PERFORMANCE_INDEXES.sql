-- Additive indexes for mobile API hot reads. Safe to re-run (IF NOT EXISTS).
-- Does not change columns or API JSON.

ALTER TABLE `attendance_employees`
  ADD INDEX `idx_att_emp_date` (`employee_id`, `date`);

ALTER TABLE `attendance_employees`
  ADD INDEX `idx_att_emp_date_id` (`employee_id`, `date`, `id`);

ALTER TABLE `employees`
  ADD INDEX `idx_employees_user_id` (`user_id`);

ALTER TABLE `leaves`
  ADD INDEX `idx_leaves_emp_status_dates` (`employee_id`, `status`, `start_date`, `end_date`);

ALTER TABLE `leaves`
  ADD INDEX `idx_leaves_emp_created_id` (`employee_id`, `created_by`, `id`);

ALTER TABLE `holidays`
  ADD INDEX `idx_holidays_created_status` (`created_by`, `status`);

ALTER TABLE `mobile_menu`
  ADD INDEX `idx_mobile_menu_parent_seq` (`parent_id`, `sequence_number`);

ALTER TABLE `company_mobile_menu`
  ADD INDEX `idx_cmm_company_menu` (`company_id`, `menu_id`);
