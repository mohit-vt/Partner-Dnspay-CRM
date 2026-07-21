<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="no-margin"><b>Activity Log</b></h3>
                        <hr>
               
                        <hr>    
                   
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Field</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th>Updated By</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= htmlspecialchars($log['field_name']) ?></td>
            <td><?= htmlspecialchars($log['old_value']) ?></td>
            <td><?= htmlspecialchars($log['new_value']) ?></td>
            <td><?= get_staff_full_name($log['updated_by']) ?></td>
            <td>
            <?= date('M j, Y \a\t g:i:s A', strtotime($log['updated_at'])) ?>
            </td>

        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>