<?php

    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::GetLogo();
    $users = \Auth::user();
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
    $currantLang = $users->currentLanguage();
    $emailTemplate = App\Models\EmailTemplate::first();
?>


<?php if(isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on'): ?>
    <nav class="dash-sidebar light-sidebar transprent-bg">
    <?php else: ?>
        <nav class="dash-sidebar light-sidebar">
<?php endif; ?>

<div class="navbar-wrapper">
    <div class="m-header main-logo">
        <a href="<?php echo e(route('home')); ?>" class="b-brand">
            <!-- ========   change your logo hear   ============ -->
            <img src="<?php echo e($logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png')); ?>"
                alt="<?php echo e(config('app.name', 'HRMGo')); ?>" class="logo logo-lg" style="height: 40px;">
        </a>
    </div>
    <div class="navbar-content">
        <ul class="dash-navbar">

            <!-- dashboard-->
            <li class="dash-item">
                <a href="<?php echo e(route('home')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-home"></i></span><span class="dash-mtext"><?php echo e(__('Dashboard')); ?> </span></a>
            </li>
            <!--dashboard-->

            <?php if(\Auth::user()->type != 'company'): ?>
                
                <li
                    class="dash-item <?php echo e(Request::segment(1) == 'employee-profile-edit' || Request::segment(1) == 'employee-profile-show' ? 'active' : ''); ?>">

                    <?php
                        $employee = App\Models\Employee::where('user_id', \Auth::user()->id)->first();
                    ?>
                    <a href="<?php echo e(route('employeeProfileShow', \Illuminate\Support\Facades\Crypt::encrypt($employee->id ?? 0))); ?>"
                        class="dash-link"><span class="dash-micon"><i class="ti ti-user"></i></span><span
                            class="dash-mtext"><?php echo e(__('Employee Details')); ?></span></a>
                </li>
                
            <?php endif; ?>

            <!-- user-->
            <?php if(Gate::check('Manage User') || Gate::check('Manage Role')): ?>

                <li class="dash-item dash-hasmenu">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-users"></i></span><span
                            class="dash-mtext"><?php echo e(__('Staff')); ?></span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage User')): ?>
                            <li class="dash-item">
                                <a class="dash-link" href="<?php echo e(route('user.index')); ?>"><?php echo e(__('User')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Role')): ?>
                            <li class="dash-item">
                                <a class="dash-link" href="<?php echo e(route('roles.index')); ?>"><?php echo e(__('Role')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <!-- user-->

            <!-- employee List-->

            <?php if(Gate::check('Manage Employee') && \Auth::user()->type != 'employee'): ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'employee' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('employee.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-user"></i></span><span class="dash-mtext"><?php echo e(__('Employees')); ?></span></a>
                </li>
            <?php endif; ?>

            <!-- employee List -->

            <!-- payroll-->
            <?php if(Gate::check('Manage Set Salary') || Gate::check('Manage Pay Slip')): ?>
                <li
                    class="dash-item dash-hasmenu  <?php echo e(Request::segment(1) == 'setsalary' ? 'dash-trigger active' : ''); ?>">
                    <a href="#!" class="dash-link">
                        <span class="dash-micon">
                            <i class="ti ti-receipt">
                            </i>
                        </span>
                        <span class="dash-mtext">
                            <?php echo e(__('Payroll')); ?>

                        </span>
                        <span class="dash-arrow"><i data-feather="chevron-right">
                            </i>
                        </span>
                    </a>
                    <ul class="dash-submenu ">
                        
                        <li class="dash-item <?php echo e(Request::segment(1) == 'allowanceoption' ? 'active' : '-'); ?>">
                            <a class="dash-link" href="<?php echo e(route('allowanceoption.index')); ?>"><?php echo e(__('Allowance')); ?></a>
                        </li>

                        <li class="dash-item <?php echo e(Request::segment(1) == 'commission_option' ? 'active' : '-'); ?>">
                            <a class="dash-link"
                                href="<?php echo e(route('commission_option.index')); ?>"><?php echo e(__('Commission ')); ?></a>
                        </li>

                        <li class="dash-item <?php echo e(Request::segment(1) == 'loans.index' ? 'active' : '-'); ?>">
                            <a class="dash-link" href="<?php echo e(route('loans')); ?>"><?php echo e(__('Loan ')); ?></a>
                        </li>

                        <li class="dash-item <?php echo e(Request::segment(1) == 'otherpaymentotion' ? 'active' : '-'); ?>">
                            <a class="dash-link"
                                href="<?php echo e(route('otherpaymentotion.index')); ?>"><?php echo e(__('Other Payment')); ?></a>
                        </li>


                        <li class="dash-item <?php echo e(Request::segment(1) == 'deductionoption' ? 'active' : '-'); ?>">
                            <a class="dash-link"
                                href="<?php echo e(route('deductionoption.index')); ?>"><?php echo e(__('Deduction ')); ?></a>
                        </li>

                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('statutory-deductions')); ?>"><?php echo e(__('Statutory Deductions')); ?></a>
                        </li>

                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('payslip.index')); ?>"><?php echo e(__('Generate Payroll')); ?></a>
                        </li>

                        <?php if(Gate::check('My Pay Slip')): ?>
                            <li
                                class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'setsalary' ? 'dash-trigger active' : ''); ?>">

                                <a class="dash-link" href="<?php echo e(route('my-payslip.index')); ?>"><?php echo e(__('My Payslip')); ?></a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>
            <!-- payroll-->

            <!-- timesheet-->
            <?php if(Gate::check('Manage Attendance') ||
                    Gate::check('Manage Leave') ||
                    Gate::check('Manage TimeSheet') ||
                    Gate::check('Manage Gate Pass') ||
                    Gate::check('Manage Apply WFH') ||
                    Gate::check('Manage Early & Late Comming')): ?>
                <li class="dash-item dash-hasmenu">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-clock"></i></span><span
                            class="dash-mtext"><?php echo e(__('Attendance & Leaves')); ?></span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage TimeSheet')): ?>
                            <li class="dash-item">
                                <a class="dash-link" href="<?php echo e(route('timesheet.index')); ?>"><?php echo e(__('Timesheet')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Leave')): ?>
                            <li class="dash-item">
                                <a class="dash-link" href="<?php echo e(route('leave.index')); ?>"><?php echo e(__('Leave')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if(\Auth::user()->type != 'company'): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('leave.my_leave_balance')); ?>"><?php echo e(__('My Leave Balance')); ?></a>
                            </li>
                        <?php endif; ?>


                        <?php if(\Auth::user()->type == 'employee'): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('applywfm.index')); ?>"><?php echo e(__('Apply WFH')); ?></a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('earlyandlate_leave.index')); ?>"><?php echo e(__('Short Leave')); ?></a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('getpass.index')); ?>"><?php echo e(__('Gate Pass')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Apply WFH')): ?>
                            <li class="dash-item">
                                <a class="dash-link" href="<?php echo e(route('applywfm.index')); ?>"><?php echo e(__('Apply WFH')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Early & Late Comming')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('earlyandlate_leave.index')); ?>"><?php echo e(__('Short Leave')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Gate Pass')): ?>
                            <li class="dash-item">
                                <a class="dash-link" href="<?php echo e(route('getpass.index')); ?>"><?php echo e(__('Gate Pass')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Attendance')): ?>
                            <li class="dash-item dash-hasmenu">
                                <a href="#!" class="dash-link"><span
                                        class="dash-mtext"><?php echo e(__('Attendance')); ?></span><span class="dash-arrow"><i
                                            data-feather="chevron-right"></i></span></a>
                                <ul class="dash-submenu">
                                    <li class="dash-item">
                                        <a class="dash-link"
                                            href="<?php echo e(route('attendanceemployee.index')); ?>"><?php echo e(__('Marked Attendance')); ?></a>
                                    </li>
                                    <?php if(\Auth::user()->type != 'company' && \Auth::user()->type != 'employee'): ?>
                                        <li class="dash-item">
                                            <a class="dash-link"
                                                href="<?php echo e(route('attendanceemployee.my-attendance')); ?>"><?php echo e(__('My Attendance')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Attendance')): ?>
                                        <li class="dash-item">
                                            <a class="dash-link"
                                                href="<?php echo e(route('attendanceemployee.bulkattendance')); ?>"><?php echo e(__('Bulk Attendance')); ?></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Company Weekoff')): ?>
                                        <li class="dash-item">
                                            <a class="dash-link"
                                                href="<?php echo e(route('company-weekoff.show')); ?>"><?php echo e(__('Week-off')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <!--timesheet-->

        <!-- My Visit & Claim-->
        <?php if(Gate::check('Manage Visit Request') || Gate::check('Manage Claim Request')): ?>
            <li
                class="dash-item dash-hasmenu  <?php echo e(Request::segment(1) == 'setsalary' ? 'dash-trigger active' : ''); ?>">
                <a href="#!" class="dash-link">
                    <span class="dash-micon"><i class="ti ti-file"></i></span>
                    <span class="dash-mtext"><?php echo e(__('My Visit & Claim')); ?></span>
                    <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu ">

                    <li class="dash-item <?php echo e(Request::segment(1) == 'my_visit' ? 'active' : '-'); ?>">
                        <a class="dash-link" href="<?php echo e(route('my_visit.index')); ?>"><?php echo e(__('Visit List')); ?></a>
                    </li>
                    <li class="dash-item <?php echo e(Request::segment(1) == 'my_claim' ? 'active' : '-'); ?>">
                        <a class="dash-link" href="<?php echo e(route('my_claim.index')); ?>"><?php echo e(__('Claim List')); ?></a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
        <!-- My Visit & Claim-->


        


        <?php if(Gate::check('Manage Holiday') || Gate::check('Manage Announcement') || Gate::check('Manage Event')): ?>
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-book"></i></span><span
                        class="dash-mtext"><?php echo e(__('Announcement')); ?></span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Announcement')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('announcement.index')); ?>"><?php echo e(__('Office Announcement')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Holiday')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('holiday.index')); ?>"><?php echo e(__('Holidays')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Event')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('event.index')); ?>"><?php echo e(__('Event')); ?></a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>
        

        <!-- Company Policy-->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Company Policy')): ?>
            <li class="dash-item">
                <a href="<?php echo e(route('company-policy.index')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-book"></i></span><span class="dash-mtext"><?php echo e(__('Company Policy')); ?>

                    </span></a>
            </li>
        <?php endif; ?>
        <!-- Company Policy-->


        <!-- Grievance Desk-->
        <?php if(Gate::check('Manage Complaint') || Gate::check('Manage Ticket')): ?>
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-border-all"></i></span><span
                        class="dash-mtext"><?php echo e(__('Grievance Desk')); ?></span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Complaint')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('complaint.index')); ?>"><?php echo e(__('Complaints')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Ticket')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('ticket.index')); ?>"><?php echo e(__('Ticket')); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <!-- Grievance Desk-->


        
        <?php if(Gate::check('Manage Promotion')): ?>
            <li class="dash-item">
                <a class="dash-link" href="<?php echo e(route('promotion.index')); ?>"><span class="dash-micon"><i
                            class="ti ti-tag"></i></span><span
                        class="dash-mtext"><?php echo e(__('Promotion')); ?></span></a>
            </li>
        <?php endif; ?>
        


        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Transfer')): ?>

            <li class="dash-item">
                <a href="<?php echo e(route('transfer.index')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-book"></i></span><span class="dash-mtext"><?php echo e(__('Transfer')); ?>

                    </span></a>
            </li>
        <?php endif; ?>
        


        <!-- performance-->
        <?php if(Gate::check('Manage Indicator') || Gate::check('Manage Appraisal') || Gate::check('Manage Goal Tracking')): ?>
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-3d-cube-sphere"></i></span><span
                        class="dash-mtext"><?php echo e(__('Performance')); ?></span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Indicator')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('indicator.index')); ?>"><?php echo e(__('Indicator')); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Appraisal')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('appraisal.index')); ?>"><?php echo e(__('Appraisal')); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Goal Tracking')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('goaltracking.index')); ?>"><?php echo e(__('Goal Tracking')); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <!--performance-->

        <!--trainning-->
        <?php if(Gate::check('Manage Trainer') || Gate::check('Manage Training') || \Auth::user()->type == 'employee'): ?>
            <!-- user type if employee than show list of traning st en -->
            <li
                class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'training' ? 'dash-trigger active' : ''); ?>">
                <a href="#!" class="dash-link "><span class="dash-micon"><i
                            class="ti ti-school"></i></span><span
                        class="dash-mtext"><?php echo e(__('Training')); ?></span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    <!-- st -->
                    <?php if(\Auth::user()->type == 'employee'): ?>
                        <li class="dash-item <?php echo e(Request::segment(1) == 'training' ? ' active' : ''); ?>">
                            <a class="dash-link"
                                href="<?php echo e(route('training.index')); ?>"><?php echo e(__('Training List')); ?></a>
                        </li>
                    <?php endif; ?>
                    <!-- en -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Training')): ?>
                        <li class="dash-item <?php echo e(Request::segment(1) == 'training' ? ' active' : ''); ?>">
                            <a class="dash-link"
                                href="<?php echo e(route('training.index')); ?>"><?php echo e(__('Training List')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Trainer')): ?>
                        <li class="dash-item ">
                            <a class="dash-link" href="<?php echo e(route('trainer.index')); ?>"><?php echo e(__('Trainer')); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <!-- tranning-->

        <!-- recruitment-->
        <?php if(Gate::check('Manage Job') ||
                Gate::check('Manage Job Application') ||
                Gate::check('Manage Job OnBoard') ||
                Gate::check('Manage Custom Question') ||
                Gate::check('Manage Interview Schedule') ||
                Gate::check('Manage Career')): ?>
            <li
                class="dash-item dash-hasmenu  <?php echo e(Request::segment(1) == 'job' || Request::segment(1) == 'job-application' ? 'dash-trigger active' : ''); ?> ">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-users"></i></span><span
                        class="dash-mtext"><?php echo e(__('Recruitment')); ?></span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Job')): ?>
                        <li class="dash-item <?php echo e(Request::segment(1) == 'job' ? 'active' : '-'); ?>">
                            <a class="dash-link" href="<?php echo e(route('job.index')); ?>"><?php echo e(__('Jobs')); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Job')): ?>
                        <li class="dash-item <?php echo e(Request::segment(1) == 'job' ? 'active' : '-'); ?>">
                            <a class="dash-link" href="<?php echo e(route('job.create')); ?>"><?php echo e(__('Job Create ')); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Job Application')): ?>
                        <li class="dash-item <?php echo e(request()->is('job-application*') ? 'active' : ''); ?>">
                            <a class="dash-link"
                                href="<?php echo e(route('job-application.index')); ?>"><?php echo e(__('Job Application')); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Job Application')): ?>
                        <li class="dash-item <?php echo e(request()->is('candidates-job-applications') ? 'active' : ''); ?>">
                            <a class="dash-link"
                                href="<?php echo e(route('job.application.candidate')); ?>"><?php echo e(__('Archive Application')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Job OnBoard')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('job.on.board')); ?>"><?php echo e(__('Job On-Boarding')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Custom Question')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('custom-question.index')); ?>"><?php echo e(__('Custom Question')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Interview Schedule')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('interview-schedule.index')); ?>"><?php echo e(__('Interview Schedule')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Career')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('career', [\Auth::user()->creatorId(), 'en'])); ?>"
                                target="_blank"><?php echo e(__('Career')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if(\Auth::user()->type == 1 || \Auth::user()->type == 'company'): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('job_referencelist')); ?>"><?php echo e(__('Reference List')); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <!-- recruitment-->

        <!--fianance-->
        <?php if(Gate::check('Manage Account List') ||
                Gate::check('Manage Payee') ||
                Gate::check('Manage Payer') ||
                Gate::check('Manage Deposit') ||
                Gate::check('Manage Expense') ||
                Gate::check('Manage Transfer Balance')): ?>
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-wallet"></i></span><span
                        class="dash-mtext"><?php echo e(__('Finance')); ?></span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Account List')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('accountlist.index')); ?>"><?php echo e(__('Account List')); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View Balance Account List')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('accountbalance')); ?>"><?php echo e(__('Account Balance')); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Payee')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('payees.index')); ?>"><?php echo e(__('Payees')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Payer')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('payer.index')); ?>"><?php echo e(__('Payers')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Deposit')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('deposit.index')); ?>"><?php echo e(__('Deposit')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Expense')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('expense.index')); ?>"><?php echo e(__('Expense')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Transfer Balance')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('transferbalance.index')); ?>"><?php echo e(__('Transfer Balance')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Approved Claims')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('approved-claims')); ?>"><?php echo e(__('Approved Claims')); ?></a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>
        <!-- fianance-->

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Assets')): ?>

            <li class="dash-item">
                <a href="<?php echo e(route('account-assets.index')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-book"></i></span><span class="dash-mtext"><?php echo e(__('Assets')); ?>

                    </span></a>
            </li>
        <?php endif; ?>
        


        <!-- Employee Exit-->
        <?php if(Gate::check('Manage Resignation') || Gate::check('Manage Termination')): ?>
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-arrow-ramp-right"></i></span><span
                        class="dash-mtext"><?php echo e(__('Employee Exit')); ?></span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Resignation')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('resignation.index')); ?>"><?php echo e(__('Resignation')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Termination')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('termination.index')); ?>"><?php echo e(__('Termination')); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <!-- Employee Exit-->



































































        <!--timesheet-->

        <!-- staff request-->
        <?php if(Gate::check('Manage Timesheet Request') ||
                Gate::check('Manage Leave Request') ||
                Gate::check('Manage Apply WFH Request') ||
                Gate::check('Manage Early & Late Leave Request') ||
                Gate::check('Manage Gate Pass Request') ||
                Gate::check('Manage Resignation Request')): ?>

            <?php if(\Auth::user()->type != 'employee'): ?>
                <li class="dash-item dash-hasmenu">
                    <a href="#!" class="dash-link"><span class="dash-micon">
                            <i class="ti ti-user-check"></i></span><span
                            class="dash-mtext"><?php echo e(__('Staff Request')); ?></span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Timesheet Request')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('timesheet_request')); ?>"><?php echo e(__('Timesheet Request')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Leave Request')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('leaverequest')); ?>"><?php echo e(__('Leave Request')); ?></a>
                            </li>
                        <?php endif; ?>


                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Apply WFH Request')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('applywfm_request')); ?>"><?php echo e(__('WFH Request')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Early & Late Leave Request')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('earlyandlate_leave_request')); ?>"><?php echo e(__('Short Leave Request')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Gate Pass Request')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('getpass_request')); ?>"><?php echo e(__('Gate Pass Request')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Resignation Request')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('resignation_request')); ?>"><?php echo e(__('Resignation Request')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Resignation Request')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('my_visit_request')); ?>"><?php echo e(__('Visit Request')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Resignation Request')): ?>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="<?php echo e(route('my_claim_request')); ?>"><?php echo e(__('Claim Request')); ?></a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>
        <?php endif; ?>

















        <!--meeting-->
        
        
        


        <!-- Zoom meeting-->
        
        
        








        <!--report-->

        <?php if(Gate::check('Manage Report')): ?>
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-list"></i></span><span
                        class="dash-mtext"><?php echo e(__('Report')); ?></span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Report')): ?>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('report.income-expense')); ?>"><?php echo e(__('Income Vs Expense')); ?></a>
                        </li>

                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('report.monthly.attendance')); ?>"><?php echo e(__('Monthly Attendance')); ?></a>
                        </li>

                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('employee-attendance-geofencing-report')); ?>"><?php echo e(__('Advance Monthly Attendance')); ?></a>
                        </li>

                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('report.leave')); ?>"><?php echo e(__('Leave')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('report.account.statement')); ?>"><?php echo e(__('Account Statement')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('report.timesheet')); ?>"><?php echo e(__('Timesheet')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('report.claim')); ?>"><?php echo e(__('Claim')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('esic_register_reports')); ?>"><?php echo e(__('Esic Register Reports')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('wage_reports_index')); ?>"><?php echo e(__('Wage Reports')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('report.birthday_list')); ?>"><?php echo e(__('Birthday List')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('report.work_anniversary_list')); ?>"><?php echo e(__('Work Anniversary List')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('report.marriage_anniversary_list')); ?>"><?php echo e(__('Marriage Anniversary List')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('late-coming-report')); ?>"><?php echo e(__('Late Coming')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('new-joinee-report')); ?>"><?php echo e(__('New Joinee')); ?></a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="<?php echo e(route('resigned-employee-report')); ?>"><?php echo e(__('Resigned Employee')); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if(Gate::check('Manage System Settings')): ?>
            <li class="dash-item ">
                <a href="<?php echo e(route('settings.index')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-settings"></i></span><span
                        class="dash-mtext"><?php echo e(__('Settings')); ?></span></a>

            </li>
        <?php endif; ?>


        <!--------------------- Start System Setup ----------------------------------->

        <?php if(Gate::check('Manage Plan') || Gate::check('Manage Order') || Gate::check('Manage Company Settings')): ?>
            <li
                class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'settings' ? ' active dash-trigger' : ''); ?>">
                <a href="#!" class="dash-link ">
                    <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                        class="dash-mtext"><?php echo e(__('System Setup')); ?></span><span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                </a>

                <ul class="dash-submenu">
                    <?php if(Gate::check('Manage Company Settings')): ?>
                        <li
                            class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'settings' ? ' active dash-trigger' : ''); ?>">
                            <a href="#!" class="dash-link patch_link">
                                <span class="dash-mtext"><?php echo e(__('Settings')); ?></span>
                                <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="dash-submenu patch_submenu">
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="<?php echo e(route('settings.index')); ?>#business-settings"><?php echo e(__('Business Settings')); ?></a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="<?php echo e(route('settings.index')); ?>#system-settings"><?php echo e(__('System Settings')); ?></a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="<?php echo e(route('settings.index')); ?>#company-settings"><?php echo e(__('Company Settings')); ?></a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="<?php echo e(route('settings.index')); ?>#email-notification-settings"><?php echo e(__('Email Notification Settings')); ?></a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="<?php echo e(route('settings.index')); ?>#google-calender"><?php echo e(__('Google Calendar Settings')); ?></a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="<?php echo e(route('settings.index')); ?>#Leave-setting"><?php echo e(__('Leave setting')); ?></a>
                                </li>
                            </ul>
                        </li>


                        <li class="dash-item dash-hasmenu">
                            <a href="#!" class="dash-link patch_link2"><span
                                    class="dash-mtext"><?php echo e(__('HR Setup')); ?></span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu patch_submenu2">
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('branch.index')); ?>"><?php echo e(__('Branch')); ?></a></li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('department.index')); ?>"><?php echo e(__('Department')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('designation.index')); ?>"><?php echo e(__('Designation')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('leavetype.index')); ?>"><?php echo e(__('Leave Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('document.index')); ?>"><?php echo e(__('Document Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('paysliptype.index')); ?>"><?php echo e(__('Pay Slab')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('goaltype.index')); ?>"><?php echo e(__('Goal Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('trainingtype.index')); ?>"><?php echo e(__('Training Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('awardtype.index')); ?>"><?php echo e(__('Award Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('loanoption.index')); ?>"><?php echo e(__('Loan Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('terminationtype.index')); ?>"><?php echo e(__('Termination Type')); ?></a>
                                </li>

                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('job-category.index')); ?>"><?php echo e(__('Job Category')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('job-stage.index')); ?>"><?php echo e(__('Job Stage')); ?></a>
                                </li>

                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('performanceType.index')); ?>"><?php echo e(__('Performance Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('competencies.index')); ?>"><?php echo e(__('Competencies')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('expensetype.index')); ?>"><?php echo e(__('Expense Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('incometype.index')); ?>"><?php echo e(__('Income Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('paymenttype.index')); ?>"><?php echo e(__('Payment Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('contract_type.index')); ?>"><?php echo e(__('Contract Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('commuting_type.index')); ?>"><?php echo e(__('Commutting Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('city_tier.index')); ?>"><?php echo e(__('City Tier')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('category_type.index')); ?>"><?php echo e(__('Reimbursement Expenses')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('visit_type.index')); ?>"><?php echo e(__('Grade Type')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('grade_type.index')); ?>"><?php echo e(__('Assign Grade')); ?></a>
                                </li>
                            </ul>
                        </li>


                        <li
                            class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'letter_setting' ? ' active' : ''); ?>">
                            <a href="#!" class="dash-link patch_link3"><span
                                    class="dash-mtext"><?php echo e(__('Letter setting')); ?></span><span
                                    class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu patch_submenu3">
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('letter_setting')); ?>#offer-letter-settings"><?php echo e(__('Offer Letter Settings')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('letter_setting')); ?>#joining-letter-settings"><?php echo e(__('Joining Letter Settings')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('letter_setting')); ?>#increment-letter-settings"><?php echo e(__('Increment Letter Settings')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('letter_setting')); ?>#Relieving-letter-settings"><?php echo e(__('Relieving Letter')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('letter_setting')); ?>#experience-certificate-settings"><?php echo e(__('Certificate of Experience Settings')); ?></a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="<?php echo e(route('letter_setting')); ?>#noc-settings"><?php echo e(__('No Objection Certificate Settings')); ?></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if(Gate::check('Manage Plan')): ?>
                        <li
                            class="dash-item<?php echo e(Request::route()->getName() == 'plans.index' || Request::route()->getName() == 'stripe' ? ' active' : ''); ?>">
                            <a href="<?php echo e(route('plans.index')); ?>"
                                class="dash-link"><?php echo e(__('Setup Subscription Plan')); ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="dash-item <?php echo e(Request::segment(1) == 'order' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('order.index')); ?>" class="dash-link"><?php echo e(__('Invoice')); ?></a>
                    </li>

                </ul>
            </li>
        <?php endif; ?>

        <!--------------------- End System Setup ----------------------------------->

    </ul>

</div>
</div>
</nav>
<script type="text/javascript">
    $('.patch_link').on("click", function() {
        setTimeout(function() {
            $('.patch_submenu').removeAttr("style");
            $('.patch_submenu').css('display', 'block');
        }, 200);
    });
    // $('.patch_link2').on("click", function(){ setTimeout(function(){ $('.patch_submenu2').removeAttr("style"); $('.patch_submenu2').css('display', 'block'); }, 200);  });
    // $('.patch_link3').on("click", function(){ setTimeout(function(){ $('.patch_submenu3').removeAttr("style"); $('.patch_submenu3').css('display', 'block'); }, 200);  });
</script>
<?php /**PATH /var/www/html/resources/views/partial/Admin/companyMenu.blade.php ENDPATH**/ ?>