<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>

<!DOCTYPE html>
<html lang="<?= e($locale); ?>"
    dir="<?= ($isRTL == 'true') ? 'rtl' : 'ltr' ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>
        <?= $title ?? get_option('companyname'); ?>
    </title>

    <?php
    $CI = &get_instance();
    $staff_id = get_staff_user_id();
    $CI->load->model('staff_model');
    $role = strtolower(trim($CI->staff_model->get($staff_id)->role ?? '')); 

    $default_favicon = 'assets/images/favicon.png';
    $role_favicon = get_option('favicon_custom_' . $role);
    $favicon = $role_favicon ?: $default_favicon;

    $cache_bust = get_option('favicon_cache_bust_' . $role) ?: time();
   
	?>
    <link rel="icon" type="image/png" href="<?= base_url($favicon) . '?v=' . $cache_bust; ?>">
    <link rel="shortcut icon" href="<?= base_url($favicon) . '?v=' . $cache_bust; ?>">
  

    <?= app_compile_css(); ?>
    <?php render_admin_js_variables(); ?>

    <script>
        var totalUnreadNotifications = <?= e($current_user->total_unread_notifications); ?> ,
            proposalsTemplates = <?= json_encode(get_proposal_templates()); ?> ,
            contractsTemplates = <?= json_encode(get_contract_templates()); ?> ,
            billingAndShippingFields = ['billing_street', 'billing_city', 'billing_state', 'billing_zip',
                'billing_country',
                'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'
            ],
            isRTL = '<?= e($isRTL); ?>',
            taskid, taskTrackingStatsData, taskAttachmentDropzone, taskCommentAttachmentDropzone, newsFeedDropzone,
            expensePreviewDropzone, taskTrackingChart, cfh_popover_templates = {},
            _table_api;
    </script>
    <?php app_admin_head(); ?>
</head>

<body <?= admin_body_class($bodyclass ?? ''); ?>>
    <?php hooks()->do_action('after_body_start'); ?>