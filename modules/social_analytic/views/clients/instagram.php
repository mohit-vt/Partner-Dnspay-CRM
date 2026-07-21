<?php 
   $instagram_top_stats = 1;
   $instagram_audience_growth = 1;
   $instagram_publishing_behavior = 1;
   $instagram_post_stats = 1;
   $instagram_active_users_by_hours = 1;
   $instagram_active_users_by_days = 1;
   $instagram_impressions = 1;
   $instagram_impressions_stats = 1;
   $instagram_engagement = 1;
   $instagram_engagement_stats = 1;
   $instagram_stories_performance = 1;
   $instagram_stories_stats = 1;
   $instagram_follower_by_age = 1;
   $instagram_follower_by_gender = 1;
   $instagram_follower_stats = 1;

   if($base_workspace && $base_workspace->display_charts != ''){
      $display_charts = json_decode($base_workspace->display_charts, true);
      if(!isset($display_charts['instagram_top_stats'])){
         $instagram_top_stats = 0;
      }

      if(!isset($display_charts['instagram_audience_growth'])){
         $instagram_audience_growth = 0;
      }

      if(!isset($display_charts['instagram_publishing_behavior'])){
         $instagram_publishing_behavior = 0;
      }
      
      if(!isset($display_charts['instagram_post_stats'])){
         $instagram_post_stats = 0;
      }

      if(!isset($display_charts['instagram_active_users_by_hours'])){
         $instagram_active_users_by_hours = 0;
      }

      if(!isset($display_charts['instagram_active_users_by_days'])){
         $instagram_active_users_by_days = 0;
      }

      if(!isset($display_charts['instagram_impressions'])){
         $instagram_impressions = 0;
      }

      if(!isset($display_charts['instagram_impressions_stats'])){
         $instagram_impressions_stats = 0;
      }

      if(!isset($display_charts['instagram_engagement'])){
         $instagram_engagement = 0;
      }

      if(!isset($display_charts['instagram_engagement_stats'])){
         $instagram_engagement_stats = 0;
      }

      if(!isset($display_charts['instagram_stories_performance'])){
         $instagram_stories_performance = 0;
      }

      if(!isset($display_charts['instagram_stories_stats'])){
         $instagram_stories_stats = 0;
      }

      if(!isset($display_charts['instagram_follower_by_age'])){
         $instagram_follower_by_age = 0;
      }

      if(!isset($display_charts['instagram_follower_by_gender'])){
         $instagram_follower_by_gender = 0;
      }

      if(!isset($display_charts['instagram_follower_stats'])){
         $instagram_follower_stats = 0;
      }
   }


    ?>
