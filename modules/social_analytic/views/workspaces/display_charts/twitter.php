<?php 
   $twitter_top_stats = 1;
   $twitter_published_posts_with_engagement = 1;
   $twitter_engagement_rate = 1;
   $twitter_engagement_stats = 1;
   $twitter_post_density_daily = 1;
   $twitter_audience_growth = 1;
   $twitter_follow_stats = 1;
   $twitter_awareness_through_mention = 1;

   if($workspace->display_charts != ''){
      $display_charts = json_decode($workspace->display_charts, true);
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
<div class="mtop40">
   <h4 class="no-margin font-bold"><img src="<?php echo base_url('modules/social_analytic/assets/images/twitter_icon.png'); ?>" alt="" width="20" height="20"> <?php echo _l('twitter'); ?></h4>
   <hr>
   <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('top_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="twitter_top_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($twitter_top_stats == '1'){echo 'checked';} ?>  value="1" name="twitter_top_stats">
                <label class="onoffswitch-label" for="twitter_top_stats"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('published_posts_with_engagement') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="twitter_published_posts_with_engagement" data-perm-id="3" class="onoffswitch-checkbox" <?php if($twitter_published_posts_with_engagement == '1'){echo 'checked';} ?>  value="1" name="twitter_published_posts_with_engagement">
                <label class="onoffswitch-label" for="twitter_published_posts_with_engagement"></label>
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
                <input type="checkbox" id="twitter_engagement_rate" data-perm-id="3" class="onoffswitch-checkbox" <?php if($twitter_engagement_rate == '1'){echo 'checked';} ?>  value="1" name="twitter_engagement_rate">
                <label class="onoffswitch-label" for="twitter_engagement_rate"></label>
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
                <input type="checkbox" id="twitter_engagement_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($twitter_engagement_stats == '1'){echo 'checked';} ?>  value="1" name="twitter_engagement_stats">
                <label class="onoffswitch-label" for="twitter_engagement_stats"></label>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('post_density_daily') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="twitter_post_density_daily" data-perm-id="3" class="onoffswitch-checkbox" <?php if($twitter_post_density_daily == '1'){echo 'checked';} ?>  value="1" name="twitter_post_density_daily">
                <label class="onoffswitch-label" for="twitter_post_density_daily"></label>
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
                <input type="checkbox" id="twitter_audience_growth" data-perm-id="3" class="onoffswitch-checkbox" <?php if($twitter_audience_growth == '1'){echo 'checked';} ?>  value="1" name="twitter_audience_growth">
                <label class="onoffswitch-label" for="twitter_audience_growth"></label>
            </div>
        </div>
      </div>
    </div>
    
  </div>

  <div class="row mtop5">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('follow_stats') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="twitter_follow_stats" data-perm-id="3" class="onoffswitch-checkbox" <?php if($twitter_follow_stats == '1'){echo 'checked';} ?>  value="1" name="twitter_follow_stats">
                <label class="onoffswitch-label" for="twitter_follow_stats"></label>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-8 border-right">
          <h5 class="title mbot5"><?php echo _l('sa_awareness_through_mentions') ?></h5>
        </div>
        <div class="col-md-4 mtop5">
            <div class="onoffswitch">
                <input type="checkbox" id="twitter_awareness_through_mention" data-perm-id="3" class="onoffswitch-checkbox" <?php if($twitter_awareness_through_mention == '1'){echo 'checked';} ?>  value="1" name="twitter_awareness_through_mention">
                <label class="onoffswitch-label" for="twitter_awareness_through_mention"></label>
            </div>
        </div>
      </div>
    </div>
    
  </div>

</div>