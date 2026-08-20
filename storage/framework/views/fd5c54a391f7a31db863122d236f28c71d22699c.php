<?php
    use App\Models\Utility;
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $languages = \App\Models\Utility::languages();
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
    $data_branches = \App\Models\Branch::where('created_by', '=', \Auth::user()->creatorId())->get();

?>

<style>
    select#branch {
        width: 169px;
    }

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }
</style>

<?php if(isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on'): ?>
    <header class="dash-header transprent-bg">
    <?php else: ?>
        <header class="dash-header">
<?php endif; ?>


<div class="header-wrapper">
    <div class="me-auto dash-mob-drp">
        <ul class="list-unstyled">
            <li class="dash-h-item mob-hamburger">
                <a href="#!" class="dash-head-link" id="mobile-collapse">
                    <div class="hamburger hamburger--arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
            </li>



            <div class="row">
                <div class="col">
                    <?php /*?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    ?>
                    <li class="dropdown dash-h-item drp-company">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="theme-avtar">
                                <img alt="#"
                                    src="{{ !empty($users->avatar) ? $profile . $users->avatar : $profile . '/avatar.png' }}"
                                    class="header-avtar" style="width: 100%; border-radius:50%">
                            </span>
                            <span class="hide-mob ms-2"> {{ Auth::user()->name }}
                                <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown">
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="ti ti-user"></i>
                                <span>{{ __('My Profile') }}</span>
                            </a>
                            <a href="{{ route('logout') }}" class="dropdown-item">
                                <i class="ti ti-power"></i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                            
                        </div>
                    </li><?php */ ?>
                </div>

            </div>






        </ul>
    </div>
    <div class="ms-auto">
        <ul class="list-unstyled">

            <?php
                $unseenCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)
                    ->where('seen', 0)
                    ->count();
            ?>

            

            

            <?php
                $unreadCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)
                    ->where('seen', 0)
                    ->count();
            ?>

            <?php if(\Auth::user()->type != 'super admin'): ?>
                <li class="dropdown dash-h-item drp-notification">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0 " data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false" id="msg-btn">
                        
                        <i class="fa fa-bell"></i>
                        
                        <span class="bg-danger dash-h-badge message-counter custom_messanger_counter">
                            <?php if(!empty(bdaynotification())): ?>
                                <?php echo e(bdaynotification()); ?>

                            <?php endif; ?>
                            <span class="sr-only"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        <div class="noti-header">
                            <h5 class="m-0"><?php echo e(__('Notifications')); ?></h5>
                            <a href="#" class="dash-head-link mark_all_as_read_message"><?php echo e(__('Clear All')); ?></a>
                        </div>

                        <div class="noti-body dropdown-list-message-msg">
                            <div style="display: flex;">
                                

                                <div class="notifications-wrapper">
                                    <?php
                                    $employee = employeeBdayGet();
                                    ?>
                                    <?php if(count($employee) > 0): ?>
                                        <?php $__currentLoopData = $employee; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a class="content" href="<?php echo e(redirectUrl($row)); ?>"
                                                data-id="<?php echo e($row->id); ?>" id="notification-redirect-url">
                                                <div class="notification-item">
                                                    <h4><?php echo e($row->title); ?></h4>
                                                    <span><?php echo e($row->details); ?></span>
                                                </div>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <span><?php echo e(__('You have no unread notifications')); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </li>
            <?php endif; ?>

            <?php
                $currantLang = basename(\App::getLocale());
            ?>

            <li class="dropdown dash-h-item drp-language">
                <a class="dash-head-link dropdown-toggle arrow-none me-0 " data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                    <i class="ti ti-world nocolor"></i>
                    <span class="drp-text hide-mob"><?php echo e(Str::upper($currantLang)); ?></span>
                    <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                    <?php $__currentLoopData = App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('change.language', $lang)); ?>"
                            class="dropdown-item <?php echo e(basename(App::getLocale()) == $lang ? 'text-danger' : ''); ?>"><?php echo e(Str::upper($lang)); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Auth::user()->type == 'super admin'): ?>
                        <div class="dropdown-divider m-0"></div>
                        <a href="<?php echo e(route('manage.language', [basename(App::getLocale())])); ?>"
                            class="dropdown-item text-primary"><?php echo e(__('Manage Language')); ?></a>
                    <?php endif; ?>
                </div>
            </li>

            <?php
                $employee = App\Models\Employee::select('*', 'employee_profile as employee_profile_image')
                    ->where('user_id', \Auth::user()->id)
                    ->first();
            ?>

            <li class="dropdown dash-h-item drp-company">
                <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="theme-avtar">

                        <?php

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

                        <img alt="#" src="<?php echo e($imagePath); ?>"  class="header-avtar"
                            style="width: 100%; border-radius:50%">
                    </span>
                    <span class="hide-mob ms-2"> <?php echo e(Auth::user()->name); ?>

                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown">

                    <?php if(Auth::user()->type == 'company'): ?>
                        <a href="<?php echo e(route('profile')); ?>" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span><?php echo e(__('My Profile')); ?></span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('employeeProfileShow', \Illuminate\Support\Facades\Crypt::encrypt($employee->id ?? 0))); ?>"
                            class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span><?php echo e(__('My Profile')); ?></span>
                        </a>

                        <a href="<?php echo e(route('profile')); ?>" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span><?php echo e(__('Change Password')); ?></span>
                        </a>
                    <?php endif; ?>


                    <a href="<?php echo e(route('logout')); ?>" class="dropdown-item">
                        <i class="ti ti-power"></i>
                        <span><?php echo e(__('Logout')); ?></span>
                    </a>
                    
                </div>
            </li>

        </ul>
    </div>
</div>
</header>
<?php $__env->startPush('scripts'); ?>
    
    <script>
        $("#notification-redirect-url").click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "<?php echo e(route('notification.status')); ?>",
                data: {
                    id: id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == '1') {
                        console.log(url)
                        window.location.href = url;
                    } else {
                        console.log('Notification redirection errror');
                    }
                }
            });

        });
        $(".mark_all_as_read_message").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "get",
                url: "<?php echo e(route('clear-notification')); ?>",
                dataType: "json",
                success: function(response) {
                    if (response.status == '1') {
                        console.log(url)
                        window.location.reload();
                    } else {
                        console.log('Notification clearing error');
                    }
                },
                error: (error) => {
                    console.error(error);
                },
            });

        });
        $('#msg-btn').click(function() {
            let contactsPage = 1;
            let contactsLoading = false;
            let noMoreContacts = false;
            $.ajax({
                url: url + "/getContacts",
                method: "GET",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    page: contactsPage,
                    type: 'custom',
                },
                dataType: "JSON",
                success: (data) => {

                    if (contactsPage < 2) {
                        $(".count-listOfContacts").html(data.contacts);

                    } else {
                        $(".count-listOfContacts").append(data.contacts);
                    }
                    $('.count-listOfContacts').find('.messenger-list-item').each(function(e) {
                        $('.noti-body .activeStatus').remove()
                        $('.noti-body .avatar').remove()
                        $(this).find('span').remove()
                        $(this).find('p').addClass("d-inline")
                        // $(this).find('b').addClass('position-absolute')
                        // $(this).find('b').css({position: "absolute"});
                        $(this).find('b').css({
                            "position": "absolute",
                            "right": "50px"
                        });
                        $(this).find('tr').remove('td')

                    })
                },
                error: (error) => {
                    setContactsLoading(false);
                    console.error(error);
                },
            });
        })
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/html/resources/views/partial/Admin/header.blade.php ENDPATH**/ ?>