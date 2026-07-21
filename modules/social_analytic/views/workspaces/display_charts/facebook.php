<?php 
   $facebook_top_stats = 1;
   $facebook_audience_growth = 1;
   $facebook_published_posts_with_engagement = 1;
   $facebook_post_rate = 1;
   $facebook_post_stats = 1;
   $facebook_post_density_daily = 1;
   $facebook_fan_by_age = 1;
   $facebook_fan_by_gender = 1;
   $facebook_fan_stats = 1;
   $facebook_active_users_by_day = 1;
   $facebook_engagement_rate = 1;
   $facebook_engagement_stats = 1;
   $facebook_reactions_overview = 1;
   $facebook_engagement_by_day_time = 1;

   if($workspace->display_charts != ''){
      $display_charts = json_decode($workspace->display_charts, true);
      if(!isset($display_charts['facebook_top_stats'])){
         $facebook_top_stats = 0;
      }

      if(!isset($display_charts['facebook_audience_growth'])){
         $facebook_audience_growth = 0;
      }

      if(!isset($display_charts['facebook_published_posts_with_engagement'])){
         $facebook_published_posts_with_engagement = 0;
      }
      
      if(!isset($display_charts['facebook_post_rate'])){
         $facebook_post_rate = 0;
      }

      if(!isset($display_charts['facebook_post_stats'])){
         $facebook_post_stats = 0;
      }

      if(!isset($display_charts['facebook_post_density_daily'])){
         $facebook_post_density_daily = 0;
      }

      if(!isset($display_charts['facebook_fan_by_age'])){
         $facebook_fan_by_age = 0;
      }

      if(!isset($display_charts['facebook_fan_by_gender'])){
         $facebook_fan_by_gender = 0;
      }

      if(!isset($display_charts['facebook_fan_stats'])){
         $facebook_fan_stats = 0;
      }

      if(!isset($display_charts['facebook_active_users_by_day'])){
         $facebook_active_users_by_day = 0;
      }

      if(!isset($display_charts['facebook_engagement_rate'])){
         $facebook_engagement_rate = 0;
      }

      if(!isset($display_charts['facebook_engagement_stats'])){
         $facebook_engagement_stats = 0;
      }

      if(!isset($display_charts['facebook_reactions_overview'])){
         $facebook_reactions_overview = 0;
      }

      if(!isset($display_charts['facebook_engagement_by_day_time'])){
         $facebook_engagement_by_day_time = 0;
      }
   }


    ?>
<div class="mtop40">
   <h4 class="no-margin font-bold"><img src="<?php echo base_url('modules/social_analytic/assets/images/facebook_icon.png'); ?>" alt="" width="20" height="20"> <?php echo _l('facebook'); ?></h4>
   <hr>
   <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('top_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_top_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_top_stats == '1'){echo 'checked';} ?>  value="1" name="facebook_top_stats">
                <label class="onoffswitch-label" for="facebook_top_stats"></label>
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
                <input type="checkbox" id="facebook_audience_growth" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_audience_growth == '1'){echo 'checked';} ?>  value="1" name="facebook_audience_growth">
                <label class="onoffswitch-label" for="facebook_audience_growth"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('published_posts_with_engagement') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_published_posts_with_engagement" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_published_posts_with_engagement == '1'){echo 'checked';} ?>  value="1" name="facebook_published_posts_with_engagement">
                <label class="onoffswitch-label" for="facebook_published_posts_with_engagement"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('post_rate') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_post_rate" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_post_rate == '1'){echo 'checked';} ?>  value="1" name="facebook_post_rate">
                <label class="onoffswitch-label" for="facebook_post_rate"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('post_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_post_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_post_stats == '1'){echo 'checked';} ?>  value="1" name="facebook_post_stats">
                <label class="onoffswitch-label" for="facebook_post_stats"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('post_density_daily') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_post_density_daily" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_post_density_daily == '1'){echo 'checked';} ?>  value="1" name="facebook_post_density_daily">
                <label class="onoffswitch-label" for="facebook_post_density_daily"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('fan_by_age') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_fan_by_age" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_fan_by_age == '1'){echo 'checked';} ?>  value="1" name="facebook_fan_by_age">
                <label class="onoffswitch-label" for="facebook_fan_by_age"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('fan_by_gender') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_fan_by_gender" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_fan_by_gender == '1'){echo 'checked';} ?>  value="1" name="facebook_fan_by_gender">
                <label class="onoffswitch-label" for="facebook_fan_by_gender"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('fan_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_fan_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_fan_stats == '1'){echo 'checked';} ?>  value="1" name="facebook_fan_stats">
                <label class="onoffswitch-label" for="facebook_fan_stats"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('active_users_by_day') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_active_users_by_day" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_active_users_by_day == '1'){echo 'checked';} ?>  value="1" name="facebook_active_users_by_day">
                <label class="onoffswitch-label" for="facebook_active_users_by_day"></label>
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
                <input type="checkbox" id="facebook_engagement_rate" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_engagement_rate == '1'){echo 'checked';} ?>  value="1" name="facebook_engagement_rate">
                <label class="onoffswitch-label" for="facebook_engagement_rate"></label>
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
                <input type="checkbox" id="facebook_engagement_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_engagement_stats == '1'){echo 'checked';} ?>  value="1" name="facebook_engagement_stats">
                <label class="onoffswitch-label" for="facebook_engagement_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('reactions_overview') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_reactions_overview" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_reactions_overview == '1'){echo 'checked';} ?>  value="1" name="facebook_reactions_overview">
                <label class="onoffswitch-label" for="facebook_reactions_overview"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('engagement_by_day_time') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="facebook_engagement_by_day_time" data-perm-id="3" class="onoffswitch-checkbox" <?php if($facebook_engagement_by_day_time == '1'){echo 'checked';} ?>  value="1" name="facebook_engagement_by_day_time">
                <label class="onoffswitch-label" for="facebook_engagement_by_day_time"></label>
            </div>
        </div>
      </div>
    </div>
  </div>
  </div>