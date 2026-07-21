<div class="facebook-dashboard-stats">
    <div class="stat-card">
        <?php
              $total_post = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND '. $where)));
              ?>
          <?php
          $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('(type = "click" or type = "reaction" or type = "comment" or type = "share") AND '. $where)));
          ?>
        <div class="stat-title"><?php echo _l('total_engagement'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('sa_fans')?>"></i></div>
        <div class="stat-value"><?php echo number_format($total ?? 0); ?></div>
        <div class="stat-description hide"><?php echo _l('total_fans'); ?></div>
    </div>
    <div class="stat-card">
        <?php 
          $sa_average_engagement_per_post = 0; 
          if($total_post > 0){
            $sa_average_engagement_per_post = round($total/$total_post); 
          }
         ?>
         
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('(type = "reaction" OR type = "like" OR type = "dislike") AND '. $where)));
              ?>
        <div class="stat-title"><?php echo _l('sa_average_engagement_per_post'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('sa_impressions')?>"></i></div>
        <div class="stat-value"><?php echo number_format($sa_average_engagement_per_post ?? 0); ?></div>
        <div class="stat-description hide"><?php echo _l('total_impressions'); ?></div>
    </div>
</div>
