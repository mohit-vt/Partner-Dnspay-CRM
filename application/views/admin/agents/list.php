<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <h4 class="tw-my-0 tw-font-bold tw-text-xl">Sub - Reseller</h4>
            <hr>
                <div class="panel_s">
                    <div class="panel-body">
                    <a href="<?php echo admin_url('agents/create'); ?>" class="btn btn-primary">Add New</a>
                    <button class="btn btn-danger" id="delete_selected">Delete</button>
                    <hr>
                    
                        <table class="table dt-table table-striped" data-order-col="6" data-order-type="desc">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all"></th> <!-- Select All -->
                                    <th>Company Name</th>
                                    <th>Company DBA</th>
                                    <th>Date Received</th>
                                    <th>Agent Name Contact</th>
                                    <th>Acquiring Location</th>
                                    <th>Lead Source</th>
                                    <th>Assigned To In House Employee </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (count($pipeline_reports) === 0) { ?>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <?php echo "No sub - reseller available"; ?>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                <?php foreach ($pipeline_reports as $report) { ?>
                                    <tr>
                                    <td><input type="checkbox" class="client_checkbox" value="<?= $report['id']; ?>"></td>
                                        <td><?php echo $report['company_name']; ?></td>
                                        <td><?php echo $report['company_dba']; ?></td>
                                        <td><?php echo _d($report['date_received']); ?></td>
                                        <td><?php echo $report['agent_name']; ?></td>
                                        <td><?php echo $report['acquiring_location']; ?></td>
                                        <td><?php echo $report['lead_source']; ?></td>
                                        <td></td>

                                        <td>
                                            <a style="background:#90D5FF !important" href="<?php echo admin_url('agents/view/' . $report['id']); ?>" class="btn btn-sm btn-info">View</a>
                                            <a href="<?php echo admin_url('agents/delete/' . $report['id']); ?>" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
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

<script>
jQuery(document).ready(function () {
    // Select/Deselect all
    $('#select_all').on('change', function () {

        $('.client_checkbox').prop('checked', this.checked);
    });

    // Uncheck 'Select All' if any checkbox is unchecked manually
    $(document).on('change', '.client_checkbox', function () {
        if (!this.checked) {
            $('#select_all').prop('checked', false);
        }

        // If all checkboxes are checked, check 'Select All'
        if ($('.client_checkbox:checked').length === $('.client_checkbox').length) {
            $('#select_all').prop('checked', true);
        }
    });

    // Delete Selected
    $('#delete_selected').on('click', function () {
        var selectedIds = $('.client_checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert('Please select at least one record.');
            return;
        }

        if (confirm('Are you sure you want to delete the selected records?')) {
            $.ajax({
                url: '<?= admin_url('agents/delete_multiple'); ?>',
                type: 'POST',
                data: { ids: selectedIds },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function () {
                    alert('An unexpected error occurred.');
                }
            });
        }
    });
});
  <?php $rolevalue = $this->roles_model->get($current_user->role);
if (isset($rolevalue->name) && ($rolevalue->name == "agent" || $rolevalue->name == "reseller" || $rolevalue->name == "partner admin")) {  ?>
    // Disable Right-Click

  document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
  });

  // Disable Keyboard Shortcuts (like F12, Ctrl+Shift+I, Ctrl+U)
  document.onkeydown = function(e) {
    if (
      e.keyCode === 123 || // F12
      (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) || // Ctrl+Shift+I or Ctrl+Shift+J
      (e.ctrlKey && e.keyCode === 85) // Ctrl+U
    ) {
      return false;
    }
  };
   <?php } ?>
</script>


