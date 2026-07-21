<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
  	$app_id = get_option('sa_facebook_app_id');
  	$app_secret = get_option('sa_facebook_app_secret');
  	$graph_version = get_option('sa_facebook_graph_version');
?>

<?php echo form_open(admin_url('social_analytic/update_setting'),array('id'=>'general-settings-form')); ?>
    <?php echo form_hidden('type', $group);?>

	<?php echo render_input('sa_facebook_app_id', 'app_id', $app_id); ?>
	<?php echo render_input('sa_facebook_app_secret', 'app_secret', $app_secret, 'password'); ?>
	<?php echo render_input('sa_facebook_graph_version', 'graph_version', $graph_version); ?>
	<div class="col-md-12">
	  <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
	</div>
<?php echo form_close(); ?>
	           