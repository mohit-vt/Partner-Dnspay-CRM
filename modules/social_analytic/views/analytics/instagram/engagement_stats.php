<h4 class="country-title"><?php echo _l('sa_engagement_metrics'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('engagement_metrics_note')?>"></i></h4>
<ul class="country-list">
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "engagement" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('sa_engagements'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total_post = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND '. $where)));
        $sa_average_engagements_per_post = 0; 
        if($total_post > 0){
          $sa_average_engagements_per_post = round($total/$total_post); 
        }
       ?>
      <li>
          <span class="country-name"><?php echo _l('sa_average_engagement_per_post'); ?></span>
          <span class="country-count"><?php echo e($sa_average_engagements_per_post); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "comment" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('sa_comments'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
</ul>
