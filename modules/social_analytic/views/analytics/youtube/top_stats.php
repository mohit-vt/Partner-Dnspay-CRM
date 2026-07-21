<div class="instagram-dashboard-stats">
    <div class="stat-card">
      <div class="icon"><i class="fas fa-bell"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_subscribers'); ?></h3>
        <?php
                $total = 0;
                foreach ($account_ids as $key => $account_id) {
                    $this->db->where('type = "subscriber" AND '. $where);
                    $this->db->where('account_id', $account_id);
                    $this->db->where('subscriber', '');
                    $this->db->order_by('time', 'desc');
                    $subscriber = $this->db->get(db_prefix() . 'sa_analytics')->row();
                    if($subscriber && is_numeric($subscriber->value)){
                        $total += $subscriber->value;
                    }
                }
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('youtube_total_subscribers_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-thumbs-up"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_likes'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "like" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('youtube_total_likes_note')?></span>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="icon"><i class="fas fa-thumbs-down"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_dislikes'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "dislike" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('youtube_total_dislikes_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-eye"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_views'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "view" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('youtube_total_views_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-file-video"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_videos'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "video" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('youtube_total_videos_note')?></span>
      </div>
    </div>
</div>
