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

            
            <li
                class="dash-item <?php echo e(Request::segment(1) == 'employee-profile-edit' || Request::segment(1) == 'employee-profile-show' ? 'active' : ''); ?>">

                <?php
                    $employee = App\Models\Employee::where('user_id', \Auth::user()->id)->first();
                ?>
                <a href="<?php echo e(route('employeeProfileShow', \Illuminate\Support\Facades\Crypt::encrypt($employee->id ?? 0))); ?>"
                    class="dash-link"><span class="dash-micon"><i class="ti ti-user"></i></span><span
                        class="dash-mtext"><?php echo e(__('Employee Details')); ?></span></a>
            </li>
            

            <li class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'setsalary' ? 'dash-trigger active' : ''); ?>">
                <a href="<?php echo e(route('my-payslip.index')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-receipt"></i></span><span
                        class="dash-mtext"><?php echo e(__('My Payslip')); ?></span></span></a>
            </li>

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
                                <a class="dash-link" href="<?php echo e(route('getpass.index')); ?>"><?php echo e(__('Gate Pass')); ?></a>
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
                                </ul>
                            </li>
                        <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <!--timesheet-->

        <!-- My Visit & Claim-->

        <li class="dash-item dash-hasmenu  <?php echo e(Request::segment(1) == 'setsalary' ? 'dash-trigger active' : ''); ?>">
            <a href="#!" class="dash-link">
                <span class="dash-micon"><i class="ti ti-file"></i></span>
                <span class="dash-mtext"><?php echo e(__('My Visit & Claim')); ?></span>
                <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="dash-submenu ">

                <li class="dash-item <?php echo e(Request::segment(1) == 'my_visit' ? 'active' : '-'); ?>">
                    <a class="dash-link" href="<?php echo e(route('my_visit.index')); ?>">
                        <?php echo e(__('My Visit')); ?>

                    </a>
                </li>
                <li class="dash-item <?php echo e(Request::segment(1) == 'my_claim' ? 'active' : '-'); ?>">
                    <a class="dash-link" href="<?php echo e(route('my_claim.index')); ?>">
                        <?php echo e(__('My Claim')); ?>

                    </a>
                </li>

            </ul>
        </li>
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
        
        <li class="dash-item">
            <a href="<?php echo e(route('company-policy.index')); ?>" class="dash-link"><span class="dash-micon"><i
                        class="ti ti-book"></i></span><span class="dash-mtext"><?php echo e(__('Company Policy')); ?>

                </span></a>
        </li>
        
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
                            <a class="dash-link" href="<?php echo e(route('training.index')); ?>"><?php echo e(__('Training List')); ?></a>
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
                                href="<?php echo e(route('job.application.candidate')); ?>"><?php echo e(__('Job Candidate')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Job OnBoard')): ?>
                        <li class="dash-item">
                            <a class="dash-link" href="<?php echo e(route('job.on.board')); ?>"><?php echo e(__('Job On-Boarding')); ?></a>
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
                            <a class="dash-link" href="<?php echo e(route('resignation.index')); ?>"><?php echo e(__('Resignation')); ?></a>
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


        <!--meeting-->
        
        
        


        <!-- Zoom meeting-->
        
        
        

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
<?php /**PATH /var/www/html/resources/views/partial/Admin/employeeMenu.blade.php ENDPATH**/ ?>