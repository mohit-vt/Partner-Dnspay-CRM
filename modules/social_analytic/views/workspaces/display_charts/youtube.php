<?php 
   $youtube_top_stats = 1;
   $youtube_gained_lost_chart = 1;
   $youtube_daily_video_views_graph = 1;
   $youtube_estimated_minutes_watched = 1;
   $youtube_daily_video_views_chart = 1;
   $youtube_videos_published = 1;

   $youtube_like_vs_dislike_chart = 1;
   $youtube_comments_chart = 1;
   $youtube_shares_chart = 1;
   $youtube_subscribers_by_age = 1;
   $youtube_subscribers_by_gender = 1;

   $youtube_subscriber_stats = 1;
   $youtube_viewer_by_age = 1;
   $youtube_viewer_by_gender = 1;
   $youtube_viewer_by_device = 1;
   $youtube_viewer_stats = 1;

   if($workspace->display_charts != ''){
      $display_charts = json_decode($workspace->display_charts, true);
      if(!isset($display_charts['youtube_top_stats'])){
         $youtube_top_stats = 0;
      }

      if(!isset($display_charts['youtube_gained_lost_chart'])){
         $youtube_gained_lost_chart = 0;
      }

      if(!isset($display_charts['youtube_daily_video_views_graph'])){
         $youtube_daily_video_views_graph = 0;
      }
      
      if(!isset($display_charts['youtube_estimated_minutes_watched'])){
         $youtube_estimated_minutes_watched = 0;
      }

      if(!isset($display_charts['youtube_daily_video_views_chart'])){
         $youtube_daily_video_views_chart = 0;
      }

      if(!isset($display_charts['youtube_videos_published'])){
         $youtube_videos_published = 0;
      }



      if(!isset($display_charts['youtube_like_vs_dislike_chart'])){
         $youtube_like_vs_dislike_chart = 0;
      }

      if(!isset($display_charts['youtube_comments_chart'])){
         $youtube_comments_chart = 0;
      }

      if(!isset($display_charts['youtube_shares_chart'])){
         $youtube_shares_chart = 0;
      }

      if(!isset($display_charts['youtube_subscribers_by_age'])){
         $youtube_subscribers_by_age = 0;
      }

      if(!isset($display_charts['youtube_subscribers_by_gender'])){
         $youtube_subscribers_by_gender = 0;
      }


      if(!isset($display_charts['youtube_subscriber_stats'])){
         $youtube_subscriber_stats = 0;
      }

      if(!isset($display_charts['youtube_viewer_by_age'])){
         $youtube_viewer_by_age = 0;
      }

      if(!isset($display_charts['youtube_viewer_by_gender'])){
         $youtube_viewer_by_gender = 0;
      }

      if(!isset($display_charts['youtube_viewer_by_device'])){
         $youtube_viewer_by_device = 0;
      }

      if(!isset($display_charts['youtube_viewer_stats'])){
         $youtube_viewer_stats = 0;
      }
   }


    ?>
<div class="mtop40">
   <h4 class="no-margin font-bold mtop25"><img src="<?php echo base_url('modules/social_analytic/assets/images/youtube_icon.png'); ?>" alt="" width="20" height="20"> <?php echo _l('youtube'); ?></h4>
   <hr>
   <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('top_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_top_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_top_stats == '1'){echo 'checked';} ?>  value="1" name="youtube_top_stats">
                <label class="onoffswitch-label" for="youtube_top_stats"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('gained_lost_subscribers') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_gained_lost_chart" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_gained_lost_chart == '1'){echo 'checked';} ?>  value="1" name="youtube_gained_lost_chart">
                <label class="onoffswitch-label" for="youtube_gained_lost_chart"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('daily_video_views_graph') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_daily_video_views_graph" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_daily_video_views_graph == '1'){echo 'checked';} ?>  value="1" name="youtube_daily_video_views_graph">
                <label class="onoffswitch-label" for="youtube_daily_video_views_graph"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('estimated_minutes_watched') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_estimated_minutes_watched" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_estimated_minutes_watched == '1'){echo 'checked';} ?>  value="1" name="youtube_estimated_minutes_watched">
                <label class="onoffswitch-label" for="youtube_estimated_minutes_watched"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('daily_video_views_chart') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_daily_video_views_chart" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_daily_video_views_chart == '1'){echo 'checked';} ?>  value="1" name="youtube_daily_video_views_chart">
                <label class="onoffswitch-label" for="youtube_daily_video_views_chart"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('sa_videos_published') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_videos_published" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_videos_published == '1'){echo 'checked';} ?>  value="1" name="youtube_videos_published">
                <label class="onoffswitch-label" for="youtube_videos_published"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('like_vs_dislike_chart') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_like_vs_dislike_chart" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_like_vs_dislike_chart == '1'){echo 'checked';} ?>  value="1" name="youtube_like_vs_dislike_chart">
                <label class="onoffswitch-label" for="youtube_like_vs_dislike_chart"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('comments_chart') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_comments_chart" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_comments_chart == '1'){echo 'checked';} ?>  value="1" name="youtube_comments_chart">
                <label class="onoffswitch-label" for="youtube_comments_chart"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('shares_chart') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_shares_chart" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_shares_chart == '1'){echo 'checked';} ?>  value="1" name="youtube_shares_chart">
                <label class="onoffswitch-label" for="youtube_shares_chart"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('subscribers_by_age') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_subscribers_by_age" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_subscribers_by_age == '1'){echo 'checked';} ?>  value="1" name="youtube_subscribers_by_age">
                <label class="onoffswitch-label" for="youtube_subscribers_by_age"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('subscribers_by_gender') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_subscribers_by_gender" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_subscribers_by_gender == '1'){echo 'checked';} ?>  value="1" name="youtube_subscribers_by_gender">
                <label class="onoffswitch-label" for="youtube_subscribers_by_gender"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('subscriber_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_subscriber_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_subscriber_stats == '1'){echo 'checked';} ?>  value="1" name="youtube_subscriber_stats">
                <label class="onoffswitch-label" for="youtube_subscriber_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('viewer_by_age') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_viewer_by_age" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_viewer_by_age == '1'){echo 'checked';} ?>  value="1" name="youtube_viewer_by_age">
                <label class="onoffswitch-label" for="youtube_viewer_by_age"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('viewer_by_gender') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_viewer_by_gender" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_viewer_by_gender == '1'){echo 'checked';} ?>  value="1" name="youtube_viewer_by_gender">
                <label class="onoffswitch-label" for="youtube_viewer_by_gender"></label>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('viewer_by_device') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_viewer_by_device" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_viewer_by_device == '1'){echo 'checked';} ?>  value="1" name="youtube_viewer_by_device">
                <label class="onoffswitch-label" for="youtube_viewer_by_device"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('viewer_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="youtube_viewer_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($youtube_viewer_stats == '1'){echo 'checked';} ?>  value="1" name="youtube_viewer_stats">
                <label class="onoffswitch-label" for="youtube_viewer_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>