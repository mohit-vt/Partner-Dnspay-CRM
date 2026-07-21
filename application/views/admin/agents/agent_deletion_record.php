<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-my-0 tw-font-bold tw-text-xl">Deleted Agent Records</h4>
                <hr>
                <div class="panel_s">
                    <div class="panel-body">
                       <table class="table dt-table table-striped" data-order-col="5" data-order-type="desc">
                            <thead>
                                <tr>
                                    <th>Agent ID</th>
                                    <th>Application ID</th>
                                    <th>Reason</th>
                                    <th>Deleted By</th>
                                    <th>Role</th>
                                    <th>Deleted At</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($deletions as $delete): ?>
                                    <tr>
                                        <td><?= $delete['agent_code']; ?></td>
                                        <td><?= $delete['application_id'] ?? '-'; ?></td>
                                        <td><?= $delete['reason']; ?></td>
                                        <td><?= $delete['deleted_by_name']; ?></td>
                                        <td><?= $delete['deleted_by_role']; ?></td>
                                        <td><?= $delete['deleted_at']; ?></td>
                                        <td><?= $delete['ip_address']; ?></td>
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
