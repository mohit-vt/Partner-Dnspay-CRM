<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-my-0 tw-font-bold tw-text-xl">Merchant Pre Application Deleted Records</h4>
                <hr>
                <div class="panel_s">
                    <div class="panel-body">

                       <table class="table dt-table table-striped" data-order-col="4" data-order-type="desc">
                        <thead>
                            <tr>
                                <th>Agent ID</th>
                                <th>Application ID</th>
                                <th>Reason</th>
                                <th>Deleted By</th>
                                <th>Deleted At</th>
                                <th>Snapshot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($deleted_records)) { ?>
                                <?php foreach ($deleted_records as $index => $rec) { ?>
                                    <tr>
                                       <td><?= $rec['agent_id']; ?></td>
                                        <td><?= $rec['application_id']; ?></td>
                                        <td><?= htmlspecialchars($rec['reason']); ?></td>
                                        <td><?= get_staff_full_name($rec['deleted_by']); ?></td>
                                        <td><?= _dt($rec['deleted_at']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" 
                                                onclick="showSnapshot(<?= htmlspecialchars(json_encode($rec['data_snapshot']), ENT_QUOTES, 'UTF-8'); ?>)">
                                                View Snapshot
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr><td colspan="6" class="text-center">No records found</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<!-- Snapshot Modal -->
<div class="modal fade" id="snapshotModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Deleted Record Snapshot</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <pre id="snapshotContent"></pre>
      </div>
    </div>
  </div>
</div>

<script>
function showSnapshot(snapshot) {
    try {
        let obj = JSON.parse(snapshot);
        $('#snapshotContent').text(JSON.stringify(obj, null, 2));
    } catch(e) {
        $('#snapshotContent').text(snapshot);
    }
    $('#snapshotModal').modal('show');
}
</script>
<?php init_tail(); ?>
