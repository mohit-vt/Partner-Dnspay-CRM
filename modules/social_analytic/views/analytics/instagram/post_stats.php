<h4 class="country-title"><?php echo _l('sa_publishing_behavior_by_media_type'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('publishing_behavior_by_media_type_note')?>"></i></h4>
<ul class="country-list">
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('sa_total_published_posts'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND post_type = "video" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('sa_published_videos'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND post_type = "photo" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('sa_published_photos'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
      <?php $total = sum_from_table(db_prefix() . 'sa_analytics', array('field' => 'value', 'where' => array('type = "post" AND post_type = "carousel" AND '. $where))); ?>
      <li>
          <span class="country-name"><?php echo _l('sa_published_carousels'); ?></span>
          <span class="country-count"><?php echo number_format($total ?? 0); ?></span>
      </li>
</ul>
