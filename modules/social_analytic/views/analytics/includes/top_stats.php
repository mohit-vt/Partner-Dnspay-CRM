<div class="facebook-dashboard-stats stats-container">
    <div class="stat-card">
      <div class="icon"><i class="fas fa-paper-plane"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_posts'); ?></h3>
        <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('(type = "post" OR type = "video") AND '. $where))); ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_posts_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-frown"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_reactions'); ?></h3>
        <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('(type = "reaction" OR type = "like" OR type = "dislike" OR (type = "engagement" AND engagement = "like")) AND '. $where))); ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_reactions_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-retweet"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_shares'); ?></h3>
        <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('(type = "share" OR (type = "engagement" AND (engagement = "share" OR engagement = "retweet"))) AND '. $where))); ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_shares_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-comments"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_comments'); ?></h3>
        <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('(type = "comment"  OR (type = "engagement" AND engagement = "comment")) AND '. $where))); ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_comments_note')?></span>
      </div>
    </div>
</div>
