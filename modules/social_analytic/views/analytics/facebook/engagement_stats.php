<div class="col-md-4">
  <h4 class="country-title"><?php echo _l('engagement_rate_by_day'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('engagement_rate_by_day_note')?>"></i></h4>
    <ul class="country-list">
          <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "click" AND '. $where))); ?>
          <li>
              <span class="country-name"><?php echo _l('sa_clicks'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
          <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "reaction" AND '. $where))); ?>
          <li>
              <span class="country-name"><?php echo _l('sa_reactions'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
          <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "comment" AND '. $where))); ?>
          <li>
              <span class="country-name"><?php echo _l('sa_comments'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
          <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "share" AND '. $where))); ?>
          <li>
              <span class="country-name"><?php echo _l('sa_shares'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
    </ul>
</div>
<div class="col-md-4">
  <div id="engagement_rate_pie_chart"></div>
</div>
<div class="col-md-4">
  <div class="stat-card">
      <div class="icon"><i class="fas fa-comments"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('total_engagement'); ?></h3>
        <?php
            $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('(type = "click" or type = "reaction" or type = "comment" or type = "share") AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_engagements_note')?></span>
      </div>
    </div>


</div>