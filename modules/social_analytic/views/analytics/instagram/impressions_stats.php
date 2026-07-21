<h4 class="country-title"><?php echo _l('sa_impressions_metrics'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('impressions_metrics_note')?>"></i></h4>
<ul class="country-list">
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "impression" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('sa_impressions'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total_post = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND '. $where)));
        $sa_average_impressions_per_post = 0; 
        if($total_post > 0){
          $sa_average_impressions_per_post = round($total/$total_post); 
        }
       ?>
      <li>
          <span class="country-name"><?php echo _l('sa_average_impressions_per_post'); ?></span>
          <span class="country-count"><?php echo number_format($sa_average_impressions_per_post ?? 0); ?></span>
      </li>
</ul>
