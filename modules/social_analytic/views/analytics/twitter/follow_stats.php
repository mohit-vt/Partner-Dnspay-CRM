<div class="col-md-4">
  <h4 class="country-title"><?php echo _l('sa_followers_following_rate'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('followers_following_rate_note')?>"></i></h4>
    <ul class="country-list">
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
          <li>
              <span class="country-name"><?php echo _l('sa_followers'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
          <?php
                $total = 0;
                foreach ($account_ids as $key => $account_id) {
                    $this->db->select('distinct date_format(time, \'%Y-%m-%d\') as date, sum(value) as value');
                    $this->db->where('type = "following" AND '. $where);
                    $this->db->where('account_id', $account_id);
                    $this->db->order_by('date', 'desc');
                    $this->db->group_by('date');
                    $fan = $this->db->get(db_prefix() . 'sa_analytics')->row();
                    if($fan && is_numeric($fan->value)){
                        $total += $fan->value;
                    }
                }
              ?>
          <li>
              <span class="country-name"><?php echo _l('sa_following'); ?></span>
              <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
          </li>
    </ul>
</div>
<div class="col-md-4">
  <div id="follow_rate_pie_chart"></div>
</div>
<div class="col-md-4">
  <div class="stat-card">
      <div class="icon"><i class="fas fa-users"></i></div>
      <div class="stat-info">
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
        <h3><?php echo _l('total_followers'); ?></h3>
        <p><?php echo number_format($total ?? 0); ?></p>
      </div>
      <div class="tooltip-container">
        <i class="fas fa-question-circle tooltip-icon"></i>
        <span class="tooltip-text"><?php echo _l('twitter_total_follower_note')?></span>
      </div>
    </div>
    
</div>