<style>
     .text-right {
          text-align: right;
     }

     .text-center {
          text-align: center;
     }
</style>

<?php $__env->startSection('page-title'); ?>

<?php echo e(__('Wage Reports')); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>

<li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>

<li class="breadcrumb-item"><?php echo e(__('Wage Reports')); ?></li>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Promotion')): ?>



<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">

     <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
          <div class=" mt-2 " id="" style="">
               <div class="card">
                    <div class="card-body">
                         <?php echo e(Form::open(['route' => ['wage_reports_index'], 'method' => 'get'])); ?>


                         <div class="row align-items-center">
                              <div class="col-sm-10">
                                   <div class="row ">
                                        <div class="col-sm-6 col-lg-3">
                                             <div class="btn-box">
                                                  <?php echo e(Form::label('month', __(' Month'), ['class' => 'form-label'])); ?>

                                                  <?php echo e(Form::month('month', $requestMonth, ['class' => 'month-btn form-control current_date', 'autocomplete' => 'off', 'placeholder' => 'Select month'])); ?>

                                             </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3" style="display: <?php echo e(Auth::user()->type == "employee" ? 'none' : ''); ?>">
                                             <div class="btn-box">
                                                  <?php echo e(Form::label('paySlabTypeId', __('Pay Slab'), ['class' => 'form-label'])); ?>

                                                  <?php echo e(Form::select('paySlabTypeId', $paySlabTypes, $requestPaySlabTypeId ?? 0, ['class' => 'form-control select2Element paySlabTypeId', 'id' => 'paySlabTypeId'])); ?>

                                             </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                             <div class="btn-box">
                                                  <?php echo e(Form::label('status', __('Status'), ['class' => 'form-label'])); ?>

                                                  <?php echo e(Form::select('status', $statusArray, $requestStatus ?? 1, ['class' => 'form-control select2Element status', 'id' => 'status'])); ?>

                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="col-sm-2 text-right pt-4">
                                   <button type="submit" name="btn_search" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="" data-bs-original-title="apply" value="0">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                   </button>

                                   <button type="submit" name="btn_reset" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Reset" value="1">
                                        <span class="btn-inner--icon"><i class="ti ti-refresh  text-white-off "></i></span>
                                   </button>

                                   <button type="submit" name="btn_export" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Export" value="2">
                                        <span class="btn-inner--icon"><i class="ti ti-file-export text-white-off "></i></span>
                                   </button>
                              </div>
                         </div>

                         <?php echo e(Form::close()); ?>

                    </div>
               </div>
          </div>
     </div>

     <div class="col-xl-12">

          <div class="card">

               <div class="card-header card-body table-border-style">

                    <div class="table-responsive">

                         <table class="table">

                              <thead>

                                   <tr>

                                        <th class="text-center"><?php echo e(__('Sr.no')); ?></th>
                                        <?php if($requestPaySlabTypeId == 0): ?>
                                        <th class="text-center"><?php echo e(__('Pay Slab')); ?></th>
                                        <?php endif; ?>

                                        <th class="text-center"><?php echo e(__('EMP Code')); ?></th>
                                        <th class="text-center"><?php echo e(__('Name')); ?></th>
                                        <th class="text-center"><?php echo e(__('Designation')); ?></th>
                                        <th class="text-center"><?php echo e(__('Department')); ?></th>

                                        <th class="text-center"><?php echo e(__('Basic Salary')); ?></th>

                                        

                                        
                                        <?php $__currentLoopData = $allowances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allowancesRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center"><?php echo e($allowancesRow->name); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                        
                                        <?php $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commissionsRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center"><?php echo e($commissionsRow->name); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                        
                                        <?php $__currentLoopData = $other_payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $other_paymentsRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center"><?php echo e($other_paymentsRow->name); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                        

                                        <th class="text-center">Gross Earning</th>

                                        
                                        
                                        <?php $__currentLoopData = $saturation_deductions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saturation_deductionsRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center"><?php echo e($saturation_deductionsRow->name); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                        
                                        <th class="text-center">PF</th>
                                        <th class="text-center">ESIC</th>
                                        


                                        
                                        <th class="text-center">Loan Amount</th>
                                        

                                        
                                        <th class="text-center">Leave Amount</th>
                                        

                                        


                                        <th class="text-center">Gross Deduction</th>
                                        <th class="text-center">Net Pay</th>

                                        <?php if($requestStatus == -1): ?>
                                        <th class="text-center">Status</th>
                                        <?php endif; ?>
                                   </tr>

                              </thead>

                              <tbody>

                                   <?php $srNo = 1; ?>
                                   <?php $__currentLoopData = $employeesPayrollData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employeesPayrollRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                   <tr>

                                        <td class="text-center"><?php echo e($srNo); ?></td>

                                        <?php if($requestPaySlabTypeId == 0): ?>
                                        <td class="text-center"><?php echo e($employeesPayrollRow['pay_slap_type']); ?></td>
                                        <?php endif; ?>

                                        <td class="text-center"><?php echo e($employeesPayrollRow['employee_id']); ?></td>
                                        <td class="text-center"><?php echo e($employeesPayrollRow['name']); ?></td>
                                        <td class="text-center"><?php echo e($employeesPayrollRow['designation']); ?></td>
                                        <td class="text-center"><?php echo e($employeesPayrollRow['department']); ?></td>

                                        <td class="text-right"><?php echo e($employeesPayrollRow['basic_salary']); ?></td>

                                        

                                        
                                        <?php $__currentLoopData = ($employeesPayrollRow['allowances'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payrollAllowancesRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-right"><?php echo e($payrollAllowancesRow); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                        
                                        <?php $__currentLoopData = ($employeesPayrollRow['commission'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payrollcommissionsRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-right"><?php echo e($payrollcommissionsRow); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                        
                                        <?php $__currentLoopData = ($employeesPayrollRow['otherPayment'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payrollotherPaymentRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-right"><?php echo e($payrollotherPaymentRow); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                        

                                        
                                        <td class="text-right"><?php echo e($employeesPayrollRow['grossEarnings']); ?></td>
                                        


                                        

                                        
                                        <?php $__currentLoopData = ($employeesPayrollRow['deduction'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payrolldeductionRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-right"><?php echo e($payrolldeductionRow); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        

                                        
                                        <td class="text-right"><?php echo e($employeesPayrollRow['pf']); ?></td>
                                        <td class="text-right"><?php echo e($employeesPayrollRow['esic']); ?></td>
                                        


                                        
                                        <td class="text-right"><?php echo e($employeesPayrollRow['loanAmount']); ?></td>
                                        

                                        
                                        <td class="text-right"><?php echo e($employeesPayrollRow['leaveAmount']); ?></td>
                                        

                                        



                                        
                                        <td class="text-right"><?php echo e($employeesPayrollRow['grossDeductions']); ?></td>
                                        

                                        
                                        <td class="text-right"><?php echo e($employeesPayrollRow['netPayableSalary']); ?></td>
                                        

                                        
                                        <?php if($requestStatus == -1): ?>
                                        <td class="text-center"><?php echo e($employeesPayrollRow['paySlipStatus']); ?></td>
                                        <?php endif; ?>
                                        

                                   </tr>

                                   <?php $srNo++; ?>

                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                              </tbody>

                         </table>

                    </div>

               </div>

          </div>

     </div>

</div>

<?php $__env->stopSection(); ?>


<script>
     $('.select2Element').select2();
</script>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/ReportsManage/index.blade.php ENDPATH**/ ?>