<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-my-0 tw-font-bold tw-text-xl">Partner Deleted Records</h4>
                <hr>
                <div class="panel_s">
                    <div class="panel-body">
                        <table class="table dt-table table-striped" data-order-col="4" data-order-type="desc">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Agent ID</th>
                                    <th>Application ID</th>
                                    <th>Reason</th>
                                    <th>Deleted At</th>
                                    <th>IP Address</th>
                                    <th>Deleted By</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($deletions as $row): ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['reseller_code']; ?></td>
                                    <td><?= $row['application_id']; ?></td>
                                    <td><?= $row['reason']; ?></td>
                                    <td><?= $row['deleted_at']; ?></td>
                                    <td><?= $row['ip_address']; ?></td>
                                    <td><?= $row['firstname'] . ' ' . $row['lastname']; ?></td>
                                    <td><?= $row['deleted_by_role']; ?></td>
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
