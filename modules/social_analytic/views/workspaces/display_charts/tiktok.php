<?php 
   $tiktok_top_stats = 1;
   $tiktok_follower_chart = 1;
   $tiktok_video_views = 1;
   $tiktok_follow_stats = 1;
   $tiktok_engagement_rate = 1;
   $tiktok_posting_pattern_engagement_analysis = 1;

   if($workspace->display_charts != ''){
      $display_charts = json_decode($workspace->display_charts, true);
      if(!isset($display_charts['tiktok_top_stats'])){
         $tiktok_top_stats = 0;
      }

      if(!isset($display_charts['tiktok_follower_chart'])){
         $tiktok_follower_chart = 0;
      }

      if(!isset($display_charts['tiktok_video_views'])){
         $tiktok_video_views = 0;
      }
      
      if(!isset($display_charts['tiktok_follow_stats'])){
         $tiktok_follow_stats = 0;
      }

      if(!isset($display_charts['tiktok_engagement_rate'])){
         $tiktok_engagement_rate = 0;
      }

      if(!isset($display_charts['tiktok_posting_pattern_engagement_analysis'])){
         $tiktok_posting_pattern_engagement_analysis = 0;
      }
   }


    ?>
    <div class="mtop40">
   <h4 class="no-margin font-bold mtop40"><img src="<?php echo base_url('modules/social_analytic/assets/images/tiktok_icon.png'); ?>" alt="" width="20" height="20"> <?php echo _l('tiktok'); ?></h4>
   <hr>
   <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('top_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="tiktok_top_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($tiktok_top_stats == '1'){echo 'checked';} ?>  value="1" name="tiktok_top_stats">
                <label class="onoffswitch-label" for="tiktok_top_stats"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('follower_chart') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="tiktok_follower_chart" data-perm-id="3" class="onoffswitch-checkbox" <?php if($tiktok_follower_chart == '1'){echo 'checked';} ?>  value="1" name="tiktok_follower_chart">
                <label class="onoffswitch-label" for="tiktok_follower_chart"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('video_views_chart') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="tiktok_video_views" data-perm-id="3" class="onoffswitch-checkbox" <?php if($tiktok_video_views == '1'){echo 'checked';} ?>  value="1" name="tiktok_video_views">
                <label class="onoffswitch-label" for="tiktok_video_views"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('follow_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="tiktok_follow_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($tiktok_follow_stats == '1'){echo 'checked';} ?>  value="1" name="tiktok_follow_stats">
                <label class="onoffswitch-label" for="tiktok_follow_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('engagement_rate') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="tiktok_engagement_rate" data-perm-id="3" class="onoffswitch-checkbox" <?php if($tiktok_engagement_rate == '1'){echo 'checked';} ?>  value="1" name="tiktok_engagement_rate">
                <label class="onoffswitch-label" for="tiktok_engagement_rate"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('posting_pattern_engagement_analysis') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="tiktok_posting_pattern_engagement_analysis" data-perm-id="3" class="onoffswitch-checkbox" <?php if($tiktok_posting_pattern_engagement_analysis == '1'){echo 'checked';} ?>  value="1" name="tiktok_posting_pattern_engagement_analysis">
                <label class="onoffswitch-label" for="tiktok_posting_pattern_engagement_analysis"></label>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>