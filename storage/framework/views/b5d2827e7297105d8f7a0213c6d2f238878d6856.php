<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Employee')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('employee')); ?>"><?php echo e(__('Employee')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Manage Employee')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <div class="float-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit employee')): ?>
            <a href="<?php echo e(route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id))); ?>"
                data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>"class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a>
        <?php endif; ?>
    </div>
    <div class="text-end mb-3">
        <div class="d-flex justify-content-end drp-languages">
            <ul class="list-unstyled mb-0 m-2">
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="drp-text hide-mob text-primary"> <?php echo e(__('Joining Letter')); ?>

                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="<?php echo e(route('joiningletter.download.pdf', $employee->id)); ?>" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i><?php echo e(__('PDF')); ?></a>
                        <a href="<?php echo e(route('joininglatter.download.doc', $employee->id)); ?>" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i><?php echo e(__('DOC')); ?></a>
                    </div>
                </li>
            </ul>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Report')): ?>
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="drp-text hide-mob text-primary"> <?php echo e(__('Experience Certificate')); ?>

                                <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown">
                            <a href="<?php echo e(route('exp.download.pdf', $employee->id)); ?>" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download ">&nbsp;</i><?php echo e(__('PDF')); ?></a>
                            <a href="<?php echo e(route('exp.download.doc', $employee->id)); ?>" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download ">&nbsp;</i><?php echo e(__('DOC')); ?></a>
                        </div>
                    </li>
                </ul>
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="drp-text hide-mob text-primary"> <?php echo e(__('NOC')); ?>

                                <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown">
                            <a href="<?php echo e(route('noc.download.pdf', $employee->id)); ?>" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download ">&nbsp;</i><?php echo e(__('PDF')); ?></a>
                            <a href="<?php echo e(route('noc.download.doc', $employee->id)); ?>" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download ">&nbsp;</i><?php echo e(__('DOC')); ?></a>
                        </div>
                    </li>
                </ul>
            <?php endif; ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="employ_dtl">
                        <div class="emploimg">

                            <?php

                                $profile = \App\Models\Utility::get_file('uploads/avatar/');

                                $imagePath = '';

                                if (Auth::user()->type == 'company') {
                                    $imagePath = !empty($users->avatar)
                                        ? $profile . $users->avatar
                                        : $profile . 'avatar.png';
                                } else {
                                    if (empty($employee->employee_profile)) {
                                        // dd('if');
                                        $imagePath = $profile . 'avatar.png';
                                    } else {
                                        $imagePath = $employee->employee_profile_image;
                                        // dd('else', $imagePath);
                                    }
                                }
                            ?>

                            <a href="<?php echo e($imagePath); ?>" target="_blank">
                                <img src="<?php echo e($imagePath); ?>" class="rounded-circle" style="width: 25%">
                                
                            </a>
                        </div>
                        <div class="employctn">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <h5 class="mt-2"> <?php echo e($employee->name); ?></h5>
                                    <p> <?php echo e($employee->employee_id); ?></p>
                                    <a href="<?php echo e(route('employeeProfileEdit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id ?? 0))); ?>"
                                        class="btn btn-sm btn-info" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Edit">
                                        Edit Profile
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="info text-sm mb-3 font-style">
                                        <strong class="font-bold">Email :</strong>
                                        <span><?php echo e($employee->email); ?></span>
                                    </div>
                                    <div class="info text-sm">
                                        <strong class="font-bold">Phone :</strong>
                                        <span><?php echo e($employee->phone); ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="info mb-3 text-sm">
                                        <strong class="font-bold">Phone :</strong>
                                        <span><?php echo e($employee->phone); ?></span>
                                    </div>
                                    <div class="info  text-sm">
                                        <strong class="font-bold">Date of Birth :</strong>
                                        <span><?php echo e(\Auth::user()->dateFormat($employee->dob)); ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="info mb-3 text-sm">
                                        <strong class="font-bold">Status :</strong>
                                        <?php if($employee->employee_exit == 1): ?>
                                            <span class="text-danger"> Leave</span>
                                        <?php else: ?>
                                            <span class="text-success"> Active</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="info mb-3 text-sm">
                                        <strong class="font-bold"><?php echo e(__('App Login')); ?> :</strong>
                                        <?php if(($employee->app_login ?? 1) == 1): ?>
                                            <span class="text-success"><?php echo e(__('Allowed')); ?></span>
                                        <?php else: ?>
                                            <span class="text-danger"><?php echo e(__('Not Allowed')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="info text-sm">
                                        <strong class="font-bold"><?php echo e(__('Web Login')); ?> :</strong>
                                        <?php if(($employee->web_login ?? 1) == 1): ?>
                                            <span class="text-success"><?php echo e(__('Allowed')); ?></span>
                                        <?php else: ?>
                                            <span class="text-danger"><?php echo e(__('Not Allowed')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>



                            </div>


                        </div>
                    </div>
                    <hr>
                    <nav>
                        <div class="nav nav-tabs tablists mb-3" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-Salary-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Salary" type="button" role="tab" aria-controls="nav-Salary"
                                aria-selected="true">Personal Detail</button>

                            <button class="nav-link" id="nav-Allowance-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Allowance" type="button" role="tab"
                                aria-controls="nav-Allowance" aria-selected="false">Company</button>

                            <button class="nav-link" id="nav-salary-and-deductions-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-salary-and-deductions" type="button" role="tab"
                                aria-controls="nav-salary-and-deductions" aria-selected="false">Salary Structure</button>

                            <button class="nav-link" id="nav-Experience-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Experience" type="button" role="tab"
                                aria-controls="nav-Experience" aria-selected="false">Experience</button>

                            <button class="nav-link" id="nav-Education-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Education" type="button" role="tab"
                                aria-controls="nav-Education" aria-selected="false"> Education</button>

                            <button class="nav-link" id="nav-Dependent-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Dependent" type="button" role="tab"
                                aria-controls="nav-Dependent" aria-selected="false">Dependent</button>

                            <button class="nav-link" id="nav-Commission-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Commission" type="button" role="tab"
                                aria-controls="nav-Commission" aria-selected="false">Document</button>

                            <button class="nav-link" id="nav-Loan-tab" data-bs-toggle="tab" data-bs-target="#nav-Loan"
                                type="button" role="tab" aria-controls="nav-Loan" aria-selected="false">Bank
                                Account</button>

                            <button class="nav-link" id="nav-Payslip-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Payslip" type="button" role="tab" aria-controls="nav-Loan"
                                aria-selected="false">Payslip</button>

                        </div>
                    </nav>
                    <div class="tab-content tab_ctn" id="nav-tabContent">

                        <div class="tab-pane fade active show" id="nav-Salary" role="tabpanel"
                            aria-labelledby="nav-Salary-tab">
                            <div class="card ">
                                <div class="card-body employee-detail-body fulls-card emp-card">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold"><?php echo e(__('Employee ID')); ?> : </strong>
                                                <span><?php echo e($employee->employee_id); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm font-style">
                                                <strong class="font-bold"><?php echo e(__('Name')); ?> :</strong>
                                                <span><?php echo e($employee->name); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm font-style">
                                                <strong class="font-bold"><?php echo e(__('Email')); ?> :</strong>
                                                <span><?php echo e($employee->email); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold"><?php echo e(__('Date of Birth')); ?> :</strong>
                                                <span><?php echo e(\Auth::user()->dateFormat($employee->dob)); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold"><?php echo e(__('Phone')); ?> :</strong>
                                                <span><?php echo e($employee->phone); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold"><?php echo e(__('Address')); ?> :</strong>
                                                <span><?php echo e($employee->address); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold"><?php echo e(__('Marital')); ?> :</strong>
                                                <span><?php echo e($employee->marital_status); ?></span>
                                            </div>
                                        </div>
                                        <?php if(!empty($userdata)): ?>
                                            <div class="col-md-6">
                                                <div class="info text-sm">
                                                    <strong class="font-bold"><?php echo e(__('Team Leader')); ?> :</strong>
                                                    <span><?php echo e($userdata->name); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold"><?php echo e(__('Basic Salary')); ?> :</strong>
                                                <span><?php echo e($employee->salary); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold"><?php echo e(__('Gender')); ?> :</strong>
                                                <span><?php echo e($employee->gender); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold"><?php echo e(__('Personal Email')); ?> :</strong>
                                                <span><?php echo e($employee->personal_email); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Allowance" role="tabpanel"
                            aria-labelledby="nav-Allowance-tab">
                            <!-- Company -->
                            <!-- company data hear -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card ">
                                        <div class="card-body employee-detail-body fulls-card emp-card">
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Branch')); ?> : </strong>
                                                        <span><?php echo e(!empty($employee->branch) ? $employee->branch->name : ''); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm font-style">
                                                        <strong class="font-bold"><?php echo e(__('Department')); ?> :</strong>
                                                        <span><?php echo e(!empty($employee->department) ? $employee->department->name : ''); ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Designation')); ?> :</strong>
                                                        <span><?php echo e(!empty($employee->designation) ? $employee->designation->name : ''); ?></span>
                                                    </div>
                                                </div>
                                                <?php
                                                    $descriptionToShow = '';
                                                    if (!empty($employee->designation_description)) {
                                                        $descriptionToShow = $employee->designation_description;
                                                    } elseif (
                                                        !empty($employee->designation) &&
                                                        !empty($employee->designation->description)
                                                    ) {
                                                        $descriptionToShow = $employee->designation->description;
                                                    }
                                                ?>
                                                <?php if(!empty($descriptionToShow)): ?>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Designation Description')); ?>

                                                                :</strong>
                                                            <span><?php echo e($descriptionToShow); ?></span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if(!empty($employee->designation->document)): ?>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm font-style">
                                                            <strong class="font-bold"><?php echo e(__('Designation Document')); ?>

                                                                :</strong>
                                                            <a href="<?php echo e($employee->designation->document); ?>"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-primary"><?php echo e(__('View Document')); ?></a>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Employee Status')); ?> :</strong>
                                                        <span><?php echo e($employee->employe_status); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Employee Types')); ?> :</strong>
                                                        <span><?php echo e($employee->employe_types); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Experience')); ?> :</strong>
                                                        <span><?php echo e($employee->employee_experience); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Date on Exit')); ?> :</strong>
                                                        <span><?php echo e(\Auth::user()->dateFormat($employee->date_of_exit)); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Date Of Joining')); ?> :</strong>
                                                        <span><?php echo e(\Auth::user()->dateFormat($employee->company_doj)); ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Pay Slab')); ?> :</strong>
                                                        <span><?php echo e($employee->playslab()->get()->first()->name ?? ''); ?></span>
                                                    </div>
                                                </div>

                                                <?php
                                                
                                                $date = '';
                                                if ($employee->probation_period_date != null || !empty($employee->probation_period_date)) {
                                                    if (date('Y-m-d') <= date('Y-m-d', strtotime($employee->probation_period_date))) {
                                                        $date = \Auth::user()->dateFormat($employee->probation_period_date);
                                                    } else {
                                                        $date = \Auth::user()->dateFormat($employee->probation_period_date) . ' | Probation Period is over.';
                                                    }
                                                }
                                                ?>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Probation Date')); ?> :</strong>
                                                        <span><?php echo e($date); ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Facility For')); ?> :</strong>
                                                        <span><?php echo e($employee->office_staff == 1 ? 'Office Staff' : 'Field Staff'); ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Job Description')); ?> :</strong>
                                                        <span><?php echo e($employee->Job_description ?? ''); ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="tab-pane fade" id="nav-salary-and-deductions" role="tabpanel"
                            aria-labelledby="nav-salary-and-deductions-tab">
                            <div class="card">
                                
                                <div class="card-body employee-detail-create-body">

                                    
                                    <?php if(empty($payslipDetail) || $payslipDetail == ''): ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="info text-sm text-center">
                                                    <br>
                                                    <br>
                                                    <h5><?php echo e(__('Setup Salary Structure')); ?></h5>

                                                    <?php
                                                    
                                                    if (Auth::user()->type != 'employee' && Auth::user()->hasPermissionTo('Edit Employee')) {
                                                        $editLink = '<div class="action-btn bg-info ms-2"><a href="' . route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) . '" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="' . __('Edit') . '"><i class="ti ti-pencil text-white"></i></a></div>';
                                                    
                                                        echo $editLink;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?php
                                            $digit = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                        ?>

                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info text-sm">
                                                    <h5><?php echo e(__('Monthly CTC')); ?></h5>
                                                    <span><?php echo e($payslipDetail->monthlyCtc); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        


                                        
                                        <div class="row">

                                            <div class="col-md-12">
                                                <u>
                                                    <h5 style="text-align:left;"><?php echo e(__('Earnings')); ?>

                                                    </h5>
                                                </u>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table" id="">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left"><?php echo e(__('Salary Components')); ?></th>
                                                                <th class="text-right"><?php echo e(__('Monthly Amount')); ?></th>
                                                                <th class="text-right"><?php echo e(__('Annual Amount')); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php
                                                            
                                                            $calculatedCtcPercentage = 0;
                                                            try {
                                                                $salary = $payslipDetail->monthelySalaryByCtc;
                                                                $ctc_monthly = $employee->ctc_annual / 12;
                                                            
                                                                $calculatedCtcPercentage = ($salary / $ctc_monthly) * 100;
                                                            
                                                                if (is_nan($calculatedCtcPercentage) || !is_finite($calculatedCtcPercentage)) {
                                                                    $calculatedCtcPercentage = 0;
                                                                }
                                                            } catch (\Throwable $th) {
                                                            }
                                                            
                                                            ?>
                                                            <tr>
                                                                <td class="text-left">Basic
                                                                    (<?php echo e(numberFormat($calculatedCtcPercentage, 2)); ?>%
                                                                    of CTC)</td>
                                                                <td class="text-right">
                                                                    <?php echo e(priceFormat($payslipDetail->monthelySalaryByCtc)); ?>

                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo e(priceFormat($payslipDetail->yearlySalaryByCtc)); ?>

                                                                </td>
                                                            </tr>

                                                            <?php $__currentLoopData = $payslipDetail->earnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $earningName => $earnings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(is_object($earnings)): ?>
                                                                    <?php $__currentLoopData = $earnings->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $earnongRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                            $rowTitle = '';

                                                                            if ($earnongRow->type == 'percentage') {
                                                                                $rowTitle =
                                                                                    $earnongRow->name .
                                                                                    " ($earnongRow->percentage%)";
                                                                            } else {
                                                                                $rowTitle = $earnongRow->name;
                                                                            }
                                                                        ?>

                                                                        <tr>
                                                                            <td class="text-left"><?php echo e($rowTitle); ?></td>
                                                                            <td class="text-right">
                                                                                <?php echo e(priceFormat($earnongRow->amount)); ?>

                                                                            </td>
                                                                            <td class="text-right">
                                                                                <?php echo e(priceFormat($earnongRow->amount * 12)); ?>

                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                        </tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-right"><b>Gross Earnings :
                                                                    <?php echo e(priceFormat($payslipDetail->grossSalary)); ?></td>
                                                            <td></b></td>
                                                        </tr>
                                                        <tfoot>

                                                        </tfoot>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        

                                        <br>

                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <u>
                                                    <h5 style="text-align:left;"><?php echo e(__('Deductions')); ?>

                                                    </h5>
                                                </u>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table" id="">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left"><?php echo e(__('Salary Components')); ?></th>
                                                                <th class="text-right"><?php echo e(__('Monthly Amount')); ?></th>
                                                                <th class="text-right"><?php echo e(__('Annual Amount')); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php $__currentLoopData = $payslipDetail->deductions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deductionName => $deductions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(is_object($deductions)): ?>
                                                                    <?php $__currentLoopData = $deductions->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deductionsRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                            $rowTitle = '';

                                                                            if ($deductionsRow->type == 'percentage') {
                                                                                $rowTitle =
                                                                                    $deductionsRow->name .
                                                                                    " ($deductionsRow->percentage%)";
                                                                            } else {
                                                                                $rowTitle = $deductionsRow->name;
                                                                            }
                                                                        ?>

                                                                        <tr>
                                                                            <td class="text-left"><?php echo e($rowTitle); ?>

                                                                            </td>
                                                                            <td class="text-right">
                                                                                <?php echo e(priceFormat($deductionsRow->amount)); ?>

                                                                            </td>
                                                                            <td class="text-right">
                                                                                <?php echo e(priceFormat($deductionsRow->amount * 12)); ?>

                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                        </tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-right"><b>Gross Deductions :
                                                                    <?php echo e(priceFormat($payslipDetail->totalDeductions)); ?>

                                                            </td>
                                                            <td></b></td>
                                                        </tr>
                                                        <tfoot>

                                                        </tfoot>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        

                                        <br>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <u>
                                                    <h5 style="text-align:left;"><?php echo e(__("Employer's Contribution")); ?>

                                                    </h5>
                                                </u>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table" id="">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left"><?php echo e(__('Salary Components')); ?></th>
                                                                <th class="text-right"><?php echo e(__('Monthly Amount')); ?></th>
                                                                <th class="text-right"><?php echo e(__('Annual Amount')); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            
                                                            <tr>
                                                                <td class="text-left">Gross Earnings </td>
                                                                <td class="text-right">
                                                                    <?php echo e(priceFormat($payslipDetail->employeeBasicSalary + $payslipDetail->totalEarnings)); ?>

                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo e(priceFormat(($payslipDetail->employeeBasicSalary + $payslipDetail->totalEarnings) * 12)); ?>

                                                                </td>
                                                            </tr>
                                                            


                                                            
                                                            <?php
                                                                $providentFundCompanyDeductionTotal =
                                                                    $payslipDetail->providentFundCompanyDeduction
                                                                        ->total;
                                                                $providentFundCompanyDeductionData =
                                                                    $payslipDetail->providentFundCompanyDeduction->data;
                                                            ?>
                                                            <?php if(!empty($providentFundCompanyDeductionData)): ?>
                                                                <tr>
                                                                    <td class="text-left">
                                                                        <?php echo e($providentFundCompanyDeductionData->name); ?>

                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo e(priceFormat($providentFundCompanyDeductionData->amount)); ?>

                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo e(priceFormat($providentFundCompanyDeductionData->amount * 12)); ?>

                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            

                                                            
                                                            <?php
                                                                $employeesStateInsuranceCompanyDeductionTotal =
                                                                    $payslipDetail
                                                                        ->employeesStateInsuranceCompanyDeduction
                                                                        ->total;
                                                                $employeesStateInsuranceCompanyDeductionData =
                                                                    $payslipDetail
                                                                        ->employeesStateInsuranceCompanyDeduction->data;
                                                            ?>
                                                            <?php if(!empty($employeesStateInsuranceCompanyDeductionData)): ?>
                                                                <tr>
                                                                    <td class="text-left">
                                                                        <?php echo e($employeesStateInsuranceCompanyDeductionData->name); ?>

                                                                    </td>

                                                                    <td class="text-right">
                                                                        <?php echo e(priceFormat($employeesStateInsuranceCompanyDeductionData->amount)); ?>

                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo e(priceFormat($employeesStateInsuranceCompanyDeductionData->amount * 12)); ?>

                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            

                                                            
                                                            <?php
                                                                $statutoryBonusTotal =
                                                                    $payslipDetail->statutoryBonus->total;
                                                                $statutoryBonusData =
                                                                    $payslipDetail->statutoryBonus->data;

                                                            ?>
                                                            <?php if(!empty($statutoryBonusData)): ?>
                                                                <tr class="row-border">
                                                                    <td class="text-left">
                                                                        <?php echo e($statutoryBonusData->name); ?>

                                                                    </td>

                                                                    <td class="text-right">
                                                                        <?php echo e(priceFormat($statutoryBonusData->amount)); ?>

                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo e(priceFormat($statutoryBonusData->amount * 12)); ?>

                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            

                                                            
                                                            <tr class="row-border" style="display: ;">
                                                                <?php
                                                                    $ctc =
                                                                        $payslipDetail->employeeBasicSalary +
                                                                        $payslipDetail->totalEarnings +
                                                                        $providentFundCompanyDeductionTotal +
                                                                        $employeesStateInsuranceCompanyDeductionTotal +
                                                                        $statutoryBonusTotal;
                                                                ?>

                                                                <td colspan="2" class="text-right">
                                                                    <b>Total : <?php echo e(priceFormat($ctc)); ?></b>
                                                                </td>
                                                            </tr>
                                                            

                                                            
                                                            <tr style="display: none;">
                                                                <td class="text-left">
                                                                    <b>Total CTC : <?php echo e(priceFormat($ctc)); ?></b>
                                                                </td>
                                                            </tr>

                                                            <tr style="display: none;">
                                                                <td class="text-left">
                                                                    <b>In Words :
                                                                        <?php echo e(ucwords($digit->format($ctc))); ?></b>
                                                                </td>
                                                            </tr>
                                                            

                                                        </tbody>

                                                        <tfoot>

                                                        </tfoot>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        

                                        <br>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info text-sm">
                                                    <h5>Net Pay :
                                                        <?php echo e(priceFormat($payslipDetail->employeeNewPayableSalary)); ?>

                                                    </h5>
                                                    <h5>In Words :
                                                        (<?php echo e(ucwords($digit->format($payslipDetail->employeeNewPayableSalary))); ?>)
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        
                                    <?php endif; ?>



                                </div>



                            </div>
                        </div>
                        

                        <div class="tab-pane fade" id="nav-Commission" role="tabpanel"
                            aria-labelledby="nav-Commission-tab">
                            <!-- Document -->

                            <?php
                                $employeedoc = $employee->documents()->pluck('document_value', 'document_id');
                                $logo = \App\Models\Utility::get_file('uploads/document');
                            ?>


                            <?php if(!$documents->isEmpty() && !empty($employeedoc->toArray())): ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card ">
                                        <div class="card-body employee-detail-body fulls-card emp-card">
                                            <div class="row">

                                                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e($document->name); ?> : </strong>


                                                            <?php
                                                            
                                                            $element = '';
                                                            
                                                            $htmlElement = '';
                                                            $filePath = !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : '';
                                                            if (!empty($filePath)) {
                                                                $fileName = basename(strrchr($filePath, '/'), '/');
                                                            
                                                                // Define a regular expression pattern for common image file extensions
                                                                $imageExtensionsPattern = '/\.(jpg|jpeg|png|gif|bmp|svg)$/i';
                                                            
                                                                // Check if the file name matches the pattern
                                                                if (preg_match($imageExtensionsPattern, $fileName)) {
                                                                    $element = "<img src='$filePath' width='50%' />";
                                                                } else {
                                                                    $element = 'View';
                                                                }
                                                            
                                                                $htmlElement = '<span><a href="' . $filePath . '" target="_blank">' . $element . '</a></span>';
                                                            } else {
                                                                $htmlElement = '<label>No document is added</label>';
                                                            }
                                                            
                                                            ?>

                                                            <?php echo $htmlElement; ?>

                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center">No documents detail have been added!</h5>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        
                        <div class="tab-pane fade" id="nav-Experience" role="tabpanel"
                            aria-labelledby="nav-Experience-tab">
                            


                            <?php if(empty($EXPTableData->toArray())): ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center">No experience detail have been added!</h5>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php $__currentLoopData = $EXPTableData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="card ">
                                            <div class="card-body employee-detail-body fulls-card emp-card">
                                                <h5><?php echo e($key + 1); ?> <?php echo e(__('Work Experience')); ?></h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Previous Company')); ?> :
                                                            </strong>
                                                            <span><?php echo e($exp->previous_company_name); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm font-style">
                                                            <strong class="font-bold"><?php echo e(__('Job Title')); ?> :</strong>
                                                            <span><?php echo e($exp->previous_job_title); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('From Date')); ?> :</strong>
                                                            <span><?php echo e($exp->previous_from_date); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('To Date')); ?> :</strong>
                                                            <span><?php echo e($exp->previous_to_date); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Designation')); ?> :</strong>
                                                            <?php //$data = App\Models\Designation::where('id', $exp->previous_company_designation_id)->first();
                                                            ?>
                                                            
                                                            <span><?php echo e($exp->previous_company_designation_id); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong
                                                                class="font-bold"><?php echo e(__('Experience Job Description')); ?>

                                                                :</strong>
                                                            <span><?php echo e($exp->experiance_Job_description); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            
                        </div>

                        
                        <div class="tab-pane fade" id="nav-Education" role="tabpanel"
                            aria-labelledby="nav-Education-tab">
                            

                            <?php if(empty($EducationData->toArray())): ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center">No education detail have been added!</h5>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php $__currentLoopData = $EducationData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="card ">
                                            <div class="card-body employee-detail-body fulls-card emp-card">
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Institute Name')); ?> :
                                                            </strong>
                                                            <span><?php echo e($edu->institute_name); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm font-style">
                                                            <strong class="font-bold"><?php echo e(__('Education')); ?> :</strong>
                                                            <span><?php echo e($edu->education); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Specialization')); ?>

                                                                :</strong>
                                                            <span><?php echo e($edu->specialization); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Date of completion')); ?>

                                                                :</strong>
                                                            <span><?php echo e($edu->date_of_completion); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Notes')); ?> :</strong>
                                                            <span><?php echo e($edu->notes); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            
                        </div>
                        

                        
                        <div class="tab-pane fade" id="nav-Dependent" role="tabpanel"
                            aria-labelledby="nav-Dependent-tab">
                            

                            <?php if(empty($DependentData->toArray())): ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center">No dependent detail have been added!</h5>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php $__currentLoopData = $DependentData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="card ">
                                            <div class="card-body employee-detail-body fulls-card emp-card">
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Name')); ?> : </strong>
                                                            <span><?php echo e($edu->dependent_name); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm font-style">
                                                            <strong class="font-bold"><?php echo e(__('Relation ')); ?> :</strong>
                                                            <span><?php echo e($edu->dependent_relation); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Date of completion')); ?>

                                                                :</strong>
                                                            <span><?php echo e($edu->dependent_dob); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Emergency phone no')); ?>

                                                                :</strong>
                                                            <span><?php echo e($edu->emergency_phone_no); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            
                        </div>

                        <div class="tab-pane fade" id="nav-Loan" role="tabpanel" aria-labelledby="nav-Loan-tab">

                            <?php if(
                                !empty($employee->account_holder_name) ||
                                    !empty($employee->account_number) ||
                                    !empty($employee->bank_name) ||
                                    !empty($employee->bank_identifier_code) ||
                                    !empty($employee->branch_location) ||
                                    !empty($employee->tax_payer_id)): ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card ">
                                        <div class="card-body employee-detail-body fulls-card emp-card">
                                            
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Account Holder Name')); ?> :
                                                        </strong>
                                                        <span><?php echo e($employee->account_holder_name); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm font-style">
                                                        <strong class="font-bold"><?php echo e(__('Account Number')); ?> :</strong>
                                                        <span><?php echo e($employee->account_number); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Bank Name')); ?> :</strong>
                                                        <span><?php echo e($employee->bank_name); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Bank Identifier Code')); ?>

                                                            :</strong>
                                                        <span><?php echo e($employee->bank_identifier_code); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Branch Location')); ?> :</strong>
                                                        <span><?php echo e($employee->branch_location); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold"><?php echo e(__('Tax Payer Id')); ?> :</strong>
                                                        <span><?php echo e($employee->tax_payer_id); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center"> No bank account have been added!</h5>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="nav-Payslip" role="tabpanel" aria-labelledby="nav-Payslip-tab">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-start mb-4">
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-6 col-lg-4">
                                                        <div class="btn-box">
                                                            <select class="form-control month_date " name="year"
                                                                tabindex="-1" aria-hidden="true">
                                                                <?php $__currentLoopData = $month; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $mon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                    $selected = '';
                                                                    if ($k == date('m', strtotime('current month'))) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo e($k); ?>"
                                                                        <?php echo e($selected); ?>><?php echo e($mon); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-4">
                                                        <div class="btn-box">

                                                            <select class="form-control year_date " name="year"
                                                                tabindex="-1" aria-hidden="true">
                                                                <?php for($i = 0; $i <= $yearsCount; $i++): ?>
                                                                    <?php
                                                                    $selected = '';
                                                                    $value = date('Y', strtotime("-$i year"));
                                                                    
                                                                    if ($value == date('Y')) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo e($value); ?>"
                                                                        <?php echo e($selected); ?>><?php echo e($value); ?></option>
                                                                <?php endfor; ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body px-0">
                                        <div class="table-responsive">
                                            <table class="table" id="pc-dt-render-column-cells">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo e(__('Employee Id')); ?></th>
                                                        
                                                        <th><?php echo e(__('Payroll Type')); ?></th>
                                                        <th><?php echo e(__('Salary')); ?></th>
                                                        <th><?php echo e(__('Net Salary')); ?></th>
                                                        <th><?php echo e(__('Status')); ?></th>
                                                        <th><?php echo e(__('Action')); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).ready(function() {
            callback();

            function callback() {
                var month = $(".month_date").val() ?? '';
                var year = $(".year_date").val() ?? '';

                $('.filter_month').val(month);
                $('.filter_year').val(year);

                if (month == '') {
                    month = '<?php echo e(date('m', strtotime('current month'))); ?>';
                    year = '<?php echo e(date('Y')); ?>';

                    $('.filter_month').val(month);
                    $('.filter_year').val(year);
                }

                var datePicker = year + '-' + month;

                $.ajax({
                    url: "<?php echo e(route('payslip.search_json')); ?>",
                    type: 'POST',
                    data: {
                        "datePicker": datePicker,
                        "_token": "<?php echo e(csrf_token()); ?>",
                        "employee_id": "<?php echo e($employee->id); ?>",
                    },
                    success: function(data) {
                        console.log(data);
                        var datatable_data = {
                            data: data
                        };

                        function renderstatus(data, cell, row) {
                            if (data == 'Paid')
                                return '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    data + '</a></div>';
                            else
                                return '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    data + '</a></div>';
                        }

                        function renderButton(data, cell, row) {

                            var $div = $(row);
                            employee_id = $div.find('td:eq(0)').text();
                            status = $div.find('td:eq(6)').text();

                            var month = $(".month_date").val();
                            var year = $(".year_date").val();
                            var id = employee_id;
                            var payslip_id = data;

                            var clickToPaid = '';
                            var payslip = '';
                            var view = '';
                            var edit = '';
                            var deleted = '';
                            var form = '';

                            if (data != 0) {
                                var payslip =
                                    '<a href="#" data-url="<?php echo e(url('payslip/pdf/')); ?>/' + id +
                                    '/' + datePicker +
                                    '" data-size="md-pdf"  data-ajax-popup="true" class="btn btn-primary" data-title="<?php echo e(__('Employee Payslip')); ?>">' +
                                    '<?php echo e(__('Payslip')); ?>' + '</a> ';
                            }

                            if (status == "UnPaid" && data != 0) {
                                clickToPaid = '<a href="<?php echo e(url('payslip/paysalary/')); ?>/' + id +
                                    '/' + datePicker + '"  class="view-btn primary-bg btn-sm">' +
                                    '<?php echo e(__('Click To Paid')); ?>' + '</a>  ';
                            }

                            if (data != 0) {
                                view =
                                    '<a href="#" data-url="<?php echo e(url('payslip/showemployee/')); ?>/' +
                                    payslip_id +
                                    '"  data-ajax-popup="true" class="view-btn gray-bg" data-title="<?php echo e(__('View Employee Detail')); ?>">' +
                                    '<?php echo e(__('View')); ?>' + '</a>';
                            }

                            if (data != 0 && status == "UnPaid") {
                                edit =
                                    '<a href="#" data-url="<?php echo e(url('payslip/editemployee/')); ?>/' +
                                    payslip_id +
                                    '"  data-ajax-popup="true" class="view-btn blue-bg" data-title="<?php echo e(__('Edit Employee salary')); ?>">' +
                                    '<?php echo e(__('Edit')); ?>' + '</a>';
                            }

                            var url = '<?php echo e(route('payslip.delete', ':id')); ?>';
                            url = url.replace(':id', payslip_id);

                            <?php if(\Auth::user()->type != 'employee'): ?>
                                if (data != 0) {
                                    deleted = '<a href="#"  data-url="' + url +
                                        '" class="payslip_delete view-btn red-bg" >' +
                                        '<?php echo e(__('Delete')); ?>' + '</a>';
                                }
                            <?php endif; ?>

                            return view + payslip + clickToPaid + edit + deleted + form;
                        }

                        console.clear();
                        var tr = '';
                        if (data.length > 0) {

                            $.each(data, function(indexInArray, valueOfElement) {
                                var status =
                                    '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    valueOfElement[6] + '</a></div>';
                                if (valueOfElement[6] == 'Paid') {
                                    var status =
                                        '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                        valueOfElement[6] + '</a></div>';
                                }

                                var id = valueOfElement[0];
                                var employee_id = valueOfElement[1];
                                var payslip_id = valueOfElement[7];

                                if (valueOfElement[7] != 0) {
                                    var payslip =
                                        '<a href="#" data-url="<?php echo e(url('payslip/pdf/')); ?>/' +
                                        id +
                                        '/' + datePicker +
                                        '" data-size="lg"  data-ajax-popup="true" class=" btn-sm btn btn-warning" data-title="<?php echo e(__('Employee Payslip')); ?>">' +
                                        '<?php echo e(__('Payslip')); ?>' + '</a> ';
                                }
                                if (valueOfElement[6] == "UnPaid" && valueOfElement[7] != 0) {
                                    var clickToPaid =
                                        '<a href="<?php echo e(url('payslip/paysalary/')); ?>/' + id +
                                        '/' + datePicker +
                                        '"  class="btn-sm btn btn-primary">' +
                                        '<?php echo e(__('Click To Paid')); ?>' + '</a>  ';
                                } else {
                                    var clickToPaid = '';
                                }

                                if (valueOfElement[7] != 0 && valueOfElement[6] == "UnPaid") {
                                    var edit =
                                        '<a href="#" data-url="<?php echo e(url('payslip/editemployee/')); ?>/' +
                                        payslip_id +
                                        '"  data-ajax-popup="true" class="btn-sm btn btn-info" data-title="<?php echo e(__('Edit Employee salary')); ?>">' +
                                        '<?php echo e(__('Edit')); ?>' + '</a>';
                                } else {
                                    var edit = '';
                                }

                                var url = '<?php echo e(route('payslip.delete', ':id')); ?>';
                                url = url.replace(':id', payslip_id);

                                <?php if(\Auth::user()->type != 'employee'): ?>
                                    if (valueOfElement[7] != 0) {
                                        var deleted = '<a href="#"  data-url="' + url +
                                            '" class="payslip_delete view-btn btn btn-danger ms-1 btn-sm"  >' +
                                            '<?php echo e(__('Delete')); ?>' + '</a>';
                                    } else {
                                        var deleted = '';
                                    }
                                <?php else: ?>
                                    var deleted = '';
                                <?php endif; ?>

                                var url_employee = valueOfElement['url'];

                                tr +=
                                    '<tr>' +
                                    '<td> <a class="btn btn-outline-primary" href="' +
                                    url_employee + '">' +
                                    valueOfElement[1] + '</a></td> ' +
                                    '<td>' + valueOfElement[2] + '</td> ' +
                                     '<td>' + valueOfElement[4] +
                                    '</td>' +
                                    '<td>' + valueOfElement[5] + '</td>' +
                                    '<td>' + status + '</td>' +

                                    '<td>' + payslip + clickToPaid + edit + deleted + '</td>' +

                                    '</tr>';
                            });
                        } else {
                            var colspan = $('#pc-dt-render-column-cells thead tr th').length;
                            var tr = '<tr><td class="dataTables-empty" colspan="' + colspan +
                                '"><?php echo e(__('No entries found')); ?></td></tr>';
                        }

                        $('#pc-dt-render-column-cells tbody').html(tr);
                        var table = document.querySelector("#pc-dt-render-column-cells");
                        var datatable = new simpleDatatables.DataTable(table);
                    },
                    error: function(data) {

                    }
                });
            }

            $(document).on("change", ".month_date,.year_date", function() {
                callback();
            });

            //bulkpayment Click
            $(document).on("click", "#bulk_payment", function() {

                var month = $(".month_date").val();
                var year = $(".year_date").val();
                var datePicker = year + '_' + month;

            });
            $(document).on('click', '#bulk_payment',
                'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',
                function() {

                    var month = $(".month_date").val();
                    var year = $(".year_date").val();
                    var datePicker = year + '-' + month;

                    var title = 'Bulk Payment';
                    var size = 'md';
                    var url = 'payslip/bulk_pay_create/' + datePicker;

                    // return false;

                    $("#commonModal .modal-title").html(title);
                    $("#commonModal .modal-dialog").addClass('modal-' + size);
                    $.ajax({
                        url: url,
                        success: function(data) {
                            if (data.length) {
                                $('#commonModal .body').html(data);
                                $("#commonModal").modal('show');
                                // common_bind();
                            } else {
                                show_toastr('error', 'Permission denied.');
                                $("#commonModal").modal('hide');
                            }
                        },
                        error: function(data) {
                            data = data.responseJSON;
                            show_toastr('error', data.error);
                        }
                    });
                });

            $(document).on("click", ".payslip_delete", function() {
                var confirmation = confirm("are you sure you want to delete this payslip?");
                var url = $(this).data('url');

                if (confirmation) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: "JSON",
                        success: function(data) {


                            show_toastr('success', 'Payslip Deleted Successfully', 'success');

                            setTimeout(function() {
                                location.reload();
                            }, 800)
                        },
                    });
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/employee/show.blade.php ENDPATH**/ ?>