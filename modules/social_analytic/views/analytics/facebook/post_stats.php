<div class="col-md-4">
  <h4 class="country-title"><?php echo _l('post_type'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('post_type_note')?>"></i></h4>
    <ul class="country-list">
          <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND post_type = "video" AND '. $where))); ?>
          <li>
              <span class="country-name"><?php echo _l('sa_videos'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
          <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND post_type = "photo" AND '. $where))); ?>
          <li>
              <span class="country-name"><?php echo _l('sa_photos'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
          <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND post_type = "link" AND '. $where))); ?>
          <li>
              <span class="country-name"><?php echo _l('sa_links'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
    </ul>
</div>
<div class="col-md-4">
  <div id="post_by_type_chart"></div>
</div>
<div class="col-md-4">
    <div class="stat-card">
      <div class="icon"><i class="fas fa-paper-plane"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('total_posts'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_posts_note')?></span>
      </div>
    </div>
</div>