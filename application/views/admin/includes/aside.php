<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
#menu.sidebar {
    top: 57px !important;
    height: calc(100vh - 57px) !important;
    border-right: 0 !important;
}

.sidebar {
    background: #0f2747 !important;
    display: flex;
    flex-direction: column;
}

.sidebar-user-profile {
    margin: 20px 10px 22px 10px !important;
}

.sidebar-user-profile .profile {
    background: #3f4b63 !important;
    color: #ffffff !important;
    border: 0 !important;
    border-radius: 10px !important;
    padding: 14px !important;
}

.sidebar-user-profile .profile span {
    color: #ffffff !important;
}

#side-menu {
    background: #0f2747 !important;
    padding: 0 8px 20px 8px !important;
}

#side-menu > li {
    margin-bottom: 6px;
}

#side-menu > li > a {
    color: #d8e6f8 !important;
    padding: 12px 14px !important;
    border-radius: 8px !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    display: flex;
    align-items: center;
    gap: 12px;
}

#side-menu > li > a i {
    color: #8cb4ff !important;
    font-size: 17px !important;
    width: 20px;
    text-align: center;
}

#side-menu > li > a:hover,
#side-menu > li.active > a {
    background: #255ea8 !important;
    color: #ffffff !important;
    border-left: 4px solid #66b3ff;
}

#side-menu > li > a:hover i,
#side-menu > li.active > a i {
    color: #ffffff !important;
}

#side-menu .nav-second-level {
    background: #173353 !important;
    margin-top: 5px;
    padding: 6px 0 6px 18px !important;
    border-radius: 8px;
}

#side-menu .nav-second-level li a {
    color: #d8e6f8 !important;
    padding: 9px 14px !important;
    display: block;
    font-size: 14px;
    border-radius: 6px;
}

#side-menu .nav-second-level li a:hover {
    background: #255ea8 !important;
    color: #ffffff !important;
}

#side-menu .arrow {
    margin-left: auto;
    color: #ffffff !important;
}

.sidebar-logo {
    margin-top: auto;
    padding: 20px 15px;
    text-align: center;
}

.sidebar-logo img {
    width: 100%;
}
</style>

