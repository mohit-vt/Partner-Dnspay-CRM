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

   if($workspace->display_charts != ''){
      $display_charts = json_decode($workspace->display_charts, true);
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
<div class="mtop40">
   <h4 class="no-margin font-bold"><img src="<?php echo base_url('modules/social_analytic/assets/images/instagram_icon.png'); ?>" alt="" width="20" height="20"> <?php echo _l('instagram'); ?></h4>
   <hr>
   <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('top_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_top_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_top_stats == '1'){echo 'checked';} ?>  value="1" name="instagram_top_stats">
                <label class="onoffswitch-label" for="instagram_top_stats"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('audience_growth') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_audience_growth" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_audience_growth == '1'){echo 'checked';} ?>  value="1" name="instagram_audience_growth">
                <label class="onoffswitch-label" for="instagram_audience_growth"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('instagram_publishing_behavior') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_publishing_behavior" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_publishing_behavior == '1'){echo 'checked';} ?>  value="1" name="instagram_publishing_behavior">
                <label class="onoffswitch-label" for="instagram_publishing_behavior"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('post_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_post_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_post_stats == '1'){echo 'checked';} ?>  value="1" name="instagram_post_stats">
                <label class="onoffswitch-label" for="instagram_post_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('active_users_by_hours') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_active_users_by_hours" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_active_users_by_hours == '1'){echo 'checked';} ?>  value="1" name="instagram_active_users_by_hours">
                <label class="onoffswitch-label" for="instagram_active_users_by_hours"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('active_users_by_days') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_active_users_by_days" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_active_users_by_days == '1'){echo 'checked';} ?>  value="1" name="instagram_active_users_by_days">
                <label class="onoffswitch-label" for="instagram_active_users_by_days"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('instagram_impressions') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_impressions" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_impressions == '1'){echo 'checked';} ?>  value="1" name="instagram_impressions">
                <label class="onoffswitch-label" for="instagram_impressions"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('impressions_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_impressions_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_impressions_stats == '1'){echo 'checked';} ?>  value="1" name="instagram_impressions_stats">
                <label class="onoffswitch-label" for="instagram_impressions_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('engagement') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_engagement" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_engagement == '1'){echo 'checked';} ?>  value="1" name="instagram_engagement">
                <label class="onoffswitch-label" for="instagram_engagement"></label>
            </div>
        </div>
      </div>
    </div>
  
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('engagement_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_engagement_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_engagement_stats == '1'){echo 'checked';} ?>  value="1" name="instagram_engagement_stats">
                <label class="onoffswitch-label" for="instagram_engagement_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('stories_performance') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_stories_performance" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_stories_performance == '1'){echo 'checked';} ?>  value="1" name="instagram_stories_performance">
                <label class="onoffswitch-label" for="instagram_stories_performance"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('stories_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_stories_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_stories_stats == '1'){echo 'checked';} ?>  value="1" name="instagram_stories_stats">
                <label class="onoffswitch-label" for="instagram_stories_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('follower_by_gender') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_follower_by_gender" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_follower_by_gender == '1'){echo 'checked';} ?>  value="1" name="instagram_follower_by_gender">
                <label class="onoffswitch-label" for="instagram_follower_by_gender"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('follower_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_follower_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_follower_stats == '1'){echo 'checked';} ?>  value="1" name="instagram_follower_stats">
                <label class="onoffswitch-label" for="instagram_follower_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('follower_by_age') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="instagram_follower_by_age" data-perm-id="3" class="onoffswitch-checkbox" <?php if($instagram_follower_by_age == '1'){echo 'checked';} ?>  value="1" name="instagram_follower_by_age">
                <label class="onoffswitch-label" for="instagram_follower_by_age"></label>
            </div>
        </div>
      </div>
    </div>
    
  </div>
</div>