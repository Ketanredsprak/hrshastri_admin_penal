<label for=""></label>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Edit Employee')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('employee')); ?>"><?php echo e(__('Employee')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Edit Employee')); ?></li>
<?php $__env->stopSection(); ?>

<?php
    $isProfileEdit = false;
    if (Auth::user()->type != 'company') {
        $loggedInEmployee = App\Models\Employee::where('user_id', \Auth::user()->id)->first();
        if ($employee->id == ($loggedInEmployee->id ?? 0)) {
            $isProfileEdit = true;
        }
    }
?>

<?php $__env->startSection('header-section'); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        #calendar {
            margin-bottom: 100px;
        }

        #calendar .fc-view {
            margin-top: 0;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(255, 220, 40, 0.15);
            background-color: rgba(81, 69, 157, 0.1);
        }

        .selected-event {
            position: relative;
            background-color: #4caf50;
            /* Green fill for selected dates */
            /* color: #fff; */
            height: 100%;
            width: 100%
        }

        .employee-login-access-panel {
            border: 1px solid #e3e6ea;
            border-radius: 8px;
            background: #f8f9fb;
            padding: 12px 14px;
        }

        .employee-login-access-panel .panel-section-title {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .employee-login-access-panel .checkbox-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px 20px;
            align-items: center;
        }

        .employee-login-access-panel .form-check {
            margin-bottom: 0;
            min-height: auto;
        }

        .employee-login-access-panel .form-check-label {
            font-weight: 500;
            color: #344767;
            cursor: pointer;
        }

        .employee-login-access-panel .panel-divider {
            border-top: 1px solid #dee2e6;
            margin: 12px 0;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="row">
        <div class="col-sm-12">
            <?php echo e(Form::model($employee, ['route' => ['employee.update', $employee->id], 'id' => 'employee_update', 'method' => 'PUT', 'enctype' => 'multipart/form-data'])); ?>

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
                                        $imagePath = $profile . 'avatar.png';
                                    } else {
                                        $imagePath = $employee->employee_profile_image;
                                    }
                                }
                            ?>

                            <img alt="#" src="<?php echo e($imagePath); ?>" class="">
                        </div>
                        <div class="employctn">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <h5 class="mt-2"> <?php echo e($employee->name); ?></h5>
                                    
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="info text-sm mb-3 font-style">
                                        <strong class="font-bold">Email :</strong>
                                        <span><?php echo e($employee->email); ?></span>
                                    </div>
                                    <div class="info text-sm mb-3 font-style">
                                        <strong class="font-bold">Personal Email :</strong>
                                        <span><?php echo e($employee->personal_email); ?></span>
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
                                        <span><?php echo e(date('d-m-Y', strtotime(\Auth::user()->dateFormat($employee->getRawOriginal('dob'))))); ?></span>
                                    </div>
                                </div>

                                <?php if($isProfileEdit == false): ?>
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="employee-login-access-panel">
                                            <span class="panel-section-title"><?php echo e(__('Employee Status')); ?></span>
                                            <div class="checkbox-row">
                                                <div class="form-check">
                                            <input type="checkbox" <?php if($employee->employee_exit == 1): ?> checked <?php endif; ?>
                                                class="form-check-input isscheck isscheck_User" id="employee_exit"
                                                name="employee_exit" value="1">
                                                    <label class="form-check-label" for="employee_exit"><?php echo e(__('Exit')); ?></label>
                                        </div>
                                                <div class="form-check">
                                            <input type="checkbox" <?php if($employee->confirm_probation_period == 1): ?> checked <?php endif; ?>
                                                class="form-check-input isscheck isscheck_User"
                                                id="confirm_probation_period" name="confirm_probation_period"
                                                value="1">
                                                    <label class="form-check-label"
                                                        for="confirm_probation_period"><?php echo e(__('Confirm')); ?></label>
                                        </div>
                                            </div>
                                            <div class="panel-divider"></div>
                                            <span class="panel-section-title"><?php echo e(__('Login Access')); ?></span>
                                            <div class="checkbox-row">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                        <?php if(($employee->app_login ?? 1) == 1): ?> checked <?php endif; ?>
                                                        class="form-check-input isscheck isscheck_User" id="app_login"
                                                        name="app_login" value="1">
                                                    <label class="form-check-label"
                                                        for="app_login"><?php echo e(__('App Login')); ?></label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                        <?php if(($employee->web_login ?? 1) == 1): ?> checked <?php endif; ?>
                                                        class="form-check-input isscheck isscheck_User" id="web_login"
                                                        name="web_login" value="1">
                                                    <label class="form-check-label"
                                                        for="web_login"><?php echo e(__('Web Login')); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <nav>
                        <div class="nav nav-tabs tablists mb-3" id="nav-tab" role="tablist">

                            <button class="nav-link active" id="nav-personal-details-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-personal-details" type="button" role="tab"
                                aria-controls="nav-personal-details" aria-selected="true">Personal Detail</button>

                            <button class="nav-link" id="nav-Allowance-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Allowance" type="button" role="tab" aria-controls="nav-Allowance"
                                aria-selected="false">Company</button>

                            <button class="nav-link" id="nav-salary-and-deductions-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-salary-and-deductions" type="button" role="tab"
                                aria-controls="nav-salary-and-deductions" aria-selected="false">Salary Structure</button>

                            <button class="nav-link" id="nav-Experience-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Experience" type="button" role="tab"
                                aria-controls="nav-Experience" aria-selected="false">Experience</button>
                            <button class="nav-link" id="nav-Education-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Education" type="button" role="tab" aria-controls="nav-Education"
                                aria-selected="false"> Education</button>
                            <button class="nav-link" id="nav-Dependent-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Dependent" type="button" role="tab" aria-controls="nav-Dependent"
                                aria-selected="false">Dependent</button>
                            <button class="nav-link" id="nav-Commission-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Commission" type="button" role="tab"
                                aria-controls="nav-Commission" aria-selected="false">Document</button>
                            <button class="nav-link" id="nav-Loan-tab" data-bs-toggle="tab" data-bs-target="#nav-Loan"
                                type="button" role="tab" aria-controls="nav-Loan" aria-selected="false">Bank
                                Account</button>

                            <?php if($isProfileEdit == false): ?>
                                <button class="nav-link" id="nav-weekoff-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-weekoff" type="button" role="tab"
                                    aria-controls="nav-weekoff" aria-selected="false">Week
                                    off</button>
                            <?php endif; ?>
                        </div>
                    </nav>

                    <div class="tab-content tab_ctn" id="nav-tabContent">

                        <div class="tab-pane fade active show" id="nav-personal-details" role="tabpanel"
                            aria-labelledby="nav-personal-details-tab">

                            <div class="card">
                                <div class="card-header">
                                    <h5><?php echo e(__('Personal Detail')); ?></h5>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php echo Form::label('employee_id', __('Employee Id'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <?php echo Form::text('employee_id', $employee->employee_id, ['class' => 'form-control', 'readonly' => false]); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php echo Form::label('name', __('Name'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <?php echo Form::text('name', null, ['class' => 'form-control']); ?>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php echo Form::label('phone', __('Phone'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <?php echo Form::text('phone', null, ['class' => 'form-control']); ?>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php echo Form::label('dob', __('Date of Birth'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>

                                                <input type="date" name="dob"
                                                    value="<?php echo e(date('Y-m-d', strtotime($employee->getRawOriginal('dob')))); ?>"
                                                    class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php echo Form::label('employee_profile', __('Upload Employee Profile Picture'), ['class' => 'form-label']); ?>

                                                <small><a
                                                        href="<?php echo e(asset(Storage::url('uploads/employee_profile')) . '/' . $employee->employee_profile); ?>"
                                                        target="_blank">View
                                                        Profile Image</a></small>
                                                <input type="file" class="form-control" id="employee_profile"
                                                    name="employee_profile" accept="image/*">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <?php echo Form::label('gender', __('Gender'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <div class="d-flex radio-check">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="g_male" value="Male"
                                                            name="gender" class="form-check-input"
                                                            <?php echo e($employee->gender == 'Male' ? 'checked' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="g_male"><?php echo e(__('Male')); ?></label>
                                                    </div>
                                                    <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                        <input type="radio" id="g_female" value="Female"
                                                            name="gender" class="form-check-input"
                                                            <?php echo e($employee->gender == 'Female' ? 'checked' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="g_female"><?php echo e(__('Female')); ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <?php echo e(Form::label('marital_status', __('Select Marital '), ['class' => 'form-label'])); ?>

                                            <div class="form-icon-user">
                                                <select name="marital_status" id="marital_status"
                                                    class="form-control select2">
                                                    <option value="">-Select Marital-</option>
                                                    <option <?php if($employee->marital_status == 'Single'): ?> selected <?php endif; ?>
                                                        class="form-check-input" value="Single">Single</option>
                                                    <option <?php if($employee->marital_status == 'Married'): ?> selected <?php endif; ?>
                                                        class="form-check-input" value="Married">Married</option>
                                                    <option <?php if($employee->marital_status == 'Widowed'): ?> selected <?php endif; ?>
                                                        class="form-check-input" value="Widowed">Widowed</option>
                                                    <option <?php if($employee->marital_status == 'Separated'): ?> selected <?php endif; ?>
                                                        class="form-check-input" value="Separated">Separated</option>
                                                    <option <?php if($employee->marital_status == 'Divorced'): ?> selected <?php endif; ?>
                                                        class="form-check-input"value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 marriage_anniversary_date_div"
                                            style="display: <?php echo e($employee->marital_status == 'Married' || $employee->marital_status == 'Widowed' ? '' : 'none'); ?>">
                                            <?php echo Form::label('marriage_anniversary_date', __('Marriage Anniversary Date '), ['class' => 'form-label']); ?>

                                            <input type="date" name="marriage_anniversary_date"
                                                class="form-control current_date" max="<?php echo date('Y-m-d'); ?>"
                                                value="<?php echo e($employee->marriage_anniversary_date); ?>">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <?php echo Form::label('email', __('Email'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                            <?php echo Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter employee email']); ?>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <?php echo Form::label('personal_email', __('Personal Email'), ['class' => 'form-label']); ?>

                                            <?php echo Form::email('personal_email', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter employee personal email',
                                            ]); ?>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <?php echo Form::label('password', __('Password'), ['class' => 'form-label']); ?>

                                            <div class="password_box">
                                                <?php echo Form::password('password', [
                                                    'class' => 'form-control enterpassword',
                                                    'placeholder' => 'Enter password (leave blank to keep current)',
                                                ]); ?>

                                                <div class="fa fa-eye icon_password"></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <?php echo Form::label('password_confirmation', __('Confirm Password'), ['class' => 'form-label']); ?>

                                            <div class="password_box">
                                                <?php echo Form::password('password_confirmation', [
                                                    'class' => 'form-control password',
                                                    'placeholder' => 'Enter confirm password',
                                                ]); ?>

                                                <div class="fa fa-eye icon_Confirmpassword"></div>
                                            </div>
                                        </div>
                                        <div class="form-group  col-sm-6">
                                            <?php echo Form::label('address', __('Address'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                            <?php echo Form::textarea('address', null, ['class' => 'form-control', 'rows' => 2]); ?>

                                        </div>
                                    </div>

                                    
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Allowance" role="tabpanel"
                            aria-labelledby="nav-Allowance-tab">
                            <?php if($isProfileEdit == false): ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Company')); ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php echo csrf_field(); ?>
                                            

                                            <div class="form-group col-sm-6">

                                                <?php
                                                    $branchesGeofencingStatus = $branches
                                                        ->pluck('geofencing_status', 'id')
                                                        ->toArray();
                                                ?>

                                                <div class="col-lg-6 col-md-6 col-sm-6" id="geofencing_div">
                                                    <div class="form-check form-switch">
                                                        <label for="geofencing_status">
                                                            <h5 class=""><?php echo e(__('Enable Geofencing')); ?>

                                                            </h5>
                                                        </label>
                                                        <input type="checkbox" class="form-check-input"
                                                            id="geofencing_status" name="geofencing_status"
                                                            <?php echo e($employee->geofencing_status == 1 ? 'checked' : ''); ?>

                                                            <?php echo e(isset($branchesGeofencingStatus[$employee->branch_id]) && $branchesGeofencingStatus[$employee->branch_id] == 1 ? '' : 'disabled'); ?>>
                                                    </div>
                                                </div>
                                                <?php echo e(Form::label('branch_id', __('Select Branch'), ['class' => 'form-label'])); ?><span
                                                    class="text-danger pl-1">*</span>
                                                <?php echo e(Form::select('branch_id', $branches->pluck('name', 'id'), null, ['class' => 'form-control select2', 'id' => 'branch_id', 'placeholder' => 'Select Branch'])); ?>

                                            </div>
                                            <div class="form-group col-sm-6">
                                                <?php echo e(Form::label('department_id', __('Select Department'), ['class' => 'form-label'])); ?><span
                                                    class="text-danger pl-1">*</span>
                                                <?php echo e(Form::select('department_id', $departments, null, ['class' => 'form-control', 'id' => 'department_id'])); ?>

                                            </div>
                                            <div class="form-group col-sm-6">
                                                <?php echo e(Form::label('designation_id', __('Select Designation'), ['class' => 'form-label'])); ?><span
                                                    class="text-danger pl-1">*</span>


                                                <div class="form-icon-user">
                                                    <div class="designation_div">
                                                        <select class="form-control designation_id select2"
                                                            name="designation_id" id="choices-multiple"
                                                            placeholder="Select Designation">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <?php echo e(Form::label('designation_description', __('Designation Description'), ['class' => 'form-label'])); ?>

                                                <div class="form-icon-user">
                                                    <?php
                                                        $descriptionValue = '';
                                                        if (!empty($employee->designation_description)) {
                                                            $descriptionValue = $employee->designation_description;
                                                        } elseif (
                                                            !empty($employee->designation) &&
                                                            !empty($employee->designation->description)
                                                        ) {
                                                            $descriptionValue = $employee->designation->description;
                                                        }
                                                    ?>
                                                    <?php echo e(Form::textarea('designation_description', $descriptionValue, ['class' => 'form-control', 'id' => 'designation_description', 'rows' => '3', 'placeholder' => __('Designation Description')])); ?>

                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <?php echo e(Form::label('employe_status', __('Employee Status'), ['class' => 'form-label'])); ?><span
                                                    class="text-danger pl-1">*</span>
                                                <div class="form-icon-user">
                                                    <select name="employe_status" id="employe_status"
                                                        class="form-control select2">

                                                        <option <?php if($employee->employe_status == 'Active'): ?> selected <?php else: ?> <?php endif; ?>
                                                            value="Active">Active</option>
                                                        <option <?php if($employee->employe_status == 'Terminated'): ?> selected <?php else: ?> <?php endif; ?>
                                                            value="Terminated">Terminated</option>
                                                        
                                                        <option <?php if($employee->employe_status == 'Resigned'): ?> selected <?php else: ?> <?php endif; ?>
                                                            value="Resigned">Resigned</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <?php echo e(Form::label('employe_types', __('Employee Types'), ['class' => 'form-label'])); ?><span
                                                    class="text-danger pl-1">*</span>

                                                <div class="form-icon-user">
                                                    <select name="employe_types" id="employe_types"
                                                        class="form-control select2">
                                                        <option <?php if($employee->employe_types == 'Permanent'): ?> selected <?php else: ?> <?php endif; ?>
                                                            value="Permanent">Permanent</option>
                                                        <option <?php if($employee->employe_types == 'Temporary'): ?> selected <?php else: ?> <?php endif; ?>
                                                            value="Temporary">Temporary</option>
                                                        <option <?php if($employee->employe_types == 'On Contract'): ?> selected <?php else: ?> <?php endif; ?>
                                                            value="On Contract">On Contract</option>
                                                        <option <?php if($employee->employe_types == 'Trainee'): ?> selected <?php else: ?> <?php endif; ?>
                                                            value="Trainee">Trainee</option>


                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <?php echo Form::label('Experience', __('Experience'), ['class' => 'form-label']); ?>

                                                <?php echo Form::text('employee_experience', $employee->employee_experience, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter Experience',
                                                ]); ?>

                                            </div>


                                            <div class="form-group col-sm-6">
                                                <?php echo Form::label('Job_description', __('Job Description'), ['class' => 'form-label']); ?>


                                                <?php echo Form::textarea('Job_description', $employee->Job_description, [
                                                    'class' => 'form-control',
                                                    'rows' => 2,
                                                    'placeholder' => 'Enter job description',
                                                ]); ?>

                                            </div>


                                            <div class="form-group col-sm-6">
                                                <?php echo Form::label('company_doj', 'Company date of joining', ['class' => 'form-label']); ?> <span class="text-danger pl-1">*</span>
                                                <input type="date" name="company_doj"
                                                    value="<?php echo e($employee->getRawOriginal('company_doj')); ?>"
                                                    class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <?php echo Form::label('date_of_exit', __('Date on Exit'), ['class' => 'form-label']); ?>

                                                <input type="date" name="date_of_exit"
                                                    value="<?php echo e($employee->getRawOriginal('date_of_exit')); ?>"
                                                    class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <?php echo Form::label('company_doj', 'Probation Period Date', ['class' => 'form-label']); ?> <span class="text-danger pl-1">*</span>
                                                <input type="date"
                                                    value="<?php echo e($employee->getRawOriginal('probation_period_date')); ?>"
                                                    name="probation_period_date" class='form-control'>
                                                <span>Probation Period Months : <?php echo e($probation_period_months); ?></span>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <?php echo Form::label('assign_shifts', 'Assign Shift', ['class' => 'form-label']); ?>

                                                <select name="assign_shifts[]" class="select2" id="assign_shifts">
                                                    <option value="">Select shift</option>
                                                    <?php $__currentLoopData = $workingShifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($shift->id); ?>"
                                                            <?php echo e(in_array($shift->id, $assignedWorkingShifts) ? 'selected' : ''); ?>>
                                                            <?php echo e($shift->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
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
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Employee Status')); ?>

                                                                :</strong>
                                                            <span><?php echo e($employee->employe_status); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold"><?php echo e(__('Employee Types')); ?>

                                                                :</strong>
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
                                                            <strong class="font-bold"><?php echo e(__('Date Of Joining')); ?>

                                                                :</strong>
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
                                                            <strong class="font-bold"><?php echo e(__('Probation Date')); ?>

                                                                :</strong>
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
                                                            <strong class="font-bold"><?php echo e(__('Job Description')); ?>

                                                                :</strong>
                                                            <span><?php echo e($employee->Job_description ?? ''); ?></span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>


                        </div>

                        <div class="tab-pane fade" id="nav-salary-and-deductions" role="tabpanel"
                            aria-labelledby="nav-salary-and-deductions-tab">

                            <?php if($isProfileEdit == false): ?>


                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Salary & Deductions')); ?></h5>
                                    </div>
                                    <div class="card-body employee-detail-create-body">

                                        
                                        <div class="row">

                                            <div class="form-group col-md-6">
                                                <?php echo Form::label('play_slab_id', __('Pay Slab'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <select name="play_slab_id" id="play_slab_id"
                                                    class="form-control select2">
                                                    <option value="">Select Pay Slab</option>
                                                    <?php $__currentLoopData = $PayslipType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $playslab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option <?php if($employee->play_slab_id == $playslab->id): ?> selected <?php endif; ?>
                                                            value="<?php echo e($playslab->id); ?>"><?php echo e($playslab->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <?php echo Form::label('office_staff', __('This facility is for Field'), ['class' => 'form-label']); ?>

                                                <select name="office_staff" id="office_staff" class="form-control">
                                                    <option value="">Select visit option</option>
                                                    <option <?php if($employee->office_staff == 1): ?> selected <?php endif; ?>
                                                        value="1">
                                                        Office Staff</option>
                                                    <option <?php if($employee->office_staff == 2): ?> selected <?php endif; ?>
                                                        value="2">
                                                        Field Staff</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6 select_grade_type d-none">
                                                <?php echo Form::label('grade_type_id', __('Grade Type'), ['class' => 'form-label']); ?>

                                                <select name="grade_type_id" id="grade_type_id" class="form-control">
                                                    <option value="">Select Grade</option>
                                                    <?php $__currentLoopData = $gradeTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gradetype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option <?php if($gradetype->id == $employee->grade_type_id): ?> selected <?php endif; ?>
                                                            value="<?php echo e($gradetype->id); ?>"><?php echo e($gradetype->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <?php echo Form::label('ctc_annual', __('CTC Annual'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <?php echo Form::number('ctc_annual', old('ctc_annual'), [
                                                    'class' => 'form-control salaryAmountValidation',
                                                    'min' => '0',
                                                    'placeholder' => 'Enter Employee Annual CTC',
                                                    'id' => 'ctc_annual',
                                                ]); ?>

                                            </div>

                                            <div class="form-group col-md-3">
                                                <?php echo Form::label('ctc_monthely', __('CTC Monthly'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <?php echo Form::number(
                                                    'ctc_monthely',
                                                    old('ctc_monthely', $employee->ctc_annual > 0 ? $employee->ctc_annual / 12 : 0),
                                                    [
                                                        'class' => 'form-control salaryAmountValidation',
                                                        'min' => '0',
                                                        'placeholder' => 'Employee Monthly CTC',
                                                        'id' => 'ctc_monthely',
                                                        'readonly',
                                                    ],
                                                ); ?>

                                            </div>



                                            <div class="form-group col-md-3">
                                                <?php echo Form::label('salary', __('Salary'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <?php echo Form::number('salary', old('salary'), [
                                                    'class' => 'form-control salaryAmountValidation',
                                                    'min' => '0',
                                                    'placeholder' => 'Enter Employee Salary',
                                                    'id' => 'salary',
                                                ]); ?>

                                            </div>

                                            <div class="form-group col-md-3">
                                                <?php echo Form::label('ctc_percentage', __('Basic(%)'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                                <?php echo Form::text('ctc_percentage', numberFormat($employee->ctc_percentage), [
                                                    'class' => 'form-control salaryAmountValidation ctcPercentageValidator',
                                                    'min' => '0',
                                                    'placeholder' => 'Basic(%)',
                                                    'id' => 'ctc_percentage',
                                                    'readonly',
                                                    'min' => 1,
                                                    'max' => 100,
                                                ]); ?>

                                            </div>

                                        </div>

                                        



                                        


                                        

                                        
                                        <hr>
                                        <div class="row">

                                            <div class="form-group col-md-12">
                                                <div class="form-check form-switch">
                                                    <label for="statutory_bonus_status">
                                                        <h5 class=""><?php echo e(__('Statutory Bonus')); ?>

                                                        </h5>
                                                    </label>
                                                    <input type="checkbox" class="form-check-input"
                                                        id="statutory_bonus_status" name="statutory_bonus_status"
                                                        <?php echo e($employee->statutory_bonus_status == 1 ? 'checked' : ''); ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="statutory_bonus_div"
                                            style="display: <?php echo e($employee->statutory_bonus_status == 1 ? '' : 'none'); ?>;">
                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                <?php echo e(Form::label('statutory_bonus', __('Statutory Bonus'), ['class' => 'col-form-label'])); ?>

                                                <?php echo e(Form::text('statutory_bonus', old('statutory_bonus', $employee->statutory_bonus), ['id' => 'statutory_bonus', 'class' => 'form-control salaryAmountValidation', 'placeholder' => 'Enter Amount'])); ?>

                                            </div>
                                        </div>
                                        

                                        
                                        <?php if($allowancesList->count() >= 1): ?>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <h5 class=""><?php echo e(__('Allowances')); ?></h5>
                                                </div>
                                            </div>

                                            <div class="row" id="allowances_div">

                                                <?php $__currentLoopData = $allowancesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allowancesRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $employeeEarningsDataRow = $employeeEarningsData
                                                            ->where('earning_id', $allowancesRow->id)
                                                            ->where('earning_type', 0)
                                                            ->first();

                                                        $earningChecked = '';
                                                        $earningDisplay = 'style="display: none;"';
                                                        $earningAmount = $allowancesRow->amount;
                                                        if ($employeeEarningsDataRow != null) {
                                                            $earningAmount = $employeeEarningsDataRow->amount;
                                                            $earningChecked =
                                                                $employeeEarningsDataRow->status == 1 ? 'checked' : '';
                                                            $earningDisplay =
                                                                $employeeEarningsDataRow->status == 1
                                                                    ? ''
                                                                    : 'style="display: none;"';
                                                        }
                                                    ?>
                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-switch">
                                                            <label for="allowances_status_<?php echo e($allowancesRow->id); ?>">
                                                                <h5 class=""><?php echo e($allowancesRow->name); ?></h5>
                                                            </label>
                                                            <input type="checkbox" class="form-check-input hideShowDiv"
                                                                id="allowances_status_<?php echo e($allowancesRow->id); ?>"
                                                                name="earning_id[]" value="<?php echo e($allowancesRow->id); ?>"
                                                                divId="allowancesDiv_<?php echo e($allowancesRow->id); ?>"
                                                                <?php echo e($earningChecked); ?>>
                                                            <input type="hidden"
                                                                name="earning_type[<?php echo e($allowancesRow->id); ?>]"
                                                                value="0">
                                                            <input type="hidden"
                                                                name="earning_amount_type[<?php echo e($allowancesRow->id); ?>]"
                                                                value="<?php echo e($allowancesRow->type); ?>">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-12 form-group"
                                                            id="allowancesDiv_<?php echo e($allowancesRow->id); ?>"
                                                            <?php echo $earningDisplay; ?>>
                                                            <?php echo e(Form::label('allowances_amount_' . $allowancesRow->id, __('Amount'), ['class' => 'col-form-label'])); ?>

                                                            <?php if($allowancesRow->type == 'percentage'): ?>
                                                                <small>(%)</small>
                                                            <?php endif; ?>
                                                            <?php echo e(Form::text('earning_amount[' . $allowancesRow->id . ']', $earningAmount, ['id' => 'allowances_amount_' . $allowancesRow->id, 'class' => 'form-control numberValidation', 'placeholder' => 'Enter Amount'])); ?>

                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <hr>
                                        <?php endif; ?>
                                        

                                        
                                        <?php if($commissionList->count() >= 1): ?>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <h5 class=""><?php echo e(__('Commission')); ?></h5>
                                                </div>
                                            </div>

                                            <div class="row" id="allowances_div">

                                                <?php $__currentLoopData = $commissionList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commissionRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $employeeEarningsDataRow = $employeeEarningsData
                                                            ->where('earning_id', $commissionRow->id)
                                                            ->where('earning_type', 1)
                                                            ->first();

                                                        $earningChecked = '';
                                                        $earningDisplay = 'style="display: none;"';
                                                        $earningAmount = $commissionRow->amount;
                                                        if ($employeeEarningsDataRow != null) {
                                                            $earningAmount = $employeeEarningsDataRow->amount;
                                                            $earningChecked =
                                                                $employeeEarningsDataRow->status == 1 ? 'checked' : '';
                                                            $earningDisplay =
                                                                $employeeEarningsDataRow->status == 1
                                                                    ? ''
                                                                    : 'style="display: none;"';
                                                        }
                                                    ?>
                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-switch">
                                                            <label for="commission_status_<?php echo e($commissionRow->id); ?>">
                                                                <h5 class=""><?php echo e($commissionRow->name); ?></h5>
                                                            </label>

                                                            <input type="checkbox" class="form-check-input hideShowDiv"
                                                                id="commission_status_<?php echo e($commissionRow->id); ?>"
                                                                name="earning_id[]" value="<?php echo e($commissionRow->id); ?>"
                                                                divId="commissionDiv_<?php echo e($commissionRow->id); ?>"
                                                                <?php echo e($earningChecked); ?>>
                                                            <input type="hidden"
                                                                name="earning_type[<?php echo e($commissionRow->id); ?>]"
                                                                value="1">
                                                            <input type="hidden"
                                                                name="earning_amount_type[<?php echo e($commissionRow->id); ?>]"
                                                                value="<?php echo e($commissionRow->type); ?>">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-12 form-group"
                                                            id="commissionDiv_<?php echo e($commissionRow->id); ?>"
                                                            <?php echo $earningDisplay; ?>>
                                                            <?php echo e(Form::label('commission_amount_' . $commissionRow->id, __('Amount'), ['class' => 'col-form-label'])); ?>

                                                            <?php if($commissionRow->type == 'percentage'): ?>
                                                                <small>(%)</small>
                                                            <?php endif; ?>
                                                            <?php echo e(Form::text('earning_amount[' . $commissionRow->id . ']', $earningAmount, ['id' => 'commission_amount_' . $commissionRow->id, 'class' => 'form-control numberValidation', 'placeholder' => 'Enter Amount'])); ?>

                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <hr>
                                        <?php endif; ?>
                                        


                                        

                                        <?php if($otherPaymentList->count() >= 1): ?>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <h5 class=""><?php echo e(__('Other Payments')); ?></h5>
                                                </div>
                                            </div>

                                            <div class="row" id="allowances_div">

                                                <?php $__currentLoopData = $otherPaymentList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $otherPaymentRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $employeeEarningsDataRow = $employeeEarningsData
                                                            ->where('earning_id', $otherPaymentRow->id)
                                                            ->where('earning_type', 2)
                                                            ->first();

                                                        $earningChecked = '';
                                                        $earningDisplay = 'style="display: none;"';
                                                        $earningAmount = $otherPaymentRow->amount;
                                                        if ($employeeEarningsDataRow != null) {
                                                            $earningAmount = $employeeEarningsDataRow->amount;
                                                            $earningChecked =
                                                                $employeeEarningsDataRow->status == 1 ? 'checked' : '';
                                                            $earningDisplay =
                                                                $employeeEarningsDataRow->status == 1
                                                                    ? ''
                                                                    : 'style="display: none;"';
                                                        }
                                                    ?>
                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-switch">
                                                            <label for="other_payment_status_<?php echo e($otherPaymentRow->id); ?>">
                                                                <h5 class=""><?php echo e($otherPaymentRow->name); ?></h5>
                                                            </label>
                                                            <input type="checkbox" class="form-check-input hideShowDiv"
                                                                id="other_payment_status_<?php echo e($otherPaymentRow->id); ?>"
                                                                name="earning_id[]" value="<?php echo e($otherPaymentRow->id); ?>"
                                                                divId="other_paymentDiv_<?php echo e($otherPaymentRow->id); ?>"
                                                                <?php echo e($earningChecked); ?>>
                                                            <input type="hidden"
                                                                name="earning_type[<?php echo e($otherPaymentRow->id); ?>]"
                                                                value="2">

                                                            <input type="hidden"
                                                                name="earning_amount_type[<?php echo e($otherPaymentRow->id); ?>]"
                                                                value="<?php echo e($otherPaymentRow->type); ?>">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-12 form-group"
                                                            id="other_paymentDiv_<?php echo e($otherPaymentRow->id); ?>"
                                                            <?php echo $earningDisplay; ?>>
                                                            <?php echo e(Form::label('other_payment_amount_' . $otherPaymentRow->id, __('Amount'), ['class' => 'col-form-label'])); ?>

                                                            <?php if($otherPaymentRow->type == 'percentage'): ?>
                                                                <small>(%)</small>
                                                            <?php endif; ?>
                                                            <?php echo e(Form::text('earning_amount[' . $otherPaymentRow->id . ']', $earningAmount, ['id' => 'other_payment_amount_' . $otherPaymentRow->id, 'class' => 'form-control numberValidation', 'placeholder' => 'Enter Amount'])); ?>

                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <hr>
                                        <?php endif; ?>
                                        

                                        


                                        

                                        

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <h5 class=""><?php echo e(__('Deductions')); ?></h5>
                                            </div>
                                        </div>

                                        <?php if($deductionList->count() >= 1): ?>
                                            <div class="row" id="allowances_div">

                                                <?php $__currentLoopData = $deductionList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deductionRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $employeeEarningsDataRow = $employeeDeductionData
                                                            ->where('deduction_id', $deductionRow->id)
                                                            ->where('deduction_type', 0)
                                                            ->first();

                                                        $earningChecked = '';
                                                        $earningDisplay = 'style="display: none;"';
                                                        $earningAmount = $deductionRow->amount;
                                                        if ($employeeEarningsDataRow != null) {
                                                            $earningAmount = $employeeEarningsDataRow->amount;
                                                            $earningChecked =
                                                                $employeeEarningsDataRow->status == 1 ? 'checked' : '';
                                                            $earningDisplay =
                                                                $employeeEarningsDataRow->status == 1
                                                                    ? ''
                                                                    : 'style="display: none;"';
                                                        }
                                                    ?>


                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-switch">
                                                            <label for="deduction_status_<?php echo e($deductionRow->id); ?>">
                                                                <h5 class=""><?php echo e($deductionRow->name); ?></h5>
                                                            </label>
                                                            <input type="checkbox" class="form-check-input hideShowDiv"
                                                                id="deduction_status_<?php echo e($deductionRow->id); ?>"
                                                                name="deduction_id[]" value="<?php echo e($deductionRow->id); ?>"
                                                                divId="other_paymentDiv_<?php echo e($deductionRow->id); ?>"
                                                                <?php echo e($earningChecked); ?>>
                                                            <input type="hidden"
                                                                name="deduction_type[<?php echo e($deductionRow->id); ?>]"
                                                                value="0">

                                                            <input type="hidden"
                                                                name="deduction_amount_type[<?php echo e($deductionRow->id); ?>]"
                                                                value="<?php echo e($deductionRow->type); ?>">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-12 form-group"
                                                            id="other_paymentDiv_<?php echo e($deductionRow->id); ?>"
                                                            <?php echo $earningDisplay; ?>>
                                                            <?php echo e(Form::label('deduction_amount_' . $deductionRow->id, __('Amount'), ['class' => 'col-form-label'])); ?>

                                                            <?php if($deductionRow->type == 'percentage'): ?>
                                                                <small>(%)</small>
                                                            <?php endif; ?>
                                                            <?php echo e(Form::text('deduction_amount[' . $deductionRow->id . ']', $earningAmount, ['id' => 'deduction_amount_' . $deductionRow->id, 'class' => 'form-control numberValidation', 'placeholder' => 'Enter Amount'])); ?>

                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <?php if($companyEpf != null): ?>
                                            <hr>
                                            <div class="row">

                                                <div class="form-group col-md-12">
                                                    <div class="form-check form-switch">
                                                        <label for="epf_status">
                                                            <h5 class=""><?php echo e(__('EPF | Employee Provident Fund')); ?>

                                                            </h5>
                                                        </label>
                                                        <input type="checkbox" class="form-check-input" id="epf_status"
                                                            name="epf_status"
                                                            <?php echo e($employee->epf_status == 1 ? 'checked' : ''); ?>>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="epf_div"
                                                style="display: <?php echo e($employee->epf_status == 0 ? 'none' : ''); ?>;">
                                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                    <?php echo e(Form::label('epf_number', __('PF Account No / Member Id'), ['class' => 'col-form-label'])); ?>

                                                    <?php echo e(Form::text('epf_number', old('epf_number', $employee->epf_number), ['id' => 'epf_number', 'class' => 'form-control', 'placeholder' => 'PF Account No / Member Id'])); ?>

                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                    <?php echo e(Form::label('uan_no', __('UAN Number'), ['class' => 'col-form-label'])); ?>

                                                    <?php echo e(Form::text('uan_no', old('uan_no', $employee->uan_no), ['id' => 'uan_no', 'class' => 'form-control', 'placeholder' => 'UAN Number'])); ?>

                                                </div>

                                                <?php if($companyEpf->override_employee_pf == 1): ?>
                                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                        <?php echo e(Form::label('employee_contribution_rate', __('Employee Contribution Rate (%)'), ['class' => 'col-form-label'])); ?>

                                                        <?php echo e(Form::text('employee_contribution_rate', old('employee_contribution_rate', $employee->epf_employee_contribution_rate), ['id' => 'employee_contribution_rate', 'class' => 'form-control salaryAmountValidation text-right', 'placeholder' => 'Employee Contribution Rate (%)', 'max' => '100'])); ?>

                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                        <?php echo e(Form::label('employer_contribution_rate', __('Employer Contribution Rate (%)'), ['class' => 'col-form-label'])); ?>

                                                        <?php echo e(Form::text('employer_contribution_rate', old('employer_contribution_rate', $employee->epf_employer_contribution_rate), ['id' => 'employer_contribution_rate', 'class' => 'form-control salaryAmountValidation text-right', 'placeholder' => 'Employer Contribution Rate (%)', 'max' => '100'])); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <hr>
                                        <?php endif; ?>
                                        

                                        
                                        <?php if($companyEsi != null): ?>
                                            <hr>
                                            <div class="row">

                                                <div class="form-group col-md-12">
                                                    <div class="form-check form-switch">
                                                        <label for="esi_status">
                                                            <h5 class="">
                                                                <?php echo e(__('ESI | Employees State Insurance')); ?>

                                                            </h5>
                                                            Note: ESI deductions will be made only if the employee’s monthly
                                                            salary is less than or equal to ₹21,000. If the employee gets a
                                                            salary revision which increases their monthly salary above
                                                            ₹21,000,
                                                            they would have to continue making ESI contributions till the
                                                            end of
                                                            the contribution period in which the salary was revised
                                                            (April-September or October-March).
                                                        </label>
                                                        <input type="checkbox" class="form-check-input" id="esi_status"
                                                            name="esi_status"
                                                            <?php echo e($employee->esi_status == 1 ? 'checked' : ''); ?>>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="esi_div"
                                                style="display: <?php echo e($employee->esi_status == 0 ? 'none' : ''); ?>;">
                                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                    <?php echo e(Form::label('esi_number', __('Esi Account No'), ['class' => 'col-form-label'])); ?>

                                                    <?php echo e(Form::text('esi_number', old('esi_number', $employee->esi_number), ['id' => 'esi_number', 'class' => 'form-control', 'placeholder' => 'Esi Account No'])); ?>

                                                </div>
                                            </div>
                                            <hr>
                                        <?php endif; ?>
                                        

                                        
                                        <?php if($professional_tax_status == 1): ?>
                                            <hr>
                                            <div class="row">

                                                <div class="form-group col-md-12">
                                                    <div class="form-check form-switch">
                                                        <label for="ept_status">
                                                            <h5 class="">
                                                                <?php echo e(__('EPT | Employees Professional Tax')); ?>

                                                            </h5>
                                                        </label>
                                                        <input type="checkbox" class="form-check-input" id="ept_status"
                                                            name="ept_status"
                                                            <?php echo e($employee->ept_status == 1 ? 'checked' : ''); ?>>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="ept_div"
                                                style="display: <?php echo e($employee->ept_status == 0 ? 'none' : ''); ?>;">

                                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                    <?php echo e(Form::label('ept_number', __('Ept Account No'), ['class' => 'col-form-label'])); ?>

                                                    <?php echo e(Form::text('ept_number', old('ept_number', $employee->ept_number), ['id' => 'ept_number', 'class' => 'form-control', 'placeholder' => 'Ept Account No'])); ?>

                                                </div>
                                            </div>
                                            <hr>
                                        <?php endif; ?>
                                        


                                        
                                        <hr>
                                        <div class="row">

                                            <div class="form-group col-md-12">
                                                <div class="form-check form-switch">
                                                    <label for="tax_deducted_at_source_status">
                                                        <h5 class=""><?php echo e(__('TDS | Tax Deducted at Source')); ?>

                                                        </h5>
                                                    </label>
                                                    <input type="checkbox" class="form-check-input"
                                                        id="tax_deducted_at_source_status"
                                                        name="tax_deducted_at_source_status"
                                                        <?php echo e($employee->tax_deducted_at_source_status == 1 ? 'checked' : ''); ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="tax_deducted_at_source_div"
                                            style="display: <?php echo e($employee->tax_deducted_at_source_status == 1 ? '' : 'none'); ?>;">
                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                <?php echo e(Form::label('tax_deducted_at_source', __('Tax Deducted at Source'), ['class' => 'col-form-label'])); ?>

                                                <?php echo e(Form::text('tax_deducted_at_source', old('tax_deducted_at_source', $employee->tax_deducted_at_source), ['id' => 'tax_deducted_at_source', 'class' => 'form-control salaryAmountValidation', 'placeholder' => 'Enter Amount'])); ?>

                                            </div>
                                        </div>
                                        

                                        

                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card-body employee-detail-create-body">

                                    
                                    <?php if(empty($payslipDetail) || $payslipDetail == ''): ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="info text-sm text-center">
                                                    <br>
                                                    <br>
                                                    <h5><?php echo e(__('Setup Salary Structure')); ?></h5>

                                                    <?php
                                                    
                                                    //   if (Auth::user()->type != 'employee' && Auth::user()->hasPermissionTo('Edit Employee')) {
                                                    $editLink = '<div class="action-btn bg-info ms-2"><a href="' . route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) . '" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="' . __('Edit') . '"><i class="ti ti-pencil text-white"></i></a></div>';
                                                    
                                                    echo $editLink;
                                                    //   }
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

                                                            <tr>
                                                                <td class="text-left">Basic
                                                                    (<?php echo e($payslipDetail->ctcPercentage); ?>% of CTC)</td>
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
                                                                            <td class="text-left"><?php echo e($rowTitle); ?>

                                                                            </td>
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

                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="nav-Experience" role="tabpanel"
                            aria-labelledby="nav-Experience-tab">


                            
                            <?php if(!empty($EXPTableData[0])): ?>
                                <?php $__currentLoopData = $EXPTableData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col col-6">
                                                        <h5><?php echo e(__('Experience')); ?></h5>
                                                    </div>
                                                    <div class="col col-6 text-end">

                                                        <?php if($key == 0): ?>
                                                            <div class="btn btn-success" id='add_sub_form'>
                                                                <?php echo e('+'); ?>

                                                            </div>
                                                        <?php else: ?>
                                                            <a href="<?php echo e(route('exp_table_remove', $exp->id)); ?>"
                                                                class="btn btn-danger"
                                                                id='add_sub_form'><?php echo e('remove'); ?></a>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>


                                            </div>
                                            <div class="card-body employee-detail-create-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <?php echo Form::label('previous_company_name', __('Previous Company'), ['class' => 'form-label']); ?>

                                                        <?php echo Form::text('previous_company_name[]', $exp->previous_company_name, [
                                                            'class' => 'form-control',
                                                        
                                                            'placeholder' => 'Enter previous company',
                                                        ]); ?>


                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <?php echo Form::label('previous_job_title', __('Job Title'), ['class' => 'form-label']); ?>

                                                        <?php echo Form::text('previous_job_title[]', $exp->previous_job_title, [
                                                            'class' => 'form-control',
                                                        
                                                            'placeholder' => 'Enter job title',
                                                        ]); ?>

                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <?php echo Form::label('previous_from_date', __('From Date'), ['class' => 'form-label']); ?>

                                                        <input type="date" name="previous_from_date[]"
                                                            value="<?php echo e($exp->getRawOriginal('previous_from_date')); ?>"
                                                            class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <?php echo Form::label('previous_to_date', __('To Date'), ['class' => 'form-label']); ?>

                                                        <input type="date" name="previous_to_date[]"
                                                            value="<?php echo e($exp->getRawOriginal('previous_to_date')); ?>"
                                                            class='form-control' max="<?php echo date('Y-m-d'); ?>">

                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <?php echo e(Form::label('previous_company_designation_id', __('Previous Company designation'), ['class' => 'form-label'])); ?>

                                                        <input type="text" class='form-control'
                                                            name='previous_company_designation_id[]'
                                                            placeholder='Enter previous company designation'
                                                            value='<?php echo e($exp->previous_company_designation_id); ?>'>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <?php echo Form::label('experiance_Job_description', __('Experience Job Description'), ['class' => 'form-label']); ?>


                                                        <?php echo Form::textarea('experiance_Job_description[]', $exp->experiance_Job_description, [
                                                            'class' => 'form-control',
                                                        
                                                            'rows' => 2,
                                                            'placeholder' => 'Enter Experience job description',
                                                        ]); ?>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row d-flex align-items-center">
                                                <div class="col col-6">
                                                    <h5><?php echo e(__('Experience')); ?></h5>
                                                </div>
                                                <div class="col col-6 text-end">
                                                    <div class="btn btn-success" id='add_sub_form'><?php echo e('+'); ?>

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="card-body employee-detail-create-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('previous_company_name', __('Previous Company'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('previous_company_name[]', null, [
                                                        'class' => 'form-control',
                                                    
                                                        'placeholder' => 'Enter previous company',
                                                    ]); ?>


                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('previous_job_title', __('Job Title'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('previous_job_title[]', null, [
                                                        'class' => 'form-control',
                                                    
                                                        'placeholder' => 'Enter job title',
                                                    ]); ?>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('previous_from_date', __('From Date'), ['class' => 'form-label']); ?>

                                                    <input type="date" name="previous_from_date[]"
                                                        class='form-control' max="<?php echo date('Y-m-d'); ?>">

                                                </div>

                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('previous_to_date', __('To Date'), ['class' => 'form-label']); ?>

                                                    <input type="date" name="previous_to_date[]" class='form-control'
                                                        max="<?php echo date('Y-m-d'); ?>">

                                                </div>

                                                <div class="form-group col-md-12">
                                                    <?php echo e(Form::label('previous_company_designation_id', __('Previous Company designation'), ['class' => 'form-label'])); ?>

                                                    <input type="text" class='form-control'
                                                        name='previous_company_designation_id'
                                                        id='previous_company_designation_id'
                                                        placeholder='Enter previous company designation'>

                                                </div>

                                                <div class="form-group col-md-12">
                                                    <?php echo Form::label('experiance_Job_description', __('Experience Job Description'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::textarea('experiance_Job_description[]', null, [
                                                        'class' => 'form-control',
                                                        'rows' => 2,
                                                        'placeholder' => 'Enter Experience job description',
                                                    ]); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div id='append_sub_form'></div>



                        </div>

                        <div class="tab-pane fade" id="nav-Education" role="tabpanel"
                            aria-labelledby="nav-Education-tab">

                            

                            <?php if(count($EducationData) > 0): ?>
                                <?php $__currentLoopData = $EducationData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-12 ">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col col-6">
                                                        <h5><?php echo e(__('Education')); ?></h5>
                                                    </div>
                                                    <div class="col col-6 text-end">
                                                        <?php if($key == 0): ?>
                                                            <p class="btn btn-success"
                                                                id='append_sub_form_eduction_table'>
                                                                <?php echo e('+'); ?>

                                                            </p>
                                                        <?php else: ?>
                                                            <a href="<?php echo e(route('eduction_table_remove', $edu->id)); ?>"
                                                                class="btn btn-danger"
                                                                id='add_sub_form'><?php echo e('remove'); ?></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="card-body employee-detail-create-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <?php echo Form::label('institute_name', __('Institute Name'), ['class' => 'form-label']); ?>

                                                        <?php echo Form::text('institute_name[]', $edu->institute_name, [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter institute name',
                                                        ]); ?>


                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <?php echo Form::label('education', __('Education'), ['class' => 'form-label']); ?>

                                                        <?php echo Form::text('education[]', $edu->education, [
                                                            'class' => 'form-control',
                                                        
                                                            'placeholder' => 'Enter education',
                                                        ]); ?>


                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <?php echo Form::label('specialization', __('Specialization'), ['class' => 'form-label']); ?>

                                                        <?php echo Form::text('specialization[]', $edu->specialization, [
                                                            'class' => 'form-control',
                                                        
                                                            'placeholder' => 'Enter specialization',
                                                        ]); ?>

                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <?php echo Form::label('date_of_completion', __('Date of birth'), ['class' => 'form-label']); ?>

                                                        <input type="date" name="date_of_completion[]"
                                                            id="date_of_completion"
                                                            value="<?php echo e($edu->getRawOriginal('date_of_completion')); ?>"
                                                            class='form-control'>

                                                    </div>



                                                    <div class="form-group col-md-12">
                                                        <?php echo Form::label('notes', __('Notes'), ['class' => 'form-label']); ?>


                                                        <?php echo Form::textarea('notes[]', $edu->notes, [
                                                            'class' => 'form-control',
                                                            'rows' => 2,
                                                            'placeholder' => 'Enter notes',
                                                        ]); ?>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row d-flex align-items-center">
                                                <div class="col col-6">
                                                    <h5><?php echo e(__('Education')); ?></h5>

                                                </div>
                                                <div class="col col-6 text-end">
                                                    <?php $i = 0; ?>
                                                    <?php if($i == 0): ?>
                                                        <p class="btn btn-success" id='append_sub_form_eduction_table'>
                                                            <?php echo e('+'); ?>

                                                        </p>
                                                    <?php else: ?>
                                                        <a href="<?php echo e(route('eduction_table_remove', null)); ?>"
                                                            class="btn btn-danger"
                                                            id='add_sub_form'><?php echo e('remove'); ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-body employee-detail-create-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('institute_name', __('Institute Name'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('institute_name[]', null, ['class' => 'form-control', 'placeholder' => 'Enter institute name']); ?>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('education', __('Education'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('education[]', null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Enter education',
                                                    ]); ?>


                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('specialization', __('Specialization'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('specialization[]', null, [
                                                        'class' => 'form-control',
                                                    
                                                        'placeholder' => 'Enter specialization',
                                                    ]); ?>

                                                </div>

                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('date_of_completion', __('Date of birth'), [
                                                        'class' => 'form-label',
                                                        'id' => 'date_of_completion',
                                                    ]); ?>

                                                    <input type="date" name="date_of_completion[]"
                                                        id="date_of_completion" class='form-control'
                                                        max="<?php echo date('Y-m-d'); ?>">

                                                </div>



                                                <div class="form-group col-md-12">
                                                    <?php echo Form::label('notes', __('Notes'), ['class' => 'form-label']); ?>


                                                    <?php echo Form::textarea('notes[]', null, [
                                                        'class' => 'form-control',
                                                        'rows' => 2,
                                                        'placeholder' => 'Enter notes',
                                                    ]); ?>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div id='append_sub_form_eduction_table_add_form'></div>

                        </div>

                        <div class="tab-pane fade" id="nav-Dependent" role="tabpanel"
                            aria-labelledby="nav-Dependent-tab">


                            

                            <?php if(!empty($DependentData[0])): ?>
                                <?php $__currentLoopData = $DependentData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col col-6">
                                                        <h5><?php echo e(__('Dependent')); ?></h5>
                                                    </div>
                                                    <div class="col col-6">
                                                        <div class="text-end">
                                                            <?php if($key == 0): ?>
                                                                <div class="btn btn-success"
                                                                    id='Dependent_sub_form_eduction_table'>
                                                                    <?php echo e('+'); ?>

                                                                </div>
                                                            <?php else: ?>
                                                                <a href="<?php echo e(route('Dependent_table_remove', $exp->id)); ?>"
                                                                    class="btn btn-danger"
                                                                    id='add_sub_form'><?php echo e('remove'); ?></a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body employee-detail-create-body">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <?php echo Form::label('dependent_name', __('Name'), ['class' => 'form-label']); ?>

                                                        <?php echo Form::text('dependent_name[]', $exp->dependent_name, [
                                                            'class' => 'form-control dependent_name',
                                                            'placeholder' => 'Enter dependent name',
                                                        ]); ?>


                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <?php echo e(Form::label('dependent_relation', __('Relation Types '), ['class' => 'form-label'])); ?><span
                                                            class="text-danger pl-1 relation_type_label"
                                                            style="display: <?php echo e(empty($exp->dependent_name) ? 'none' : ''); ?>">*</span>
                                                        <div class="form-icon-user">
                                                            <select name="dependent_relation[]" id="dependent_relation"
                                                                class="form-control select2">

                                                                <option
                                                                    <?php if($exp->dependent_relation == 'Permanent'): ?> selected <?php else: ?> <?php endif; ?>
                                                                    value="Father">Father</option>
                                                                <option
                                                                    <?php if($exp->dependent_relation == 'Mother'): ?> selected <?php else: ?> <?php endif; ?>
                                                                    value="Mother">Mother</option>
                                                                <option
                                                                    <?php if($exp->dependent_relation == 'Brother'): ?> selected <?php else: ?> <?php endif; ?>
                                                                    value="Brother">Brother</option>
                                                                <option
                                                                    <?php if($exp->dependent_relation == 'Sister'): ?> selected <?php else: ?> <?php endif; ?>
                                                                    value="Sister">Sister</option>
                                                                <option
                                                                    <?php if($exp->dependent_relation == 'Husband'): ?> selected <?php else: ?> <?php endif; ?>
                                                                    value="Husband">Husband</option>
                                                                <option
                                                                    <?php if($exp->dependent_relation == 'Wife'): ?> selected <?php else: ?> <?php endif; ?>
                                                                    value="Wife">Wife</option>
                                                                <option
                                                                    <?php if($exp->dependent_relation == 'Child'): ?> selected <?php else: ?> <?php endif; ?>
                                                                    value="Child">Child</option>

                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group col-md-12">
                                                        <?php echo Form::label('dependent_dob', __('Date of birth'), ['class' => 'form-label']); ?>

                                                        <input type="date" name="dependent_dob[]"
                                                            value="<?php echo e($exp->getRawOriginal('dependent_dob')); ?>"
                                                            class='form-control' max="<?php echo date('Y-m-d'); ?>">

                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <?php echo Form::label('dependent_dob', __('Emergency phone no.'), ['class' => 'form-label']); ?>

                                                        <?php echo e(Form::number('emergency_phone_no[]', $exp->emergency_phone_no, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Emergency phone no'])); ?>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row d-flex align-items-center">
                                                <div class="col col-6">
                                                    <h5><?php echo e(__('Dependent')); ?></h5>
                                                </div>
                                                <div class="col col-6 text-end">
                                                    <div class="btn btn-success" id='Dependent_sub_form_eduction_table'>
                                                        <?php echo e('+'); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body employee-detail-create-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <?php echo Form::label('dependent_name', __('Name'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('dependent_name[]', null, [
                                                        'class' => 'form-control dependent_name',
                                                        'placeholder' => 'Enter dependent name',
                                                    ]); ?>


                                                </div>

                                                <div class="form-group col-md-12">
                                                    <?php echo e(Form::label('dependent_relation', __('Relation Types '), ['class' => 'form-label'])); ?>

                                                    <span class="text-danger pl-1 relation_type_label"
                                                        style="display:none"> *</span>
                                                    <div class="form-icon-user">
                                                        <select name="dependent_relation[]" id="dependent_relation"
                                                            class="form-control select2">
                                                            <option value="">Select option</option>
                                                            <option value="Father">Father</option>
                                                            <option value="Mother">Mother</option>
                                                            <option value="Brother">Brother</option>
                                                            <option value="Sister">Sister</option>
                                                            <option value="Husband">Husband</option>
                                                            <option value="Wife">Wife</option>
                                                            <option value="Child">Child</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <?php echo Form::label('dependent_dob', __('Date of birth'), ['class' => 'form-label']); ?>

                                                    <input type="date" name="dependent_dob[]" class='form-control'
                                                        max="<?php echo date('Y-m-d'); ?>">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <?php echo Form::label('dependent_dob', __('Emergency phone no.'), ['class' => 'form-label']); ?>

                                                    <?php echo e(Form::number('emergency_phone_no[]', null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Emergency phone no'])); ?>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div id='Dependent_sub_form_eduction_table_add_form'></div>
                        </div>

                        <div class="tab-pane fade" id="nav-Commission" role="tabpanel"
                            aria-labelledby="nav-Commission-tab">
                            <div class="col-md-12 ">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?php echo e(__('Document')); ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-sm-4 col-md-3">
                                                    <div class="form-group upload-box">
                                                        <label for="document" class=" form-label"><?php echo e($document->name); ?>

                                                            <?php if($document->is_ == 1): ?>
                                                                <span class="text-danger">*</span>
                                                            <?php endif; ?>
                                                        </label>
                                                        <div class="upload-files-box">
                                                            <input type="hidden" name="emp_doc_id[<?php echo e($document->id); ?>]"
                                                                id="" value="<?php echo e($document->id); ?>">

                                                            <?php
                                                                $employeedoc = !empty($employee->documents())
                                                                    ? $employee
                                                                        ->documents()
                                                                        ->pluck('document_value', __('document_id'))
                                                                    : [];
                                                            ?>

                                                            <div class="uploaded-files">
                                                                <?php
                                                                    $logo = \App\Models\Utility::get_file(
                                                                        'uploads/document/',
                                                                    );

                                                                ?>
                                                                <?php if(!empty($employeedoc[$document->id])): ?>
                                                                    <?php if(substr($employeedoc[$document->id], -4) == '.pdf'): ?>
                                                                        <span class="text-xs-1"><a
                                                                                href="<?php echo e(!empty($employeedoc[$document->id]) ? asset(Storage::url('uploads/document')) . '/' . $employeedoc[$document->id] : ''); ?>"
                                                                                target="_blank"><?php echo e($employeedoc[$document->id]); ?></a>
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <img id="<?php echo e('blah' . $key); ?>"
                                                                            src="<?php echo e(isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : ''); ?>" />
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <img id="<?php echo e('blah' . $key); ?>" src="" />
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="choose-files">

                                                                <label for="document[<?php echo e($document->id); ?>]">

                                                                    <div class=" bg-primary document "> <i
                                                                            class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                                    </div>
                                                                    @trunc
                                                                    <input type="file"
                                                                        class="form-control file d-none <?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        <?php if($document->is_ == 1): ?>  <?php endif; ?>
                                                                        name="document[<?php echo e($document->id); ?>]"
                                                                        id="document[<?php echo e($document->id); ?>]"
                                                                        data-filename="<?php echo e($document->id . '_filename'); ?>"
                                                                        onchange="document.getElementById('<?php echo e('blah' . $key); ?>').src = window.URL.createObjectURL(this.files[0])">
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Loan" role="tabpanel" aria-labelledby="nav-Loan-tab">

                            

                            <?php if($isProfileEdit == false): ?>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5><?php echo e(__('Bank Account')); ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']); ?>

                                                    <span class="text-danger">*</span>
                                                    <?php echo Form::text('account_holder_name', null, ['class' => 'form-control']); ?>


                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('account_number', __('Account Number'), ['class' => 'form-label']); ?>

                                                    <span class="text-danger">*</span>
                                                    <?php echo Form::number('account_number', null, ['class' => 'form-control']); ?>


                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('bank_name', null, ['class' => 'form-control']); ?>


                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']); ?>

                                                    <span class="text-danger">*</span>
                                                    <?php echo Form::text('bank_identifier_code', null, ['class' => 'form-control']); ?>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']); ?>

                                                    <span class="text-danger">*</span>
                                                    <?php echo Form::text('branch_location', null, ['class' => 'form-control']); ?>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php echo Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('tax_payer_id', null, ['class' => 'form-control']); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
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
                                                            <strong class="font-bold"><?php echo e(__('Account Number')); ?>

                                                                :</strong>
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
                                                            <strong class="font-bold"><?php echo e(__('Branch Location')); ?>

                                                                :</strong>
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
                            <?php endif; ?>


                        </div>

                        <div class="tab-pane fade" id="nav-weekoff" role="tabpanel" aria-labelledby="nav-weekoff-tab">

                            <?php if($isProfileEdit == false): ?>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-2"><span class="text-dark"><i
                                                        class="badge bg-dark p-2  rounded"><?php echo e(__('WO')); ?></i>&nbsp;
                                                    <?php echo e(__('Week Off')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <label for="is_custom_weekoffs">
                                            <h5 class=""><?php echo e(__('Enable Custom Weekoff')); ?>

                                            </h5>
                                        </label>
                                        <input type="checkbox" class="form-check-input" id="is_custom_weekoffs"
                                            name="is_custom_weekoffs"
                                            <?php echo e($employee->is_custom_weekoffs == 1 ? 'checked' : ''); ?>>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-6 m-auto">
                                        
                                        <input type="hidden" name="weekoffs_data" id="weekoffs_data"
                                            class="weekoffs_data" value="<?php echo e($employee->weekoffs_data ?? ''); ?>">
                                        <div id='calendar'></div>

                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        
                        <div class="text-end">
                            <button type="submit" class="btn  btn-primary"><?php echo e('Update'); ?></button>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>



    
<?php $__env->stopSection(); ?>

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<?php $__env->startPush('script-page'); ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>


    <?php if($isProfileEdit == false): ?>
        <script>
            var isCalenderInitialize = false;
            $('#is_custom_weekoffs').change(function() {
                if ($(this).prop('checked')) {

                    $("#calendar").show();

                    showCalender()
                } else {
                    $("#calendar").hide()
                }
            });
            var selectedDates = {};

            $('#nav-tab >button').on('shown.bs.tab', function(e) {
                console.log("clicked id", e.target.id);

                if (e.target.id == "nav-weekoff-tab" && $("#is_custom_weekoffs").prop('checked')) {
                    $("#calendar").show();
                    showCalender()
                } else {
                    $("#calendar").hide();
                    //  $("#weekoffs_data").val("");
                }
            });

            <?php if($employee->is_custom_weekoffs == 1): ?>
                //   showCalender();
            <?php endif; ?>

            function showCalender() {

                if (isCalenderInitialize) {
                    return;
                }

                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    buttonText: {
                        today: 'Today'
                    },
                    dateClick: function(info) {
                        var dateStr = info.dateStr;

                        // Check if the date is already selected
                        if (selectedDates[dateStr]) {
                            // Date is already selected, remove it
                            //     selectedDates[dateStr].remove();
                            //     delete selectedDates[dateStr];
                        } else {
                            // Select new date and add event
                            var event = calendar.addEvent({
                                title: 'Week Off',
                                start: dateStr,
                                allDay: true,
                                className: 'selected-event',
                                color: '#1C232F'
                            });

                            selectedDates[dateStr] = event;
                        }

                        // saveToLocalStorage(Object.keys(selectedDates));

                        $("#weekoffs_data").val(JSON.stringify(Object.keys(selectedDates)));
                        // sendSelectedDates();
                    },
                    eventContent: function(info) {
                        var deleteButton = document.createElement('button');
                        deleteButton.className = 'delete-button';
                        deleteButton.innerHTML = '<span class="material-icons">delete</span>';
                        deleteButton.onclick = function() {
                            info.event.remove();
                            delete selectedDates[info.event.startStr];
                            //     saveToLocalStorage(Object.keys(selectedDates));
                            //     sendSelectedDates();
                            $("#weekoffs_data").val(JSON.stringify(Object.keys(selectedDates)));
                        };

                        var arrayOfDomNodes = [deleteButton]
                        return {
                            domNodes: arrayOfDomNodes
                        }
                    }
                });
                calendar.render();


                // add data

                console.log("weekoffs_data")
                console.log('<?php echo $employee->weekoffs_data ?? ''; ?>');

                <?php if(!empty($employee->weekoffs_data) && $employee->weekoffs_data != '[]' && $employee->weekoffs_data != null): ?>

                    //  alert("in");

                    var storedDates = JSON.parse('<?php echo $employee->weekoffs_data ?? ''; ?>');



                    for (var dateStr of storedDates) {
                        var event = calendar.addEvent({
                            title: 'Week Off',
                            start: dateStr,
                            allDay: true,
                            className: 'selected-event',
                            color: '#1C232F'
                        });

                        selectedDates[dateStr] = event;
                    }
                <?php endif; ?>
                // add data

                isCalenderInitialize = true;
                console.log("Calendar initialize.");
            }
        </script>
    <?php endif; ?>

    <script type="text/javascript">
        var branchesGeofencingStatus = <?php echo json_encode(@$branchesGeofencingStatus); ?>;

        function getDesignation(did) {


            $.ajax({
                url: "<?php echo e(route('employee.json')); ?>",
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {
                    $('.designation_id').empty();
                    var emp_selct = `<select class="form-control col-md-12 designation_id select2" name="designation_id" id="choices-multiple"
                                            placeholder="Select Designation" >
                                            </select>`;
                    $('.designation_div').html(emp_selct);
                    $('.designation_id').append('<option value="">Select any Designation</option>');
                    $.each(data, function(key, value) {
                        var select = '';
                        if (key == '<?php echo e($employee->designation_id); ?>') {
                            select = 'selected';
                        }

                        $('.designation_id').append('<option value="' + key + '"  ' + select + '>' +
                            value + '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });
                }
            });
        }

        // Store the initial saved employee-level description
        var savedEmployeeDescription = <?php echo json_encode($employee->designation_description ?? '', 15, 512) ?>;

        // Fetch designation description when designation changes
        $(document).on('change', '.designation_id', function() {
            var designationId = $(this).val();
            var currentDescription = $('#designation_description').val();

            // If there's a saved employee-level description, don't change it
            if (savedEmployeeDescription && savedEmployeeDescription.trim() !== '') {
                // Restore the saved employee description if it was changed
                if (currentDescription !== savedEmployeeDescription) {
                    $('#designation_description').val(savedEmployeeDescription);
                }
                return; // Don't fetch new designation description
            }

            // Only fetch if there's no saved employee-level description
            if (designationId) {
                $.ajax({
                    url: "<?php echo e(route('employee.designation.description')); ?>",
                    type: 'POST',
                    data: {
                        "designation_id": designationId,
                        "_token": "<?php echo e(csrf_token()); ?>",
                    },
                    success: function(data) {
                        $('#designation_description').val(data.description || '');
                    }
                });
            } else {
                // Clear only if there's no saved employee description
                $('#designation_description').val('');
            }
        });

        function calculateSalary() {

            try {
                var ctc_annual = $("#ctc_annual").val() ?? 0;
                var ctc_monthely = parseFloat(ctc_annual / 12);
                $("#ctc_monthely").val(ctc_monthely.toFixed(2));

                var salary = $("#salary").val();
                var calculatedCtcPercentage = (salary / ctc_monthely) * 100;

                if (isNaN(calculatedCtcPercentage) || !isFinite(calculatedCtcPercentage)) {
                    calculatedCtcPercentage = 0;
                }

                $("#ctc_percentage").val(calculatedCtcPercentage.toFixed(2));

            } catch (error) {
                console.log("error", error);
                $("#salary").val(0);
            }
        }

        $(document).ready(function() {

            calculateSalary();

            $('.numberValidation').on('input keypress keyup paste dragover', function(e) {
                // Check if the entered value is a valid number
                var inputValue = $(this).val() + String.fromCharCode(e.which);
                if (!$.isNumeric(inputValue)) {
                    // Prevent the input if it's not a number
                    e.preventDefault();
                }
            });

            $('.salaryAmountValidation').on('input keypress keyup paste dragover', function(e) {
                // Check if the entered value is a valid number
                var inputValue = $(this).val() + String.fromCharCode(e.which);
                if (!$.isNumeric(inputValue)) {
                    // Prevent the input if it's not a number
                    e.preventDefault();
                }
                calculateSalary();
            });

            $('.ctcPercentageValidator').on('input keypress keyup paste dragover', function(e) {
                // Check if the entered value is a valid number
                var inputValue = parseFloat($(this).val());
                if (inputValue > 100) {
                    $(this).val(100);
                }
                calculateSalary();
            });


            //branch_id
            $(document).on('change', 'select[name=branch_id]', function() {
                var branch_id = $(this).val();


                try {
                    if (branchesGeofencingStatus[branch_id] == 1 && branch_id != "") {
                        $("#geofencing_status").prop("disabled", false);
                    } else {
                        $("#geofencing_status").prop("disabled", true);
                    }

                } catch (error) {
                    $("#geofencing_status").prop("disabled", true);
                }

                //  if (branch_id == <?php echo e($employee->branch_id); ?> && <?php echo e($employee->geofencing_status); ?> ==
                //      1) {
                //      $("#geofencing_status").prop("checked", true);
                //  } else {
                //      $("#geofencing_status").prop("checked", false);
                //  }
                $("#geofencing_status").prop("checked", false);

                $.ajax({
                    url: "<?php echo e(route('employee.Branch_department_json')); ?>",
                    type: 'POST',
                    data: {
                        "branch_id": branch_id,
                        "_token": "<?php echo e(csrf_token()); ?>",
                    },
                    success: function(data) {
                        $('#department_id').empty('');
                        $('#department_id').append(
                            '<option value=""><?php echo e(__('Select Department ')); ?></option>');
                        $.each(data, function(key, value) {
                            console.log('<option value="' + key + '">' + value +
                                '</option>');
                            $('#department_id').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                    }
                });
            });

            var checked = $('#office_staff').find(":selected").val();

            if (checked == 1) {
                $('.select_grade_type').addClass('d-none');
            } else {
                if (checked == '') {
                    $('.select_grade_type').addClass('d-none');
                } else {
                    $('.select_grade_type').removeClass('d-none');
                }
            }

            //check select_office_staff
            $(document).on('change', '#office_staff', function() {
                var office_staff = $(this).val();
                if (office_staff == 2) {
                    $('.select_grade_type').removeClass('d-none');
                } else {
                    $('#grade_type_id').val('');
                    $('.select_grade_type').addClass('d-none');
                }
            });
            $("#employee_update").submit(function(e) {
                event.preventDefault();
                var form_cust = this;
                var formData = new FormData(form_cust);
                var url = $(this).attr('action');
                var type = "POST";
                $.ajax({
                    context: 'commonModal',
                    type: type,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 1) {
                            show_toastr('Success', response.message, 'success');
                            //    window.location.href = "<?php echo e(route('employee.index')); ?>";
                            window.location.href = response.redirect;
                        }
                        if (response.status == 0) {
                            $.each(response.message, function(key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                })
                return false;
            });

            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;


            var d_id = $('#department_id').val();
            var designation_id = '<?php echo e($employee->designation_id); ?>';
            getDesignation(d_id);


            //autometically ajax call
            //  var country_id = $('#select_roles').val();
            //     var selct = ''
            //     var state_id = <?php echo e($employee->users_types_id); ?>

            //     $.ajax({
            //         type:'get',
            //         url: '<?php echo e(url('get_users_data')); ?>' + '/' + country_id,
            //         data:{},
            //             success:function(state){
            //             $.each(state, function (key, value) {
            //                     if(value.id == state_id){
            //                         selct  = 'selected';
            //                     }else{
            //                         selct  = '';
            //                     }
            //                     $("#select_users").append('<option ' + selct + ' value="' + value.id + '">' + value.name + '</option>');
            //             });
            //         }
            // });

            // $("#select_roles").change(function() {

            //     var id = $('#select_roles').val();
            //     $.ajax({
            //         type: 'get',
            //         url: '<?php echo e(url('get_users_data')); ?>' + '/' + id,
            //         data: {},
            //         success: function(state) {
            //             $('#select_users').html('');
            //             $('#select_users').append('<option value="">Select a users</option>');
            //             $.each(state, function(key, value) {
            //                 $("#select_users").append('<option value="' + value
            //                 .id + '">' + value.name + '</option>');
            //             });
            //         }
            //     });
            // });



            //ajax
            var index = "<?php echo e($EXPTableDatacount); ?>";
            $("#add_sub_form").click(function() {

                var now = new Date();
                var month = (now.getMonth() + 1);
                var day = now.getDate();
                if (month < 10) month = "0" + month;
                if (day < 10) day = "0" + day;
                var today = now.getFullYear() + '-' + month + '-' + day;


                index++;
                var designations = <?php echo json_encode($designations); ?>;

                $('#append_sub_form').append('<div class="col-md-12" id="delete_sub_form">' +
                    '<div class="card">' +
                    '<div class="card-header">' +
                    '<h5>Experience</h5>' +
                    '<p class="btn btn-danger float-end" id="programings_btn_remove">- remove</p>' +
                    '</div>' + '<div class="card-body employee-detail-create-body">' +
                    '<div class="row">' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Previous Company</lable>' +
                    '<input type="text" name="previous_company_name[' + index +
                    ']" class="form-control" placeholder="Enter previous company" ></input>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Job Title</lable>' +
                    '<input type="text" name="previous_job_title[' + index +
                    ']" class="form-control" placeholder="Enter previous job title"></input>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">From Date</lable>' +
                    '<input type="date" max=' + today + ' name="previous_from_date[' + index +
                    ']" class="form-control" placeholder="Enter previous job title"></input>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">To Date</lable>' +
                    '<input type="date" max=' + today + ' name="previous_to_date[' + index +
                    ']" class="form-control" placeholder="Enter previous job title"></input>' +
                    '</div>' +

                    '<div class="form-group col-md-12">' +

                    '<lable class="form-label">Previous Company designation</lable>' +
                    '<input type="text" name="previous_company_designation_id[' + index +
                    ']" id="previous_company_designation_id[' + index +
                    ']"class="form-control" placeholder="Enter previous company designation"></input>' +
                    '</div>' +
                    '<div class="form-group col-md-12">' +
                    '<lable class="form-label">Experience Job Description</lable>' +
                    '<textarea type="text" name="experiance_Job_description[' + index +
                    ']" class="form-control" placeholder="Enter Experience job description"></textarea>' +
                    '</div>' +
                    '</div></div></div></div>');
            });
            $(document).on('click', '#programings_btn_remove', function() {
                $(this).closest('div#delete_sub_form').remove();
            });
        });

        //education table
        var eduction_table = "<?php echo e($EducationDatacount); ?>";
        $("#append_sub_form_eduction_table").click(function() {


            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;

            eduction_table++;

            $('#append_sub_form_eduction_table_add_form').append(
                '<div class="col-md-12" id="delete_sub_form_eduction_table">' +
                '<div class="card">' +
                '<div class="card-header">' +
                '<h5>Education</h5>' +
                '<p class="btn btn-danger float-end" id="programings_btn_remove_eduction_table">- remove</p>' +
                '</div>' + '<div class="card-body employee-detail-create-body">' +
                '<div class="row">' +
                '<div class="form-group col-md-6">' +
                '<lable class="form-label">Institute Name</lable>' +
                '<input type="text" name="institute_name[' + eduction_table +
                ']" class="form-control" placeholder="Enter institute name" ></input>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<lable class="form-label">Education</lable>' +
                '<input type="text" name="education[' + eduction_table +
                ']" class="form-control" placeholder="Enter previous job title"></input>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<lable class="form-label">Specialization</lable>' +
                '<input type="text" name="specialization[' + eduction_table +
                ']" class="form-control" placeholder="Enter specialization"></input>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<lable class="form-label">Date of birth</lable>' +
                '<input type="date" max=' + today + ' id="date_of_completion" name="date_of_completion[' +
                eduction_table +
                ']" class="form-control" placeholder="Enter previous job title"></input>' + '</div>' +

                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Notes</lable>' +
                '<textarea type="text" name="notes[' + eduction_table +
                ']" class="form-control" placeholder="Enter notes"></textarea>' +
                '</div>' +
                '</div></div></div></div>');
        });
        $(document).on('click', '#programings_btn_remove_eduction_table', function() {
            $(this).closest('div#delete_sub_form_eduction_table').remove();
        });

        //Dependent
        //Dependent-TABLE add-more
        var Dependent = "<?php echo e($DependentDatacount); ?>";
        $("#Dependent_sub_form_eduction_table").click(function() {


            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;

            Dependent++;

            $('#Dependent_sub_form_eduction_table_add_form').append(
                '<div class="col-md-12" id="delete_programings_btn_remove_Dependent_table">' +
                '<div class="card">' +
                '<div class="card-header">' +
                '<h5>Dependent</h5>' +
                '<p class="btn btn-danger float-end" id="programings_btn_remove_Dependent_table">- remove</p>' +
                '</div>' + '<div class="card-body employee-detail-create-body">' +
                '<div class="row">' +
                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Name</lable>' +
                '<input type="text" name="dependent_name[' + Dependent +
                ']" class="form-control dependent_name" placeholder="Enter dependent name" ></input>' +
                '</div>' +
                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Education</lable>' +
                '<span class="text-danger pl-1 relation_type_label" style="display:none"> *</span>' +
                '<select  name="dependent_relation[' + Dependent + ']" id="dependent_relation' + Dependent +
                '" class="form-control select2">' +
                '<option value="">-Select Relation</option>' +
                '<option vale="Father">Father</option>' +
                '<option value="Mother">Mother</option>' +
                '<option value="Brother">Brother</option>' +
                '<option value="Sister">Sister</option>' +
                '<option value="Husband">Husband</option>' +
                '<option value="Wife">Wife</option>' +
                '<option value="Child">Child</option>' +
                '</select>' +
                '</div>' +
                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Date of birth</lable>' +
                '<input type="date" max=' + today + ' name="dependent_dob[' + Dependent +
                ']" class="form-control" placeholder="Enter specialization"></input>' +
                '</div>' +

                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Emergency phone no.</lable>' +
                '<input type="number" name="emergency_phone_no[' + Dependent +
                ']" class="form-control" placeholder="Emergency phone no."></input>' +
                '</div>' +
                '</div></div></div></div>');
        });
        $(document).on('click', '#programings_btn_remove_Dependent_table', function() {
            $(this).closest('div#delete_programings_btn_remove_Dependent_table').remove();
        });


        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        $('.dependent_name').on('input', function() {

            var inputValue = $(this).val();

            var closestRequiredLabel = $(this).parent().parent().find('.relation_type_label');
            //console.log('closestRequiredLabel:', closestRequiredLabel);

            if ($.trim(inputValue) == '') {
                closestRequiredLabel.hide();
            } else {
                closestRequiredLabel.show();
            }
        });
    </script>

    <script>
        $('#epf_status').change(function() {
            if ($(this).prop('checked')) {
                $("#epf_div").show();
            } else {
                $("#epf_div").hide();
            }
        });

        $('#esi_status').change(function() {
            if ($(this).prop('checked')) {
                $("#esi_div").show();
            } else {
                $("#esi_div").hide();
            }
        });

        $('#ept_status').change(function() {
            if ($(this).prop('checked')) {
                $("#ept_div").show();
            } else {
                $("#ept_div").hide();
            }
        });

        $('.hideShowDiv').change(function() {
            var divId = $(this).attr("divId");
            if ($(this).prop('checked')) {
                $("#" + divId).show();
            } else {
                $("#" + divId).hide();
            }
        });

        $('#statutory_bonus_status').change(function() {
            if ($(this).prop('checked')) {
                $("#statutory_bonus_div").show();
            } else {
                $("#statutory_bonus_div").hide();
            }
        });

        $('#tax_deducted_at_source_status').change(function() {
            if ($(this).prop('checked')) {
                $("#tax_deducted_at_source_div").show();
            } else {
                $("#tax_deducted_at_source_div").hide();
            }
        });

        $('#marital_status').change(function() {
            if ($(this).val() == "Married" || $(this).val() == "Widowed") {
                $(".marriage_anniversary_date_div").show();
            } else {
                $(".marriage_anniversary_date_div").hide();
            }
        });

        $('.icon_Confirmpassword').click(function() {
            if ($('.password').attr('type') == 'text') {
                $('.password').attr('type', 'password');
            } else {
                $('.password').attr('type', 'text');
            }
        });

        $('.icon_password').click(function() {
            if ($('.enterpassword').attr('type') == 'text') {
                $('.enterpassword').attr('type', 'password');
            } else {
                $('.enterpassword').attr('type', 'text');
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/employee/edit.blade.php ENDPATH**/ ?>