<aside id="menu" class="sidebar">

    <?php
    $current_segment = $this->uri->segment(2);
    $current_user    = get_staff();
    ?>

    <div class="dropdown sidebar-user-profile">
        <a href="#"
           class="dropdown-toggle profile tw-block"
           data-toggle="dropdown"
           aria-expanded="false">

            <span class="tw-inline-flex tw-items-center tw-gap-x-3">
                <?= staff_profile_image($current_user->staffid, ['img', 'img-responsive', 'staff-profile-image-small']); ?>

                <span>
                    <span class="tw-truncate tw-block tw-w-[140px] tw-font-semibold">
                        <?= get_staff_full_name(); ?>
                    </span>

                    <?php if (!empty($current_user->agent_id)) { ?>
                        <span class="tw-truncate tw-block tw-w-[140px] tw-font-semibold">
                            RID: <?= e($current_user->agent_id); ?>
                        </span>
                    <?php } ?>

                    <span class="tw-font-normal tw-truncate tw-block tw-w-[140px] tw-text-sm">
                        <?= e($current_user->email); ?>
                    </span>
                </span>
            </span>
        </a>

        <ul class="dropdown-menu tw-w-full">
            <li>
                <a href="<?= admin_url('profile'); ?>">
                    <?= _l('nav_my_profile'); ?>
                </a>
            </li>

            <li>
                <a href="<?= admin_url('staff/timesheets'); ?>">
                    <?= _l('my_timesheets'); ?>
                </a>
            </li>

            <li>
                <a href="<?= admin_url('staff/edit_profile'); ?>">
                    <?= _l('nav_edit_profile'); ?>
                </a>
            </li>

            <li>
                <a href="#" onclick="logout(); return false;">
                    <?= _l('nav_logout'); ?>
                </a>
            </li>
        </ul>
    </div>

    <ul class="nav metis-menu tw-mt-[15px]" id="side-menu">

        <?php hooks()->do_action('before_render_aside_menu'); ?>

        <li class="<?= empty($current_segment) || $current_segment == 'dashboard' ? 'active' : ''; ?>">
            <a href="<?= admin_url('dashboard'); ?>">
                <i class="fa fa-dashboard menu-icon"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>

        <li class="<?= $current_segment == 'onboarding' ? 'active' : ''; ?>">
            <a href="<?= admin_url('coming_soon/onboarding'); ?>">
                <i class="fa fa-user-plus menu-icon"></i>
                <span class="menu-text">Onboarding</span>
            </a>
        </li>

        <li class="<?= $current_segment == 'leads' ? 'active' : ''; ?>">
            <a href="<?= admin_url('leads'); ?>">
                <i class="fa fa-bullseye menu-icon"></i>
                <span class="menu-text">Leads</span>
            </a>
        </li>

        <li class="<?= $current_segment == 'pipeline_report' ? 'active' : ''; ?>">
            <a href="#" aria-expanded="false">
                <i class="fa fa-share-alt menu-icon"></i>
                <span class="menu-text">Merchant Referrals</span>
                <span class="fa arrow pleft5"></span>
            </a>
            <ul class="nav nav-second-level collapse" aria-expanded="false">
                <li><a href="<?= admin_url('pipeline_report/create'); ?>">New Referral</a></li>
                <li><a href="<?= admin_url('pipeline_report'); ?>">Referral Pipeline</a></li>
                <li><a href="<?= admin_url('pipeline_report/archived'); ?>">Archived Referrals</a></li>
            </ul>
        </li>

        <li class="<?= $current_segment == 'portfolio' ? 'active' : ''; ?>">
            <a href="#" aria-expanded="false">
                <i class="fa fa-briefcase menu-icon"></i>
                <span class="menu-text">Portfolio</span>
                <span class="fa arrow pleft5"></span>
            </a>
            <ul class="nav nav-second-level collapse" aria-expanded="false">
                <li><a href="<?= admin_url('pre_application/pre_application_detail'); ?>">Active Merchants</a></li>
                <li><a href="<?= admin_url('pre_application'); ?>">Pending Merchants</a></li>
                <li><a href="<?= admin_url('coming_soon/portfolio_attrition'); ?>">Attrition</a></li>
                <li><a href="<?= admin_url('coming_soon/portfolio_renewals'); ?>">Renewals</a></li>
            </ul>
        </li>

        <li class="<?= $current_segment == 'commissions' ? 'active' : ''; ?>">
            <a href="<?= admin_url('coming_soon/commissions'); ?>">
              <i class="fa-solid fa-bitcoin-sign"></i>
                <span class="menu-text">Commissions</span>
            </a>
        </li>

        <li class="<?= $current_segment == 'residuals' ? 'active' : ''; ?>">
            <a href="<?= admin_url('coming_soon/residuals'); ?>">
                <i class="fa fa-line-chart menu-icon"></i>
                <span class="menu-text">Residuals</span>
            </a>
        </li>

        <li class="<?= $current_segment == 'statements' ? 'active' : ''; ?>">
            <a href="<?= admin_url('coming_soon/statements'); ?>">
                <i class="fa-solid fa-file-invoice"></i>
                <span class="menu-text">Statements</span>
            </a>
        </li>

        <li class="<?= $current_segment == 'support' ? 'active' : ''; ?>">
            <a href="<?= admin_url('coming_soon/support_tickets'); ?>">
                <i class="fa fa-life-ring menu-icon"></i>
                <span class="menu-text">Support</span>
            </a>
        </li>

        <li class="<?= $current_segment == 'marketing_library' ? 'active' : ''; ?>">
            <a href="<?= admin_url('coming_soon/marketing_library'); ?>">
                <i class="fa fa-folder-open menu-icon"></i>
                <span class="menu-text">Marketing Library</span>
            </a>
        </li>

        <li class="<?= $current_segment == 'reports' ? 'active' : ''; ?>">
            <a href="<?= admin_url('reports'); ?>">
                <i class="fa fa-bar-chart menu-icon"></i>
                <span class="menu-text">Reports</span>
            </a>
        </li>



        <?php hooks()->do_action('after_render_aside_menu'); ?>

    </ul>

    <div class="sidebar-logo">
        <?php
        $custom_logo = get_option('admin_logo_custom');

        if (!empty($custom_logo)) {
            $logo = base_url($custom_logo) . '?v=' . time();
        } else {
            $logo = get_user_company_logo() . '?v=' . time();
        }
        ?>

        <?php if (empty($logo)) { ?>
            <a class="logo logo-text tw-text-2xl tw-font-semibold"
               href="<?= hooks()->apply_filters('admin_header_logo_href', admin_url()); ?>">
                <?= e(get_option('companyname')); ?>
            </a>
        <?php } else { ?>
            <a class="logo"
               href="<?= hooks()->apply_filters('admin_header_logo_href', admin_url()); ?>">
                <img src="<?= e($logo); ?>"
                     class="img-responsive"
                     alt="<?= e(get_option('companyname')); ?>" />
            </a>
        <?php } ?>
    </div>

</aside>