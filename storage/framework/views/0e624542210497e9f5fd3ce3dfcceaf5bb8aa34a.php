<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Job')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Manage Job')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('script-page'); ?>
    <script>
        $('.copy_link').click(function(e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function(e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('Success', 'Url copied to clipboard', 'success');
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('action-button'); ?>
   
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Job')): ?>
        <a href="<?php echo e(route('job.create')); ?>"  data-ajax-popup="true" data-size="md"
            data-title="<?php echo e(__('Create New Job')); ?>" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Create')); ?>">
            <i class="ti ti-plus"></i>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">

  




    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th><?php echo e(__('SR No.')); ?></th>
                                <th><?php echo e(__('Title')); ?></th>
                                <th><?php echo e(__('No. of opening')); ?></th>
                                <th><?php echo e(__('Skills')); ?></th>
                                <th><?php echo e(__('Branch')); ?></th>
                                <th width="200px"><?php echo e(__('Apply')); ?></th>
                                <th width="200px"><?php echo e(__('Reference')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                           
                        <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key+1); ?></td>
                                            <th scope="row">
                                                    
                                                    <div class="media-body">
                                                        <a href="<?php echo e(route('job.requirement', [$job->code, !empty($job) ? (!empty($job->createdBy->lang) ? $job->createdBy->lang : 'en') : 'en'])); ?>"
                                                           ><?php echo e($job->title); ?> </a>
                                                    </div>
                                            </th>
                                            <td><?php echo e($job->position); ?> </td>
                                            <td>

                                                <?php $__currentLoopData = explode(',', $job->skill); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-primary p-2 px-3 rounded text-white"><?php echo e($skill); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>

                                            <td><span
                                                    class="h6"><?php echo e(!empty($job->branches)?$job->branches->name:'-'); ?></span>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('job.requirement', [$job->code, !empty($job) ? (!empty($job->createdBy->lang) ? $job->createdBy->lang : 'en') : 'en'])); ?>" class="btn btn-primary btn-sm">Apply</a>
                                            </td>

                                            <td>
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg"
                                                        data-url="<?php echo e(URL::to('job_reference/'.$job->id.'')); ?>"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="<?php echo e(__('Job Reference')); ?>"
                                                        data-bs-original-title="<?php echo e(__('Job Reference')); ?>">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/job/career.blade.php ENDPATH**/ ?>