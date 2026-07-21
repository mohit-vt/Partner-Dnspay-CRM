<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<?php init_head();?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
              <div class="panel-body">
                <div class="border-right">
                  <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
                  <hr />
                </div>
            <div class="horizontal-tabs mb-5">
              <ul class="nav nav-tabs nav-tabs-horizontal mb-10">
              <?php echo form_hidden('type', $type);?>
              <?php
              foreach($tab as $gr){ ?> 
                <li<?php if($type == $gr){echo " class='active'"; } ?>>
                <a href="<?php echo admin_url('social_analytic/raw_data?type='.$gr); ?>" data-group="<?php echo html_entity_decode($gr); ?>">
                  <img src="<?php echo base_url('modules/social_analytic/assets/images/'.$gr.'_icon.png'); ?>" alt="Girl in a jacket" width="20" height="20">
                   <?php echo _l($gr); ?>
                  </a>
                </li>
                <?php 
              } ?>
              </ul>
              </div>
              <?php $this->load->view($tabs['view']); ?>
              <br>
              </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- box loading -->
<div id="box-loading"></div>
<?php init_tail();?>
<?php require 'modules/social_analytic/assets/js/raw_data/manage_js.php';?>