<?php echo form_hidden('social', 'instagram');?>
<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
        <div class="row _buttons">
          <div class="col-md-6">
            <h4 class="no-margin text-bold ptop-15">
                  <img src="<?php echo base_url('modules/social_analytic/assets/images/instagram_icon.png'); ?>" alt="Girl in a jacket" width="20" height="20"> 
                   <?php echo new_html_entity_decode($title); ?></h4>
          </div>
          <div class="col-md-6">
            
          <div class="_hidden_inputs _filters _tasks_filters">
              <?php

              echo form_hidden('date_filter');
                
            

                $accounts = sa_get_contact_account_ids_by_base_workspace('instagram');
                foreach ($accounts as $account) {
                    echo form_hidden('account_id_' . $account['id'], $account['id']);
                }
              ?>
          </div>

          
          <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btn_filter">
              <i class="fa fa-filter" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu width300">
              <li class="filter-group" data-filter-group="group-date">
                  <a href="#" data-cview="last_30_days" onclick="dashboard_custom_view('last_30_days','<?php echo _l("last_30_days"); ?>','date_filter'); return false;">
                      <?php echo _l('last_30_days'); ?>
                  </a>
              </li>
                <li class="filter-group" data-filter-group="group-date">
                  <a href="#" data-cview="this_month" onclick="dashboard_custom_view('this_month','<?php echo _l("this_month"); ?>','date_filter'); return false;">
                      <?php echo _l('this_month'); ?>
                  </a>
              </li>
              <li class="filter-group" data-filter-group="group-date">
                  <a href="#" data-cview="this_quarter" onclick="dashboard_custom_view('this_quarter','<?php echo _l("this_quarter"); ?>','date_filter'); return false;">
                      <?php echo _l('this_quarter'); ?>
                  </a>
              </li>
              <li class="filter-group" data-filter-group="group-date">
                  <a href="#" data-cview="this_year" onclick="dashboard_custom_view('this_year','<?php echo _l("this_year"); ?>','date_filter'); return false;">
                      <?php echo _l('this_year'); ?>
                  </a>
              </li>
              <li class="filter-group" data-filter-group="group-date">
                  <a href="#" data-cview="last_month" onclick="dashboard_custom_view('last_month','<?php echo _l("last_month"); ?>','date_filter'); return false;">
                      <?php echo _l('last_month'); ?>
                  </a>
              </li>
              <li class="filter-group" data-filter-group="group-date">
                  <a href="#" data-cview="last_quarter" onclick="dashboard_custom_view('last_quarter','<?php echo _l("last_quarter"); ?>','date_filter'); return false;">
                      <?php echo _l('last_quarter'); ?>
                  </a>
              </li>
              <li class="filter-group" data-filter-group="group-date">
                  <a href="#" data-cview="last_year" onclick="dashboard_custom_view('last_year','<?php echo _l("last_year"); ?>','date_filter'); return false;">
                      <?php echo _l('last_year'); ?>
                  </a>
              </li>
              <div class="clearfix"></div>
              <li class="divider"></li>
              <li class="dropdown-submenu pull-left filter-group" data-filter-group="group-date">
                 <a href="#" tabindex="-1"><?php echo _l('year'); ?></a>
                 <?php 
                    $current_year = date('Y');
                    $y0 = (int)$current_year;
                    $y1 = (int)$current_year - 1;
                    $y2 = (int)$current_year - 2;
                    $y3 = (int)$current_year - 3;
                    $y4 = (int)$current_year - 4;
                 ?>
                 <ul class="dropdown-menu dropdown-menu-left">
                  <li class="filter-group" data-filter-group="group-date">
                      <a href="#" data-cview="year_<?php echo new_html_entity_decode($y0); ?>" onclick="dashboard_custom_view('year_<?php echo new_html_entity_decode($y0); ?>','<?php echo _l("financial_year").': '.$y0; ?>','date_filter'); return false;"><?php echo new_html_entity_decode($y0); ?></a>
                  </li>
                  <li class="filter-group" data-filter-group="group-date">
                      <a href="#" data-cview="year_<?php echo new_html_entity_decode($y1); ?>" onclick="dashboard_custom_view('year_<?php echo new_html_entity_decode($y1); ?>','<?php echo _l("financial_year").': '.$y1; ?>','date_filter'); return false;"><?php echo new_html_entity_decode($y1); ?></a>
                  </li>
                  <li class="filter-group" data-filter-group="group-date">
                      <a href="#" data-cview="year_<?php echo new_html_entity_decode($y2); ?>" onclick="dashboard_custom_view('year_<?php echo new_html_entity_decode($y2); ?>','<?php echo _l("financial_year").': '.$y2; ?>','date_filter'); return false;"><?php echo new_html_entity_decode($y2); ?></a>
                  </li>
                  <li class="filter-group" data-filter-group="group-date">
                      <a href="#" data-cview="year_<?php echo new_html_entity_decode($y3); ?>" onclick="dashboard_custom_view('year_<?php echo new_html_entity_decode($y3); ?>','<?php echo _l("financial_year").': '.$y3; ?>','date_filter'); return false;"><?php echo new_html_entity_decode($y3); ?></a>
                  </li>
                  <li class="filter-group" data-filter-group="group-date">
                      <a href="#" data-cview="year_<?php echo new_html_entity_decode($y4); ?>" onclick="dashboard_custom_view('year_<?php echo new_html_entity_decode($y4); ?>','<?php echo _l("financial_year").': '.$y4; ?>','date_filter'); return false;"><?php echo new_html_entity_decode($y4); ?></a>
                  </li>
                </ul>
              </li>
              <li class="divider"></li>
                <?php if (count($accounts)) { ?>
        <div class="clearfix"></div>
        <li class="divider"></li>
        <li class="dropdown-submenu pull-left active">
            <a href="#" tabindex="-1"><?php echo _l('filter_by_accounts'); ?></a>
            <ul class="dropdown-menu dropdown-menu-left">
                <?php foreach ($accounts as $account) { ?>
                <li class="active">
                    <a href="#" data-cview="account_id_<?php echo e($account['id']); ?>"
                        onclick="dashboard_custom_view(<?php echo e($account['id']); ?>,'<?php echo e($account['name']); ?>','account_id_<?php echo e($account['id']); ?>'); return false;"><?php echo e($account['name']); ?></a>
                </li>
                <?php } ?>
            </ul>
        </li>
        <?php } ?>
            </ul>
          </div>
          </div>
        </div>
        <hr class="mtop-5">
        <div class="clearfix"></div>
        <?php if($instagram_top_stats == 1){ ?>
        <div id="top_stats">
        </div>
        <?php } ?>
        <?php if($instagram_audience_growth == 1){ ?>
        <div class="row mtop40">
        <div class="col-md-12">
          <div id="audience_growth">
            </div>
          </div>
        </div>
        <?php } ?>
        <div class="row mtop40">
        <?php if($instagram_publishing_behavior == 1){ ?>
            <div class="col-md-8">
                <div id="publishing_behavior">
                </div>
            </div>
        <?php } ?>
        <?php if($instagram_post_stats == 1){ ?>
            <div class="col-md-4">
                <div id="post_stats">
                </div>
            </div>
        <?php } ?>
        </div>
       <div class="row mtop40">
        <?php if($instagram_active_users_by_hours == 1){ ?>
            <div class="col-md-6">
                <div id="active_users_by_hours">
                </div>
            </div>
        <?php } ?>
        <?php if($instagram_active_users_by_days == 1){ ?>
            <div class="col-md-6">
                <div id="active_users_by_days">
                </div>
            </div>
        <?php } ?>
        </div>
        <div class="row mtop40">
        <?php if($instagram_impressions == 1){ ?>
            <div class="col-md-8">
                <div id="instagram_impressions">
                </div>
            </div>
        <?php } ?>
        <?php if($instagram_impressions_stats == 1){ ?>
            <div class="col-md-4">
                <div id="impressions_stats">
                </div>
            </div>
        <?php } ?>
        </div>

        <div class="row mtop40">
        <?php if($instagram_engagement == 1){ ?>
            <div class="col-md-8">
                <div id="instagram_engagement">
                </div>
            </div>
        <?php } ?>
        <?php if($instagram_engagement_stats == 1){ ?>
            <div class="col-md-4">
                <div id="engagement_stats">
                </div>
            </div>
        <?php } ?>
        </div>
        <div class="row mtop40">
            <div class="col-md-8">
        <?php if($instagram_stories_performance == 1){ ?>
                <div id="instagram_stories_performance">
                </div>
        <?php } ?>
        <?php if($instagram_stories_performance== 1){ ?>
                <div id="stories_performance" class="mtop40">
                </div>
        <?php } ?>
            </div>
        <?php if($instagram_stories_stats == 1){ ?>
            <div class="col-md-4">
                <div id="stories_stats">
                </div>
            </div>
        <?php } ?>
        </div>
        <?php if($instagram_follower_by_age == 1){ ?>
        <div class="row mtop40">
          <div class="col-md-12">
            <div id="follower_by_age"></div>
          </div>
           </div>
        <?php } ?>
        <div class="row mtop40">
         
        <?php if($instagram_follower_by_gender == 1){ ?>
          <div class="col-md-6">
            <div id="follower_by_gender"></div>
          </div>
        <?php } ?>
        <?php if($instagram_follower_stats == 1){ ?>
          <div class="col-md-6">
            <div class="row" id="follower_stats"></div>
          </div>
        <?php } ?>
        </div>
    </div>
  </div>
</div>
<?php require('modules/social_analytic/assets/js/clients/instagram_js.php'); ?>
