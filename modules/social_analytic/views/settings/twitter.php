<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
  	$client_id = get_option('sa_twitter_client_id');
  	$client_secret = get_option('sa_twitter_client_secret');
?>

<?php echo form_open(admin_url('social_analytic/update_setting'),array('id'=>'general-settings-form')); ?>
    <?php echo form_hidden('type', $group);?>
	<?php echo render_input('sa_twitter_client_id', 'client_id', $client_id); ?>
	<?php echo render_input('sa_twitter_client_secret', 'client_secret', $client_secret, 'password'); ?>
	<div class="col-md-12">
	  <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
	</div>
<?php echo form_close(); ?>
	           