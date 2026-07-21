<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  <?php $rolevalue = $this->roles_model->get($current_user->role);
if (isset($rolevalue->name) && ($rolevalue->name == "agent" || $rolevalue->name == "reseller" || $rolevalue->name == "partner admin")) {  ?> 
.dt-buttons {
    display: none !important;
}
  <?php } ?>
.status-dropdown {
    color: white;
    font-weight: bold;
    border: none;
    padding: 5px 5px;
    border-radius: 4px;
    width: 100px;
}

.status-dropdown.pending {
    background-color: #ffc107; /* yellow */
}

.status-dropdown.approved {
    background-color: #28a745; /* green */
}
  .status-dropdown.approved-missingdoc {
    background-color: #FF5C00; /* green */
}

.status-dropdown.declined {
    background-color: #dc3545; /* red */
}

</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <h4 class="tw-my-0 tw-font-bold tw-text-xl">Sub Agent Reseller Applications</h4>
            <hr>
                <div class="panel_s">
                    <div class="panel-body">
                <a href="<?php echo admin_url('agents/create'); ?>" class="btn btn-primary">Add New Sub Agent Reseller</a>
                  
                    <hr>
                        <table class="table dt-table table-striped" data-order-col="6" data-order-type="desc">
                        <thead>
                            <tr>
                                <!--<th><input type="checkbox" id="select_all"></th> -->
                                    <th class="text-left">Agent ID</th>
                                    <th class="text-left">Application ID</th>
                              		<th class="text-left">Agent Name</th>
                                    <th class="text-left">Applicant Name</th>
                                    <th class="text-left">Business Nature</th>
                              		<th class="text-left">Assigned To Agent</th>
                                    <th class="text-left">Date Recieved</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Actions</th>
                              <?php if (is_admin()): ?>
                                     <th class="text-left">Delete</th>
                              <?php endif; ?> 
                                    
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($leads as $lead): ?>
            <tr>
            <!-- <td><input type="checkbox" class="client_checkbox" value="<?= $lead['id']; ?>"></td> -->
                 <td class="text-left"><?= $lead['agents_code']; ?></td>
                <td class="text-left"><?= $lead['application_id']; ?></td> 
              <td class="text-left"><?php echo $lead['firstname'] . ' ' . $lead['lastname']; ?></td>
				<td class="text-left"><?php echo $lead['first_name'] . ' ' . $lead['last_name']; ?></td>
                <td class="text-left"><?php echo $lead['business_nature']; ?></td>
              	           
            <td>
                <?= isset($agent_map[$lead['assigned_to_agent']]) 
                    ? htmlspecialchars($agent_map[$lead['assigned_to_agent']]) 
                    : ''; ?>
            </td>

  			
                <td class="text-left"><?php echo $lead['date_created']; ?></td>
                <td>    
                <select style="height: 30px !important" class="status-dropdown form-control" data-id="<?= $lead['id'] ?>">
                <option value="Pending" <?= $lead['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $lead['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Approved-MissingDoc" <?= $lead['status'] == 'Approved-MissingDoc' ? 'selected' : '' ?>>Approved – Missing Doc</option>
                <option value="Declined" <?= $lead['status'] == 'Declined' ? 'selected' : '' ?>>Declined</option>
                </select>
                </td>
                <td class="text-left"><a style="background:#90D5FF !important" href="<?php echo admin_url('agents/view/' . $lead['id']); ?>" class="btn btn-sm btn-info">View</a></td>
              <?php if (is_admin()): ?>
                <td class="text-left">
                    <a href="javascript:void(0);" 
                    class="btn btn-sm btn-danger delete-agents" 
                    data-id="<?= $lead['id']; ?>">
                    Delete
                    </a>
                </td>

            <?php endif; ?> 
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

<!-- Delete Reason Modal -->
<div class="modal fade" id="deleteAgentModal" tabindex="-1" role="dialog" aria-labelledby="deleteAgentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAgentModalLabel">Reason for Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deleteAgentId">
        <div class="form-group">
          <label for="deleteReason">Please enter the reason:</label>
          <textarea id="deleteReason" class="form-control" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteAgent">Delete</button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>

<script>

$(document).ready(function () {
    var partnerIdToDelete = null;

    // Open Modal
    $(document).on('click', '.delete-agents', function () {
        partnerIdToDelete = $(this).data('id');
        $('#deleteAgentId').val(partnerIdToDelete);
        $('#deleteReason').val('');
        $('#deleteAgentModal').modal('show');
    });
$('#confirmDeleteAgent').on('click', function () {
    var reason = $('#deleteReason').val().trim();
    var id = $('#deleteAgentId').val();

    if (!reason) {
        alert('Please provide a reason for deletion.');
        return;
    }

    var csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash(); ?>";

    $.ajax({
        url: '<?= admin_url('agents/delete_agents'); ?>/' + id,
        type: 'POST',
        data: { 
            reason: reason,
            [csrfName]: csrfHash 
        },
        dataType: 'json',
        success: function (response) {
            $('#deleteAgentModal').modal('hide');
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
    var csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash(); ?>";
            $.ajax({
    url: '<?= admin_url('reseller/delete_multiple'); ?>',
    type: 'POST',
    data: {
        ids: selectedIds,
        [csrfName]: csrfHash
    },
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
    error: function (xhr, status, error) {
        console.error('AJAX Error:', status, error, xhr.responseText);
        alert('An unexpected error occurred.\n' + xhr.responseText);
    }
});


        }
    });
});

$(document).ready(function () {
function updateStatusColor(selectElement, status) {
    selectElement.removeClass('pending approved declined approved-missingdoc');

    // Normalize: lowercase, remove spaces and special dashes
    var normalized = status.toLowerCase()
                            .replace(/\s+/g, '') // remove spaces
                            .replace(/[–—-]/g, '-'); // normalize dash types

    switch (normalized) {
        case 'pending':
            selectElement.addClass('pending');
            break;
        case 'approved':
            selectElement.addClass('approved');
            break;
        case 'approved-missingdoc':
            selectElement.addClass('approved-missingdoc');
            break;
        case 'declined':
            selectElement.addClass('declined');
            break;
    }
}

    // Apply color on page load
    $('.status-dropdown').each(function () {
        updateStatusColor($(this), $(this).val());
    });

    // Optional: Update color dynamically when status changes
    $('.status-dropdown').on('change', function () {
    var $this = $(this); // Correctly define $this here

    updateStatusColor($this, $this.val());

    var id = $this.data('id');
    var status = $this.val();

    $.ajax({
        type: "POST",
        url: "agents/update_status", // Ensure this path is correct
        data: { id: id, status: status },
        success: function(data) {
            console.log('Status update response:', data);
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
        }
    });
});

});

  
<?php $rolevalue = $this->roles_model->get($current_user->role);
if (isset($rolevalue->name) && ($rolevalue->name == "agent" || $rolevalue->name == "reseller" || $rolevalue->name == "partner admin")) {  ?>
    // Disable Right-Click
$(function () {
    // Re-initialize without export buttons
    $('.dt-table').DataTable({
        dom: 'lfrtip',  // remove 'B' to hide buttons
        buttons: []     // extra safety, clears any inherited buttons
    });
});
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



