<h4 class="country-title"><?php echo _l('sa_stories_metrics'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('stories_metrics_note')?>"></i></h4>
<ul class="country-list">
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "published_stories" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('sa_total_published_stories'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "stories_performance" AND stories_performance = "story_replies" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('story_replies'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "stories_performance" AND stories_performance = "story_tap_backs" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('story_tap_backs'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "stories_performance" AND stories_performance = "story_taps_forward" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('story_taps_forward'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "stories_performance" AND stories_performance = "story_exits" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('story_exits'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "stories_performance" AND stories_performance = "story_reach" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('story_reach'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "stories_performance" AND stories_performance = "story_impressions" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('story_impressions'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total_post = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "published_stories" AND '. $where)));
        $sa_average_story_impressions = 0; 
        if($total_post > 0){
          $sa_average_story_impressions = round($total/$total_post); 
        }
       ?>
      <li>
          <span class="country-name"><?php echo _l('sa_average_story_impressions'); ?></span>
          <span class="country-count"><?php echo number_format($sa_average_story_impressions ?? 0); ?></span>
      </li>
</ul>





