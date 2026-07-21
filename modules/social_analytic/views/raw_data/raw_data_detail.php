<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
         <?php echo form_open_multipart(admin_url('social_analytic/social_account'),array('id'=>'workspace-form'));?>
          <?php echo form_hidden('id'); ?>
          
            <button type="submit" class="btn btn-primary btn-submit"><?php echo _l('submit'); ?></button>
            <?php echo form_close(); ?>  
          
        </div>
      </div>
    </div>
  </div>
</div>
<!-- box loading -->
<div id="box-loadding"></div>
<?php init_tail(); ?>
