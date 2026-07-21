<div class="facebook-dashboard-stats">
    <div class="stat-card">
      <div class="icon"><i class="fas fa-users"></i></div>
      <div class="stat-info">
        <?php
                $total = 0;
                foreach ($account_ids as $key => $account_id) {
                    $this->db->select('distinct date_format(time, \'%Y-%m-%d\') as date, sum(value) as value');
                    $this->db->where('type = "fan" AND '. $where);
                    $this->db->where('account_id', $account_id);
                    $this->db->order_by('date', 'desc');
                    $this->db->group_by('date');
                    $fan = $this->db->get(db_prefix() . 'sa_analytics')->row();
                    if($fan && is_numeric($fan->value)){
                        $total += $fan->value;
                    }
                }
              ?>

        <h3><?php echo _l('sa_fans'); ?></h3>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_fans_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-eye"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_impressions'); ?></h3>
        <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "impression" AND '. $where))); ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_impressions_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-comments"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_engagements'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('(type = "comment" OR type = "reaction" OR type = "share" OR type = "click") AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_engagements_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-smile"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_reactions'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "reaction" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_reactions_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-mouse-pointer"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_clicks'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "click" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_clicks_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-comments"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_comments'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "comment" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_comments_note')?></span>
      </div>
    </div>

    <div class="stat-card">
      <div class="icon"><i class="fas fa-frown"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_sentiment'); ?></h3>
        <?php
              $total_sentiment_plus = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' =>  array('(type = "reaction" and (reaction = "like" OR reaction = "love" OR reaction = "haha" OR reaction = "wow")) AND '. $where)));
              $total_sentiment_minus = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' =>  array('(type = "reaction" and (reaction = "sad" OR reaction = "angry")) AND '. $where)));
              ?>
        <div class="stat-value-success">+ve: <?php echo number_format($total_sentiment_plus ?? 0); ?></div>
        <div class="stat-value-danger">-ve: <?php echo number_format($total_sentiment_minus ?? 0); ?></div>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_sentiments_note')?></span>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="icon"><i class="fas fa-commenting"></i></div>
      <div class="stat-info">
        <h3><?php echo _l('sa_message_count'); ?></h3>
        <?php
              $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "message_count" AND '. $where)));
              ?>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('total_message_count_note')?></span>
      </div>
    </div>
</div>
