<?php 
   $overview_top_stats = 1;
   $overview_engagement_vs_total_posts_published = 1;
   $overview_daily_post_impression_trend = 1;
   $overview_account_performance = 1;
   $overview_facebook_engagement = 1;
   $overview_instagram_engagement = 1;
   $overview_tiktok_engagement = 1;
   $overview_twitter_engagement = 1;
   $overview_youtube_engagement = 1;

   if($workspace->display_charts != ''){
      $display_charts = json_decode($workspace->display_charts, true);
      if(!isset($display_charts['overview_top_stats'])){
         $overview_top_stats = 0;
      }

      if(!isset($display_charts['overview_engagement_vs_total_posts_published'])){
         $overview_engagement_vs_total_posts_published = 0;
      }

      if(!isset($display_charts['overview_daily_post_impression_trend'])){
         $overview_daily_post_impression_trend = 0;
      }
      
      if(!isset($display_charts['overview_account_performance'])){
         $overview_account_performance = 0;
      }

      if(!isset($display_charts['overview_facebook_engagement'])){
         $overview_facebook_engagement = 0;
      }

      if(!isset($display_charts['overview_instagram_engagement'])){
         $overview_instagram_engagement = 0;
      }

      if(!isset($display_charts['overview_tiktok_engagement'])){
         $overview_tiktok_engagement = 0;
      }

      if(!isset($display_charts['overview_twitter_engagement'])){
         $overview_twitter_engagement = 0;
      }

      if(!isset($display_charts['overview_youtube_engagement'])){
         $overview_youtube_engagement = 0;
      }
   }


    ?>
<div class="mtop40">
   <h4 class="no-margin font-bold"><i class="fa fa-dashboard menu-icon"></i>  <?php echo _l('overview'); ?></h4>
   <hr>
   <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('top_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_top_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_top_stats == '1'){echo 'checked';} ?>  value="1" name="overview_top_stats">
                <label class="onoffswitch-label" for="overview_top_stats"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('engagement_vs_total_posts_published') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_engagement_vs_total_posts_published" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_engagement_vs_total_posts_published == '1'){echo 'checked';} ?>  value="1" name="overview_engagement_vs_total_posts_published">
                <label class="onoffswitch-label" for="overview_engagement_vs_total_posts_published"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('daily_post_impression_trend') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_daily_post_impression_trend" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_daily_post_impression_trend == '1'){echo 'checked';} ?>  value="1" name="overview_daily_post_impression_trend">
                <label class="onoffswitch-label" for="overview_daily_post_impression_trend"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('account_performance') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_account_performance" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_account_performance == '1'){echo 'checked';} ?>  value="1" name="overview_account_performance">
                <label class="onoffswitch-label" for="overview_account_performance"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('facebook_engagement') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_facebook_engagement" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_facebook_engagement == '1'){echo 'checked';} ?>  value="1" name="overview_facebook_engagement">
                <label class="onoffswitch-label" for="overview_facebook_engagement"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('instagram_engagement') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_instagram_engagement" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_instagram_engagement == '1'){echo 'checked';} ?>  value="1" name="overview_instagram_engagement">
                <label class="onoffswitch-label" for="overview_instagram_engagement"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('tiktok_engagement') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_tiktok_engagement" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_tiktok_engagement == '1'){echo 'checked';} ?>  value="1" name="overview_tiktok_engagement">
                <label class="onoffswitch-label" for="overview_tiktok_engagement"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('twitter_engagement') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_twitter_engagement" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_twitter_engagement == '1'){echo 'checked';} ?>  value="1" name="overview_twitter_engagement">
                <label class="onoffswitch-label" for="overview_twitter_engagement"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('youtube_engagement') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="overview_youtube_engagement" data-perm-id="3" class="onoffswitch-checkbox" <?php if($overview_youtube_engagement == '1'){echo 'checked';} ?>  value="1" name="overview_youtube_engagement">
                <label class="onoffswitch-label" for="overview_youtube_engagement"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

</div>