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
  <div class="panel_s">
    <div class="panel-body">
      <h4>Archived Pipeline Reports</h4>
      <hr>
      <a href="<?= admin_url('pipeline_report'); ?>" class="btn btn-primary mb-3">
        <i class="fa fa-arrow-left"></i> Back to Pipeline
      </a>

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Applicant Id</th>
            <th>Lead Source</th>
            <th>Applicant Name</th>
            <th>Company</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Application Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($archived_list as $row): ?>
          <tr>
            <td><?= $row['application_id']; ?></td>
            <td><?= $row['lead_source']; ?></td>
            <td><?= htmlspecialchars(trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''))); ?></td>
            <td><?= htmlspecialchars($row['company_name']); ?></td>
            <td><?= htmlspecialchars($row['email_address']); ?></td>
            <td><?= htmlspecialchars($row['contact_phone']); ?></td>
            <td><?= htmlspecialchars($row['application_date']); ?></td>
            <td>
              <button class="btn btn-sm btn-success unarchive-record" data-id="<?= $row['id']; ?>">
                <i class="fa fa-undo"></i> Restore
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
         </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/circle-progress/1.2.2/circle-progress.js"></script>
<script>
$(document).on('click', '.unarchive-record', function() {
    const id = $(this).data('id');
    if (!confirm('Restore this record to the pipeline?')) return;

    $.ajax({
        url: admin_url + 'pipeline_report/unarchive_record',
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        success: function(res) {
            if (res.success) {
                alert_float('success', res.message);
                $('button.unarchive-record[data-id="' + id + '"]').closest('tr').fadeOut();
            } else {
                alert_float('danger', res.message);
            }
        },
        error: function() {
            alert_float('danger', 'Server error while restoring record');
        }
    });
});
</script>
<?php init_tail(); ?>
