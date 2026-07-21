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

.status-dropdown.pending {
    background-color: #ffc107; /* yellow */
}

.status-dropdown.approved {
    background-color: #28a745; /* green */
}

.status-dropdown.declined {
    background-color: #dc3545; /* red */
}
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <h4 class="tw-my-0 tw-font-bold tw-text-xl">ISO/Reseller Applications</h4>
            <hr>
                <div class="panel_s">
                    <div class="panel-body">

                    <hr>
                        <table class="table dt-table table-striped" data-order-col="5" data-order-type="desc">
                        <thead>
                            <tr>
                                <!--<th><input type="checkbox" id="select_all"></th>  Select All -->
                                   <th class="text-left">Agent ID</th>
                                   <th class="text-left">Application ID</th> 
                                    <th class="text-left">Applicant Name</th>
                                    <th class="text-left">Remote IP</th>
                                    <th class="text-left">User Agent</th>
                              		<th class="text-left">Date Added</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Actions</th>
                                    <th></th>
                              		<?php if (is_admin()): ?>
                                    <th></th>
                                  <?php endif; ?>
                              
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($leads as $lead): ?>
         
        
            <tr>
            <!--<td><input type="checkbox" class="client_checkbox" value="<?= $lead['id']; ?>"></td>-->
                <!--<td class="text-left"><?= isset($lead['id']) ? $lead['id'] : ''; ?></td>-->                
                <td><?= html_escape($lead['agent_id']); ?></td>
                <td><?= html_escape($lead['application_id']); ?></td>
			<td><?= htmlspecialchars($lead['full_name']); ?></td>
                <td><?= $lead['remote_ip']; ?></td>
                <td><?= $lead['user_agent']; ?></td>
                <td><?= $lead['dateadded']; ?></td>
               <?php $status = isset($lead['application_status']) ? $lead['application_status'] : 'Pending'; ?>
                <td>
                    <select style="height: 30px !important" class="status-dropdown form-control" data-id="<?= $lead['id'] ?>">
                        <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Approved" <?= $status == 'Approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="Declined" <?= $status == 'Declined' ? 'selected' : '' ?>>Declined</option>
                    </select>
                </td>

                <td class="text-left"><a style="background:#90D5FF !important" href="<?php echo admin_url('reseller/view/' . $lead['id']); ?>" class="btn btn-sm btn-info">View</a></td>

                <td class="text-left"><a style="background:red !important;color:white" href="<?php echo admin_url('reseller/create/' . $lead['id']); ?>" class="btn btn-sm btn-info">Register</a></td>
     
				 <?php if (is_admin()): ?>
                <td class="text-left"><a href="javascript:void(0);" style="color:white" 
                    class="btn btn-sm btn-danger delete-single" 
                    data-id="<?= $lead['id']; ?>">Delete</a>
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
<!-- Delete Application Modal -->
<div class="modal fade" id="deleteApplicationModal" tabindex="-1" role="dialog" aria-labelledby="deleteApplicationModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="deleteApplicationModalLabel">Delete Application</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="deleteApplicationId">
        <div class="form-group">
          <label for="deleteApplicationReason">Reason for deletion <span class="text-danger">*</span></label>
          <textarea id="deleteApplicationReason" class="form-control" rows="3" required></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteApplication">Delete</button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>

<!-- then circle-progress -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
<script>
$(document).ready(function () {

    // Select/Deselect all
    $('#select_all').on('change', function () {
        $('.client_checkbox').prop('checked', this.checked);
    });

    $(document).on('change', '.client_checkbox', function () {
        if (!this.checked) {
            $('#select_all').prop('checked', false);
        }
        if ($('.client_checkbox:checked').length === $('.client_checkbox').length) {
            $('#select_all').prop('checked', true);
        }
    });

    // Open modal
    $(document).on('click', '.delete-single', function () {
        var appId = $(this).data('id');
        $('#deleteApplicationId').val(appId);
        $('#deleteApplicationReason').val('');
        $('#deleteApplicationModal').modal('show');
    });

    // Confirm delete
    $('#confirmDeleteApplication').click(function () {
        var appId = $('#deleteApplicationId').val();
        var reason = $('#deleteApplicationReason').val();

        if (!reason) {
            alert_float('danger', 'Please provide a reason for deletion');
            return;
        }

        $.ajax({
            url: admin_url + 'reseller/delete',
            type: 'POST',
            data: { id: appId, reason: reason },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert_float('success', response.message);
                    window.location.reload();
                } else {
                    alert_float('danger', response.message);
                }
                $('#deleteApplicationModal').modal('hide');
            },
            error: function (xhr) {
                var errorMsg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'An error occurred while deleting the application';
                alert_float('danger', errorMsg);
                $('#deleteApplicationModal').modal('hide');
            }
        });
    });

    // Status color + update
    function updateStatusColor(selectElement, status) {
        selectElement.removeClass('pending approved declined');
        switch (status.toLowerCase()) {
            case 'pending': selectElement.addClass('pending'); break;
            case 'approved': selectElement.addClass('approved'); break;
            case 'declined': selectElement.addClass('declined'); break;
        }
    }

    $('.status-dropdown').each(function () {
        updateStatusColor($(this), $(this).val());
    });

    $('.status-dropdown').on('change', function () {
        var $this = $(this);
        updateStatusColor($this, $this.val());

        $.ajax({
            type: "POST",
            url: admin_url + 'reseller/update_status',
            data: { id: $this.data('id'), status: $this.val() },
            success: function (data) { console.log('Status update response:', data); },
            error: function (xhr, status, error) { console.error('AJAX error:', error); }
        });
    });

    <?php 
    $rolevalue = $this->roles_model->get($current_user->role);
    if (isset($rolevalue->name) && in_array($rolevalue->name, ["agent","reseller","partner admin"])) { ?>
        // Disable Right-Click
        document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
        // Disable Dev Tools Shortcuts
        document.onkeydown = function(e) {
            if (e.keyCode === 123 || (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) || (e.ctrlKey && e.keyCode === 85)) {
                return false;
            }
        };
    <?php } ?>

}); // ✅ properly closed
</script>



