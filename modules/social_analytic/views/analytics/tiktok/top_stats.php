<div class="facebook-dashboard-stats">
    <div class="stat-card">
      <div class="icon"><i class="fas fa-users"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_followers'); ?></h3>
              <?php
                $total = 0;
                foreach ($account_ids as $key => $account_id) {
                    $this->db->select('distinct date_format(time, \'%Y-%m-%d\') as date, sum(value) as value');
                    $this->db->where('type = "follower" AND '. $where);
                    $this->db->where('account_id', $account_id);
                    $this->db->order_by('date', 'desc');
                    $this->db->group_by('date');
                    $fan = $this->db->get(db_prefix() . 'sa_analytics')->row();
                    if($fan && is_numeric($fan->value)){
                        $total += $fan->value;
                    }
                }
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('tiktok_total_followers_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-bell"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_following'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "following" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('tiktok_total_following_note')?></span>
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
        <span class="tooltip-text"><?php echo _l('tiktok_total_videos_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-eye"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_video_views'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "video_view" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('tiktok_total_video_views_note')?></span>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="icon"><i class="fas fa-comments"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_engagements'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "engagement" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('tiktok_total_engagements_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-thumbs-up"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_likes'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "engagement" and engagement = "like" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('tiktok_total_likes_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-retweet"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_shares'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' =>  array('(type = "engagement" and engagement = "share" OR type = "sentiment_minus") AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('tiktok_total_shares_note')?></span>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="icon"><i class="fas fa-comments"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_comments'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "engagement" and engagement = "comment" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('tiktok_total_comments_note')?></span>
      </div>
    </div>
</div>
