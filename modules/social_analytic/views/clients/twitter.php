<?php 
   $twitter_top_stats = 1;
   $twitter_published_posts_with_engagement = 1;
   $twitter_engagement_rate = 1;
   $twitter_engagement_stats = 1;
   $twitter_post_density_daily = 1;
   $twitter_audience_growth = 1;
   $twitter_follow_stats = 1;
   $twitter_awareness_through_mention = 1;

   if($base_workspace && $base_workspace->display_charts != ''){
      $display_charts = json_decode($base_workspace->display_charts, true);
      if(!isset($display_charts['twitter_top_stats'])){
         $twitter_top_stats = 0;
      }

      if(!isset($display_charts['twitter_published_posts_with_engagement'])){
         $twitter_published_posts_with_engagement = 0;
      }

      if(!isset($display_charts['twitter_engagement_rate'])){
         $twitter_engagement_rate = 0;
      }
      
      if(!isset($display_charts['twitter_engagement_stats'])){
         $twitter_engagement_stats = 0;
      }

      if(!isset($display_charts['twitter_post_density_daily'])){
         $twitter_post_density_daily = 0;
      }

      if(!isset($display_charts['twitter_audience_growth'])){
         $twitter_audience_growth = 0;
      }

      if(!isset($display_charts['twitter_follow_stats'])){
         $twitter_follow_stats = 0;
      }

      if(!isset($display_charts['twitter_awareness_through_mention'])){
         $twitter_awareness_through_mention = 0;
      }
   }


    ?>

<?php echo form_hidden('social', 'twitter');?>
<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
        <div class="row _buttons">
          <div class="col-md-6">
            <h4 class="no-margin text-bold ptop-15">
                  <img src="<?php echo base_url('modules/social_analytic/assets/images/twitter_icon.png'); ?>" alt="Girl in a jacket" width="20" height="20"> 
                   <?php echo new_html_entity_decode($title); ?></h4>
          </div>
          <div class="col-md-6">
            
          <div class="_hidden_inputs _filters _tasks_filters">
              <?php

              echo form_hidden('date_filter');
                
            

                $accounts = sa_get_contact_account_ids_by_base_workspace('twitter');
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
        <?php if($twitter_top_stats == 1){ ?>
        <div id="top_stats">
        </div>
        <?php } ?>
        <?php if($twitter_published_posts_with_engagement == 1){ ?>
        <div class="row mtop40">
            <div class="col-md-12">
                <div id="published_posts_with_engagement">
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if($twitter_engagement_rate == 1){ ?>
        <div class="row mtop40">
          <div class="col-md-12">
            <div id="engagement_rate"></div>
          </div>
        </div>
        <?php } ?>
        <?php if($twitter_engagement_stats == 1){ ?>
        <div class="row mtop40" id="engagement_stats">
        </div>
        <?php } ?>
        <?php if($twitter_post_density_daily == 1){ ?>
        <div class="row mtop40">
          <div class="col-md-12">
            <div id="post_density_daily"></div>
          </div>
        </div>
        <?php } ?>
        <?php if($twitter_audience_growth == 1){ ?>
        <div class="row mtop40">
            <div class="col-md-12">
                <div id="twitter_audience_growth">
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if($twitter_follow_stats == 1){ ?>
        <div class="row mtop40" id="follow_stats">
        </div>
        <?php } ?>
        <?php if($twitter_awareness_through_mention == 1){ ?>
        <div class="row mtop40">
            <div class="col-md-12">
                <div id="awareness_through_mention">
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
  </div>
</div>
<?php require('modules/social_analytic/assets/js/clients/twitter_js.php'); ?>
