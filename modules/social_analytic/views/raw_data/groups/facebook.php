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
              <?php echo form_hidden('social', 'facebook');?>

              <a href="<?php echo admin_url('social_analytic/get_facebook_data/'.$account->id); ?>" class="btn btn-primary mbot15 mleft5 pull-right"><?php echo _l('sync_data'); ?></a>
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
              <th width="10%"><?php echo _l('sa_date'); ?></th>
              <th><?php echo _l('sa_fan'); ?></th>
              <th><?php echo _l('sa_impressions'); ?></th>
              <th><?php echo _l('sa_reactions'); ?></th>
              <th><?php echo _l('sa_clicks'); ?></th>
              <th><?php echo _l('sa_comments'); ?></th>
              <th><?php echo _l('sa_message_count'); ?></th>
              <th><?php echo _l('sa_number_of_posts'); ?></th>
              <th><?php echo _l('sa_shares'); ?></th>
              <th><?php echo _l('sa_active_users'); ?></th>
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
                ['id' => 'fan', 'name' => _l('sa_fan')], 
                ['id' => 'impression', 'name' => _l('sa_impression')], 
                ['id' => 'reaction', 'name' => _l('sa_reaction')], 
                ['id' => 'click', 'name' => _l('sa_click')], 
                ['id' => 'comment', 'name' => _l('sa_comment')], 
                ['id' => 'message_count', 'name' => _l('sa_message_count')], 
                ['id' => 'post', 'name' => _l('sa_post')], 
                ['id' => 'share', 'name' => _l('sa_share')], 
                ['id' => 'active_user', 'name' => _l('sa_active_user')], 
              ]; ?>
              <?php echo render_select('type', $type, array('id', 'name'), 'type', 'add', array(), array(), '', '', false) ?>
              <?php echo render_datetime_input('time', 'time') ?>
              <?php echo render_input('value', 'sa_value') ?>

              <div class="fan-sub-type hide">
                <?php $gender = [
                ['id' => 'female', 'name' => _l('female')], 
                ['id' => 'male', 'name' => _l('male')], 
              ]; ?>
              <?php echo render_select('gender', $gender, array('id', 'name'), 'gender', '', array(), array(), '', '') ?>

              <?php $age = [
                ['id' => '13_17', 'name' => _l('13_17')], 
                ['id' => '18_24', 'name' => _l('18_24')], 
                ['id' => '25_34', 'name' => _l('25_34')], 
                ['id' => '35_44', 'name' => _l('35_44')], 
                ['id' => '45_54', 'name' => _l('45_54')], 
                ['id' => '55_64', 'name' => _l('55_64')], 
                ['id' => '65_and_over', 'name' => _l('65_and_over')], 
              ]; ?>
              <?php echo render_select('age', $age, array('id', 'name'), 'age', '', array(), array(), '', '') ?>
              <div class="form-group select-placeholder">
                  <label for="language"
                      class="control-label"><?php echo _l('language'); ?>
                  </label>
                  <select name="language" id="language"
                      class="form-control selectpicker">
                      <option value=""></option>
                      <?php foreach ($this->app->get_available_languages() as $availableLanguage) {
                        ?>
                      <option value="<?php echo e(ucfirst($availableLanguage)); ?>">
                          <?php echo e(ucfirst($availableLanguage)); ?></option>
                      <?php
                      } ?>
                  </select>
              </div>

              <?php $countries       = get_all_countries();
                     $customer_default_country = get_option('customer_default_country');
                     echo render_select('country', $countries, [ 'short_name', [ 'short_name']], 'clients_country', '', []);
                     ?>
              </div>
              <div class="reaction-sub-type hide">
                <?php $reaction = [
                ['id' => 'like', 'name' => _l('like')], 
                ['id' => 'love', 'name' => _l('love')], 
                ['id' => 'wow', 'name' => _l('wow')], 
                ['id' => 'haha', 'name' => _l('haha')], 
                ['id' => 'sad', 'name' => _l('sad')], 
                ['id' => 'angry', 'name' => _l('angry')], 
              ]; ?>
              <?php echo render_select('reaction', $reaction, array('id', 'name'), 'reaction', '', array(), array(), '', '') ?>
              </div>

              <div class="post-sub-type hide">
                <?php $post_type = [
                ['id' => 'video', 'name' => _l('video')], 
                ['id' => 'photo', 'name' => _l('photo')], 
                ['id' => 'link', 'name' => _l('link')], 
              ]; ?>
              <?php echo render_select('post_type', $post_type, array('id', 'name'), 'post_type', '', array(), array(), '', '') ?>
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
