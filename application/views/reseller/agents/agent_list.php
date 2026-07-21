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
                <a href="<?php echo site_url('reseller/agents/create'); ?>" class="btn btn-primary">Add New Sub Agent Reseller</a>
                  
                    <hr>
                        <table class="table dt-table table-striped" data-order-col="4" data-order-type="desc">
                        <thead>
                            <tr>
                                <!--<th><input type="checkbox" id="select_all"></th> -->
                                    <th class="text-left">Agent ID</th>
                              <th class="text-left">Application ID</th>
                                    <th class="text-left">Applicant Name</th>
                                    <th class="text-left">Business Nature</th>
                                    <th class="text-left">Date Recieved</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Actions</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($leads as $lead): ?>
           
        
            <tr>
            <!-- <td><input type="checkbox" class="client_checkbox" value="<?= $lead['id']; ?>"></td> -->
                 <td class="text-left"><?= $lead['agents_code']; ?></td> <!-- This will start from 1 -->
				 <td class="text-left"><?= $lead['application_id']; ?>
                <td class="text-left"><?php echo $lead['first_name'] . ' ' . $lead['last_name']; ?></td>
                <td class="text-left"><?php echo $lead['business_nature']; ?></td>
                <td class="text-left"><?php echo $lead['date_created']; ?></td>
                <td>    
                <select style="height: 30px !important" class="status-dropdown form-control" data-id="<?= $lead['id'] ?>">
                <option value="Pending" <?= $lead['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $lead['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Declined" <?= $lead['status'] == 'Declined' ? 'selected' : '' ?>>Declined</option>
                </select>
                </td>
                <td class="text-left"><a style="background:#90D5FF !important" href="<?php echo site_url('reseller/agents/view/' . $lead['id']); ?>" class="btn btn-sm btn-info">View</a></td>
              
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

<script>


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
        selectElement.removeClass('pending approved declined');

        switch (status.toLowerCase()) {
            case 'pending':
                selectElement.addClass('pending');
                break;
            case 'approved':
                selectElement.addClass('approved');
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



