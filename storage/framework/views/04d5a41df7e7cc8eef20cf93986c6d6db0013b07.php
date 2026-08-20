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

            <li class="dash-item">
                <a href="<?php echo e(route('user.index')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-user"></i></span><span class="dash-mtext"><?php echo e(__('Company')); ?></span></a>
            </li>

            <li class="dash-item">
                <a href="<?php echo e(route('mobile_menu')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-phone"></i></span><span class="dash-mtext"><?php echo e(__('Mobile Menu')); ?></span></a>
            </li>




            <li class="dash-item ">
                <a href="<?php echo e(route('plans.index')); ?>" class="dash-link"><span class="dash-micon"><i
                            class=" ti ti-trophy"></i></span><span class="dash-mtext"><?php echo e(__('Plan')); ?></span></a>

            </li>

            <li class="dash-item ">
                <a href="<?php echo e(route('plan_request.index')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-arrow-down-right-circle"></i></span><span
                        class="dash-mtext"><?php echo e(__('Plan Request')); ?></span></a>

            </li>

            <?php if(Gate::check('manage coupon')): ?>
                <li class="dash-item ">
                    <a href="<?php echo e(route('coupons.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-gift"></i></span><span class="dash-mtext"><?php echo e(__('Coupon')); ?></span></a>

                </li>
            <?php endif; ?>

            <li class="dash-item ">
                <a href="<?php echo e(route('order.index')); ?>"
                    class="dash-link <?php echo e(request()->is('orders*') ? 'active' : ''); ?>"><span class="dash-micon"><i
                            class="ti ti-shopping-cart"></i></span><span
                        class="dash-mtext"><?php echo e(__('Order')); ?></span></a>

            </li>

            <li class="dash-item ">
                <a href="<?php echo e(route('role.index')); ?>"
                    class="dash-link <?php echo e(request()->is('roles*') ? 'active' : ''); ?>"><span class="dash-micon"><i
                            class="ti ti-shopping-cart"></i></span><span
                        class="dash-mtext"><?php echo e(__('Role')); ?></span></a>

            </li>

            <li
                class="dash-item <?php echo e(Request::route()->getName() == 'email_template.show' || Request::segment(1) == 'email_template_lang' || Request::route()->getName() == 'manageemail.lang' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('manage.email.language', [$emailTemplate->id, \Auth::user()->lang])); ?>"
                    class="dash-link"><span class="dash-micon"><i class="ti ti-template"></i></span><span
                        class="dash-mtext"><?php echo e(__('Email Templates')); ?></span></a>
            </li>
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
<?php /**PATH /var/www/html/resources/views/partial/Admin/superAdminMenu.blade.php ENDPATH**/ ?>