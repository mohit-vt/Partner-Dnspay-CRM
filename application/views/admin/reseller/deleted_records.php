<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-my-0 tw-font-bold tw-text-xl">Deleted Reseller Records</h4>
                <hr>
                <div class="panel_s">
                    <div class="panel-body">
                        <table class="table dt-table table-striped" data-order-col="3" data-order-type="desc">
                            <thead>
                                <tr>
                                    <!--<th>ID</th>-->
                                    <th>Agent ID</th>
                                    <th>Application ID</th>
                                    <th>Reason</th>
                                    <th>Deleted At</th>
                                    <th>Deleted By</th>
                                    <th>Role</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($deletions as $d): ?>
                                    <tr>
                                        <!--<td><?= $d['id']; ?></td>-->
                                        <td><?= html_escape($d['reseller_code']); ?></td>
                                        <td><?= html_escape($d['application_id']); ?></td>
                                        <td><?= html_escape($d['reason']); ?></td>
                                        <td><?= _dt($d['deleted_at']); ?></td>
                                        <td><?= $d['firstname'] . ' ' . $d['lastname']; ?></td>
                                        <td><?= html_escape($d['deleted_by_role']); ?></td>
                                        <td><?= $d['ip_address']; ?></td>
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
