<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .break-word {
    word-break: break-word;
    max-width: 300px;
}

.dt-table th {
    vertical-align: middle;
    background: #f5f5f5;
}

.dt-table td small {
    font-size: 0.85em;
}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">Deleted Records</h4>
                        <hr class="hr-panel-heading" />
                        <div class="table-responsive">
<table class="table dt-table table-striped" data-order-col="4" data-order-type="desc">
    <thead>
        <tr>
            <th width="8%"><?php echo _l('Application id'); ?></th>
            <th width="20%"><?php echo _l('Deleted by'); ?></th>
            <th width="12%"><?php echo _l('User role'); ?></th>
            <th width="25%"><?php echo _l('Deletion reason'); ?></th>
            <th width="15%"><?php echo _l('Deletion date'); ?></th>
            <th width="15%"><?php echo _l('IP Address'); ?></th>
      
        </tr>
    </thead>
                      <tbody>
    <?php  foreach($deletions as $deletion): ?>
    <tr>
        <td><?= $deletion['application_id'] ?></td>
        <td class="deleted-by-cell">
    <div class="deleted-by-info">
        
            <i class="fa fa-user-circle-o text-primary" aria-hidden="true"></i>
           <?= htmlspecialchars($deletion['staff_firstname']) ?> 
            <?= htmlspecialchars($deletion['staff_lastname']) ?>   
   
    </div>
</td>
        <td><?= !empty($deletion['deleted_by_role']) ? htmlspecialchars($deletion['deleted_by_role']) : 'Unknown' ?></td>
        <td><?= nl2br(htmlspecialchars($deletion['reason'])) ?></td>
        <td><?= _dt($deletion['deleted_at']) ?></td>
        <td><?= !empty($deletion['ip_address']) ? $deletion['ip_address'] : 'N/A' ?></td>
    </tr>
    <?php endforeach; ?>
</tbody>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/circle-progress/1.2.2/circle-progress.js"></script>
<script>
if (!$.fn.circleProgress) {
    $.fn.circleProgress = function () { return this; };
}
</script>
