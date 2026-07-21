<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="no-margin font-bold"><img src="<?php echo base_url('modules/social_analytic/assets/images/'. $account->type .'_icon.png'); ?>" alt="" width="20" height="20"> <?php echo _l($account->type); ?>: <?php echo _l($account->name); ?></h4>
            </div>
            <div class="col-md-6">
              <?php echo form_hidden('account', $account->id);?>
              <?php echo form_hidden('lock', 1); ?>
              <?php echo form_hidden('social', 'twitter');?>

              <a href="<?php echo admin_url('social_analytic/get_twitter_data/'.$account->id); ?>" class="btn btn-primary mbot15 mleft5 pull-right"><?php echo _l('sync_data'); ?></a>
              <a href="<?php echo admin_url('social_analytic/import_xlsx_analytics/'.$account->id); ?>" class="btn btn-success mbot15 mleft5 pull-right"><?php echo _l('import'); ?></a>
              <a href="#" onclick="add_data(); return false;" class="btn btn-primary mbot15 pull-right mleft5"><?php echo _l('add_raw_data'); ?></a>
              <a href="#" onclick="unlock(); return false;" class="btn btn-warning mbot15 pull-right unlock-btn mleft5"><?php echo _l('unlock'); ?></a>
              <a href="#" onclick="lock(); return false;" class="btn btn-warning mbot15 pull-right lock-btn hide mleft5"><?php echo _l('lock'); ?></a>
            </div>
          </div>
          <div class="row">
          <div class="col-md-3">
            <?php echo render_date_input('from_date','from_date'); ?>
          </div>
          <div class="col-md-3">
            <?php echo render_date_input('to_date','to_date'); ?>
          </div>
        </div>
          <table class="table table-workspaces">
            <thead>
              <th><?php echo _l('sa_date'); ?></th>
              <th><?php echo _l('sa_posts'); ?></th>
              <th><?php echo _l('sa_following'); ?></th>
              <th><?php echo _l('sa_engagements'); ?></th>
              <th><?php echo _l('sa_followers'); ?></th>
              <th><?php echo _l('sa_awareness_through_mentions'); ?></th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- box loading -->
<div id="box-loadding"></div>


<div class="modal fade" id="workspace-modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo _l('edit')?></h4>
         </div>
         <?php echo form_open(admin_url('social_analytic/raw_data_update'),array('id'=>'workspace-form'));?>
         <?php echo form_hidden('id', $account->id); ?>
      
         <div class="modal-body">
              <?php $edit_type = [
                ['id' => 'overwrite', 'name' => _l('overwrite')],
                ['id' => 'add', 'name' => _l('add')], 
              ]; ?>
              <?php echo render_select('edit_type', $edit_type, array('id', 'name'), 'edit_type', 'overwrite', array(), array(), '', '', false) ?>
              <?php $type = [
                ['id' => 'post', 'name' => _l('sa_post')], 
                ['id' => 'following', 'name' => _l('sa_following')], 
                ['id' => 'engagement', 'name' => _l('sa_engagement')], 
                ['id' => 'follower', 'name' => _l('sa_follower')], 
                ['id' => 'awareness_through_mention', 'name' => _l('sa_awareness_through_mention')], 
              ]; ?>
              <?php echo render_select('type', $type, array('id', 'name'), 'type', 'add', array(), array(), '', '', false) ?>
              <?php echo render_datetime_input('time', 'time') ?>
              <?php echo render_input('value', 'sa_value') ?>

              <div class="follower-sub-type hide">
                <?php $gender = [
                ['id' => 'female', 'name' => _l('female')], 
                ['id' => 'male', 'name' => _l('male')], 
              ]; ?>
              <?php echo render_select('gender', $gender, array('id', 'name'), 'gender', '', array(), array(), '', '') ?>

              <?php $countries       = get_all_countries();
                     $customer_default_country = get_option('customer_default_country');
                     echo render_select('country', $countries, [ 'short_name', [ 'short_name']], 'clients_country', '', []);
                     ?>
              </div>

              <div class="engagement-sub-type hide">
                <?php $engagement = [
                ['id' => 'like', 'name' => _l('sa_like')], 
                ['id' => 'retweet', 'name' => _l('sa_retweet')], 
              ]; ?>
              <?php echo render_select('engagement', $engagement, array('id', 'name'), 'engagement', '', array(), array(), '', '') ?>
              </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-primary btn-submit"><?php echo _l('submit'); ?></button>
         </div>
         <?php echo form_close(); ?>  
      </div>
   </div>
</div>
<?php init_tail(); ?>
<?php require 'modules/social_analytic/assets/js/raw_data/raw_data_detail_js.php';?>
