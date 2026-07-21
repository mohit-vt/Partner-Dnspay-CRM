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
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <h4 class="tw-my-0 tw-font-bold tw-text-xl">Merchant Pre-Application</h4>
            <hr>
                <div class="panel_s">
                    <div class="panel-body">
                    <a href="<?php echo base_url('reseller/pre_application/create'); ?>" class="btn btn-primary">Add New application</a>
                   <?php  if ($pre_application_merchant_status == "true") { ?>  
                       <!--<button class="btn btn-danger" id="delete_selected">Delete</button> -->
                     <?php } ?>
                    <hr>
                    
                        <table class="table dt-table table-striped" data-order-col="5" data-order-type="desc" >
                        <thead>
                            <tr>
                                <?php  if ($pre_application_merchant_status == "true") { ?> 
                                <!-- <th><input type="checkbox" id="select_all"></th>  -->
                                <?php } ?>
                                    <th>Firts Name</th>
                                    <th>Last Name</th>
                                    <th>Cell Telephone Number</th>
                                    <th>Email Address</th>
                                    <th>Bank Name</th>
                                    <th>Date received</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                    <th>Status</th>
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
                                        <td><?php echo $report['first_name']; ?></td>
                                        <td><?php echo $report['last_name']; ?></td>
                                        <td><?php echo _d($report['cell_number']); ?></td>
                                        <td><?php echo $report['personal_email_address']; ?></td>
                                        <td><?php echo $report['bank_name']; ?></td>
                                        <td><?php echo $report['created_at']; ?></td>
                                        <td><?php echo $report['comments']; ?></td>
                                        <td>
     <a style="background:#90D5FF !important" href="<?php echo site_url('reseller/pre_application/view/' . $report['id']); ?>" class="btn btn-sm btn-info">View</a>                                            
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
<?php init_tail(); ?>
 <script>
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


