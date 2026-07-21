<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.status-dropdown {
    color: white;
    font-weight: bold;
    border: none;
    padding: 5px 5px;
    border-radius: 4px;
    width: 100px;
}


.status-dropdown.yes {
    background-color: #008000; /* yellow */
}

.status-dropdown.no {
    background-color: #dc3545; /* red */
}
.table td, .table th {
    white-space: nowrap;   /* prevents text wrapping */
    text-overflow: ellipsis;
    overflow: hidden;
    max-width: 150px;      /* adjust per column */
}

.table td:nth-child(3), 
.table th:nth-child(3) {
    max-width: 200px; /* Applicant Name wider */
}

.table td:nth-child(5), 
.table th:nth-child(5) {
    max-width: 220px; /* Email column */
}

</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <h4 class="tw-my-0 tw-font-bold tw-text-xl">Merchant Pre-Application</h4>
            <hr>
                <div class="panel_s">
                    <div class="panel-body">
                    <a href="<?php echo admin_url('pre_application/create'); ?>" class="btn btn-primary">Add New application</a>
                   <?php  if ($pre_application_merchant_status == "true") { ?>  
                       <!--<button class="btn btn-danger" id="delete_selected">Delete</button> -->
                     <?php } ?>
                    <hr>
                    
                        <table class="table dt-table table-striped" data-order-col="5" data-order-type="desc">
                        <thead>
                            <tr>
                                <?php  if ($pre_application_merchant_status == "true") { ?> 
                                <!-- <th><input type="checkbox" id="select_all"></th>  -->
                                <?php } ?>
                                    <th>Agent Id</th>
                                    <th>Application Id</th>
                                    <th>Applicant Name</th>
                               		<th>Agent Name</th>
                                    <th>Cell Telephone Number</th>
                                    <th>Date Recieved</th>
                                    <th>Bank Name</th>
                                    <!--<th>Assigned To In House Employee</th>-->
                                    <th>Comments</th>
                                    <th>Actions</th>
                                    <th>Status</th>
                                     <?php if (is_admin()): ?>
                                        <th class="text-left">Delete</th>
                                         <?php endif; ?> 
                                    <!-- <th>Copy</th>
                                    <th>Duplication Alerts</th> -->
                                     
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($applications) === 0) { ?>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <?php echo "No applications available"; ?>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                <?php foreach ($applications as $report) { ?>
                                    <tr>
                                     <?php  if ($pre_application_merchant_status == "true") { ?>    
                                     <!-- <td><input type="checkbox" class="client_checkbox" value="<?= $report['id']; ?>"></td>  -->
                                     <?php } ?>
                                        <td><?php echo $report['agent_id']; ?></td>
                                     	<td><?php echo $report['application_id']; ?></td>
                                        <td><?php echo $report['first_name'] . ' ' . $report['last_name']; ?></td>
                                   		<td>
                                            <?php if (!empty($report['assign_agent'])): ?>
                                                <?= html_escape($report['assign_agent']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Unassigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo _d($report['cell_number']); ?></td>
                                        <td><?php echo $report['created_at']; ?></td>
                                        <td><?php echo $report['bank_name']; ?></td>
                                        <!--<td>
                                            <?php if (!empty($report['assign_employee'])): ?>
                                                <?= html_escape($report['assign_employee']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Unassigned</span>
                                            <?php endif; ?>
                                        </td>-->
                                        <td><?php echo $report['comments']; ?></td>
                                        <td>
                                            <a style="background:#90D5FF !important" href="<?php echo admin_url('pre_application/view/' . $report['id']); ?>" class="btn btn-sm btn-info">View</a>                                            
                                        </td>
                                        <td>  
                                        <?php  if ($pre_application_merchant_status == "true") { ?>  
                                        <select style="height: 30px !important"  class="status-dropdown form-control" data-id="<?= $report['id'] ?>">
                                        <option value="Yes" <?= $report['merchant_status'] == 'Yes' ? 'selected' : '' ?>>Yes</option>

                                        <option value="No" <?= $report['merchant_status'] == 'No' ? 'selected' : '' ?>>No</option>
                                        </select>

                                     
                                          <?php }else{ ?>
                                        <button
                                            class="btn btn-sm cancel-button <?= $report['merchant_status'] === 'Cancel' ? 'btn-danger' : 'btn-warning'; ?>"
                                            data-id="<?= $report['id']; ?>"
                                            data-status="<?= $report['merchant_status']; ?>">
                                            <?= $report['merchant_status'] === 'Cancel' ? 'Cancelled' : 'Cancel'; ?>
                                        </button>
                                              <?php } ?>


                                    </td>
                                    <?php if (is_admin()): ?>
                                        <td class="text-left">
                                            <a href="javascript:void(0);" style="color:white"
                                            class="btn btn-sm btn-danger delete-pre_application" 
                                            data-id="<?= $report['id']; ?>">
                                            Delete
                                            </a>
                                        </td>
                                    <?php endif; ?>                            
                                    <!-- <td>
                                    <a href="<?= admin_url('pre_application/copy/' . $report['id']) ?>" class="btn btn-sm btn-primary"><i class="fa fa-copy"></i> Copy </a>
                                    </td>
                                        <td >
                                            <?php if (!empty($report['is_duplicate'])): ?>
                                                 <button class="btn btn-sm bg-danger">Duplication Alert</button>
                                            <?php else: ?>
                                            
                                            <?php endif; ?>
                                        </td> -->
                                        
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

<!-- Delete Reason Modal -->
<div class="modal fade" id="deletePreappModal" tabindex="-1" role="dialog" aria-labelledby="deletePreappModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePreappModalLabel">Reason for Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deletePreappId">
        <div class="form-group">
          <label for="deleteReason">Please enter the reason:</label>
          <textarea id="deleteReason" class="form-control" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeletePreapp">Delete</button>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
 <script>
$(document).ready(function () {
    var partnerIdToDelete = null;

    // Open Modal
    $(document).on('click', '.delete-pre_application', function () {
        partnerIdToDelete = $(this).data('id');
        $('#deletePreappId').val(partnerIdToDelete);
        $('#deleteReason').val('');
        $('#deletePreappModal').modal('show');
    });
$('#confirmDeletePreapp').on('click', function () {
    var reason = $('#deleteReason').val().trim();
    var id = $('#deletePreappId').val();

    if (!reason) {
        alert('Please provide a reason for deletion.');
        return;
    }

    var csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash(); ?>";

$.ajax({
    url: '<?= admin_url('pre_application/delete_pre_application'); ?>',
    type: 'POST',
    data: { 
        id: id,                // ✅ send id in POST
        reason: reason,
        [csrfName]: csrfHash 
    },
    dataType: 'json',
    success: function (response) {
        $('#deletePreappModal').modal('hide');
        if (response.success) {
            location.reload();
        } else {
            alert('Error: ' + response.message);
        }
    },
    error: function (xhr, status, error) {
        console.error('AJAX Error:', status, error, xhr.responseText);
        alert('Unexpected error occurred.');
    }
});

});

});

$(document).ready(function () {
    // Select/Deselect all
    $('#select_all').on('change', function () {

        $('.client_checkbox').prop('checked', this.checked);
    });

    // Uncheck 'Select All' if any checkbox is unchecked manually


    // Delete Selected
    $('#delete_selected').on('click', function () {
        var selectedIds = $('.client_checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert('Please select at least one record.');
            return;
        }
            $.ajax({
                url: '<?= admin_url('pre_application/delete_multiple'); ?>',
                type: 'POST',
                data: { ids: selectedIds },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        //alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function () {
                    alert('An unexpected error occurred.');
                }
            });
        
    });

$(document).on('click', '.cancel-button', function () {
    var $btn = $(this);
    var id = $btn.data('id');
    var currentStatus = $btn.data('status');

    // Already cancelled
    if (currentStatus === 'Cancel') {
        return;
    }

        $btn
        .removeClass('btn-warning')
        .addClass('btn-danger')
        .text('Cancelled')
        .data('status', 'Cancel');

    $.ajax({
        type: "POST",
        url: admin_url + "pre_application/update_status",
        data: { id: id, status: 'Cancel' },
        success: function (response) {
            var data = JSON.parse(response);
            if (data.success) {
                // Update button only after DB update
                $btn
                    .removeClass('btn-warning')
                    .addClass('btn-danger')
                    .text('Cancelled')
                    .data('status', 'Cancel');
            } else {
                alert('Failed to update: ' + (data.message || 'Unknown error'));
            }
        },
        error: function () {
            alert('Server error while cancelling.');
        }
    });
});
  
  
        // Apply color on page load
        $('.status-dropdown').each(function () {
            updateStatusColor($(this), $(this).val());
        });



    $(document).ready(function () {
        $(document).on('change', '.status-dropdown', function () {
            var $this = $(this); // Correctly define $this here
            updateStatusColor($this, $this.val());
            var $dropdown = $(this);
            var newStatus = $dropdown.val();
            var id = $dropdown.data('id');

            var $wrapper = $dropdown.closest('.status-wrapper');

            $.ajax({
                type: "POST",
                url: admin_url + "pre_application/update_status",
                data: { id: id, status: newStatus },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Update wrapper color
                        $wrapper
                            .removeClass('bg-success bg-danger bg-secondary')
                            .addClass(getStatusClass(newStatus));
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function () {
                    alert('Server error while updating status.');
                }
            });
        });


    });
    
  
  function updateStatusColor(selectElement, status) {
        selectElement.removeClass('yes no');

        switch (status.toLowerCase()) {
            case 'yes':
                selectElement.addClass('yes');
                break;
            case 'no':
                selectElement.addClass('no');
                break;
        }
    }

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


