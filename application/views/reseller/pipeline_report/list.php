<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
 .scrollbar-bottom {
    overflow-x: auto;
    overflow-y: hidden;
    width: 100%;
    height: 16px;
}

.scrollbar-bottom > div {
    height: 1px;
}
 
  
/* Top scrollbar sync styling */
.scrollbar-top {
  overflow-x: auto;
  height: 16px;
}
.scrollbar-top div {
  height: 1px;
}
  
  /* Sorting indicator */
th.sortable {
  cursor: pointer;
}
th.sortable::after {
  content: " ⇅";
  font-size: 12px;
  color: #888;
}
  .badge-light {
    background-color: #f9f9f9;
    color: #333;
    font-size: 12px;
    border-radius: 6px;
    display: inline-block;
    white-space: nowrap;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
}

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
/* Container for vertical + horizontal scroll */
.table-wrapper {
  max-height: 70vh;      /* vertical scroll limit */
  overflow-y: auto;       /* vertical scroll */
  overflow-x: hidden;     /* horizontal scroll handled separately */
  position: relative;
  border: 1px solid #ddd;
  border-radius: 6px;
}

/* Top horizontal scrollbar */
.scrollbar-top {
  overflow-x: auto;
  overflow-y: hidden;
  width: 100%;
}
.scrollbar-top > div {
  height: 1px; /* dummy div to match table width */
}

/* Table styling */
#pipelineTable {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}

/* Sticky header */
#pipelineTable thead th {
  position: sticky;
  top: 0;
  background: #f1f1f1;
  z-index: 10;
  border-bottom: 2px solid #ccc;
  text-align: center;
  padding: 8px;
}

/* Table cells */
#pipelineTable td, #pipelineTable th {
  padding: 8px 10px;
  border: 1px solid #ddd;
}

/* Optional: nice scrollbar */
.table-wrapper::-webkit-scrollbar {
  width: 10px;
}
.table-wrapper::-webkit-scrollbar-thumb {
  background: #bbb;
  border-radius: 5px;
}
.table-wrapper::-webkit-scrollbar-thumb:hover {
  background: #999;
}

/* Status colors */
.status-dropdown.inactive { background-color: grey; color: white; }
.status-dropdown.preapp { background-color: #f0ad4e; color: white; }
.status-dropdown.onboarding { background-color: #ffc107; color: white; }
.status-dropdown.bank { background-color: #17a2b8; color: white; }
.status-dropdown.management { background-color: #dc3545; color: white; }
.status-dropdown.approved { background-color: #28a745; color: white; }
.status-dropdown.live { background-color: #007bff; color: white; }
.status-dropdown.closed { background-color: #6c757d; color: white; }
.status-dropdown.declined { background-color: #dc3545; color: white; }
  

/* I am not the orginal creator */
.toggleWrapper {
    /*position: absolute;
    /* top: 50%;
    padding: 0px 164px; 
    left: 86%;*/

    overflow: hidden;
    /* transform: translate3d(-50%, -50%, 0); */
    color: white;
}

.toggleWrapper .input {
  position: absolute;
  left: -99em;
}

.toggle {
  cursor: pointer;
  display: inline-block;
  position: relative;
  width: 65px;
  height: 25px;
  background-color: red;
  border-radius: 84px;
 
}

.toggle:before {
  content: "Yes";
  position: absolute;
  left: 7px;
  top: 3px;
  font-size: 13px;
  color: red;
}

.toggle:after {
  content: "No";
  position: absolute;
  right: 7px;
  top: 3px;
  font-size: 13px;
  color: white;
}

.toggle__handler {
  display: inline-block;
  position: relative;
  z-index: 1;
  top: 3px;
  left: 3px;
  width: 20px;
  height: 20px;
  background-color: white;
  border-radius: 50px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
  transition: all 400ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
  transform: rotate(-45deg);
}

.input:checked + .toggle {
  background-color: green;
}

.input:checked + .toggle:before {
  color: white;
}

.input:checked + .toggle:after {
  color: green;
}

.input:checked + .toggle .toggle__handler {
  background-color: white;
  transform: translate3d(40px, 0, 0) rotate(0);
}

.input:checked + .toggle .toggle__handler .crater {
  opacity: 1;
}

  
.badge-danger {
    background-color: #dc3545 !important;
    color: #fff !important;
}
</style>
<style>
/* ------------------------------- */
/* MOBILE RESPONSIVE FIXES         */
/* ------------------------------- */
@media (max-width: 768px) {

    /* Filter + Search: stack vertically */
    .row.mb-3 .col-md-4,
    .row.mb-3 .col-md-4.offset-md-4 {
        justify-content: flex-start !important;
        margin-bottom: 10px;
    }

    #statusFilter,
    #tableSearch {
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Make table more readable on mobile */
    #pipelineTable {
        font-size: 11px;
    }

    #pipelineTable th,
    #pipelineTable td {
        padding: 6px 6px;
        white-space: nowrap;
    }

    /* Scrollbars should stay visible */
    .scrollbar-top,
    .scrollbar-bottom {
        height: 12px;
    }
}

</style>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <a href="<?= site_url('reseller/pipeline_report/create'); ?>" class="btn btn-primary">Add New Application</a>
            <hr>
<!-- Filter + Search row -->
<div class="row mb-3 align-items-center">

    <!-- Left side: Filter -->
    <div class="col-md-4 col-sm-12 d-flex align-items-center mb-2">
        <label for="statusFilter" class="mr-2"><strong>Filter by Status:</strong></label>
        <select id="statusFilter" class="form-control" style="max-width:220px;">
            <option value="">All</option>
            <option value="Approved">Approved</option>
            <option value="Inactive">Inactive</option>
            <option value="PreAPP">PreAPP</option>
            <option value="Onboarding">Onboarding</option>
            <option value="Bank">Bank</option>
            <option value="Management">Management</option>
            <option value="Live">Live</option>
            <option value="Closed">Closed</option>
        </select>
    </div>

    <!-- Right side: Search -->
    <div class="col-md-4 offset-md-4 col-sm-12 d-flex justify-content-end align-items-center">
        <label for="tableSearch" class="mr-2"><strong>Search:</strong></label>
        <input type="text" id="tableSearch" class="form-control"
               placeholder="Search any column..."
               style="max-width:320px;">
    </div>

</div>



            <!-- Top horizontal scrollbar -->
            <div class="scrollbar-top"><div></div></div>

            <!-- Table wrapper with vertical scroll -->
            <div class="table-wrapper">
              <table class="table table-striped" id="pipelineTable">

                <thead>
                  <tr>
                    <th>Lead Source</th>
                    <th>Company Name</th>
                    <th>DBA Name</th>
                    <th>Location</th>

                    <?php
                      $rolevalue = $this->roles_model->get($current_user->role);
                      if (isset($rolevalue->name) && $rolevalue->name != "agent" && $rolevalue->name != "reseller") {
                    ?>
                      <th>Agent Name Contact</th>
                    <?php } ?>

                    <th>Email</th>
                    <th>Cell Phone Number</th>
                    <th>Bank Name</th>
                    <th>Date Recieved</th>
                    <th>Match/TMF</th>
                    <th>UBO</th>
                    <th>Copy</th>
                    <th>Manage</th>
                    <th>Alerts</th>
                    <th>Status</th>
                    <th>Assignment Type</th>
                    <th class="no-sort">Log</th>
                  </tr>
                </thead>

                <tbody>
                <?php $index = 1; foreach ($combined_list as $item): ?>
                  <tr>

                    <td><?= isset($item['lead_source']) ? htmlspecialchars($item['lead_source']) : ''; ?></td>
                    <td><?= htmlspecialchars($item['company_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($item['dba'] ?? '') ?></td>

                    <?php
                      $business_country = $item['user_business_country'] ?? '';
                      $pipeline_report_country = $item['pipeline_report_country'] ?? '';
                      $country_val = !empty($business_country) ? $business_country : $pipeline_report_country;
                      $country_name = '';
                      if (!empty($country_val)) {
                          if (is_numeric($country_val)) {
                              $country = get_country($country_val);
                              $country_name = $country ? $country->short_name : $country_val;
                          } else {
                              $country_name = $country_val;
                          }
                      }
                    ?>
                    <td><?= htmlspecialchars($country_name); ?></td>

                    <?php
                      $rolevalue = $this->roles_model->get($current_user->role);
                      if (isset($rolevalue->name) && $rolevalue->name != "agent" && $rolevalue->name != "reseller") {
                          $first_name = $item['staff_firstname'] ?? $item['first_name'] ?? '';
                          $last_name  = $item['staff_lastname'] ?? $item['last_name'] ?? '';
                          $full_name  = trim($first_name . ' ' . $last_name);
                    ?>
                      <td><?= htmlspecialchars($full_name); ?></td>
                    <?php } ?>

                    <td><?= htmlspecialchars($item['final_email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($item['business_phone'] ?? '') ?></td>

                    <?php
                      $bank_name = $item['pipeline_bank'] ?? '';
                      $pipeline_report_bank_name = $item['pipeline_report_bank_name'] ?? '';
                    ?>
                    <td><?= htmlspecialchars(!empty($bank_name) ? $bank_name : $pipeline_report_bank_name); ?></td>

                    <?php
                      $date_received = $item['created_at'] ?? '';
                      $application_date = $item['application_date'] ?? '';
                    ?>
                    <td><?= htmlspecialchars(!empty($date_received) ? $date_received : $application_date); ?></td>

                    <!-- Match/TMF toggle -->
                    <td>
                      <?php $math_id = 'match_tmf_' . $index++; ?>
                      <input class="input match-toggle"
                             data-field="match_tmf"
                             data-id="<?= htmlspecialchars($item['pipeline_report_id'] ?? '') ?>"
                             id="<?= $math_id ?>"
                             type="checkbox"
                             style="display:none"
                             <?= (isset($item['match_tmf']) && $item['match_tmf'] === 'Yes') ? 'checked' : '' ?> />
                      <label class="toggle" for="<?= $math_id ?>">
                        <span class="toggle__handler"></span>
                      </label>
                    </td>

                    <!-- UBO toggle -->
                    <td>
                      <?php $ubo_id = 'ubo_' . $index++; ?>
                      <input class="input match-toggle"
                             data-field="ubo"
                             data-id="<?= htmlspecialchars($item['pipeline_report_id'] ?? '') ?>"
                             id="<?= $ubo_id ?>"
                             type="checkbox"
                             style="display:none"
                             <?= (isset($item['ubo']) && $item['ubo'] === 'Yes') ? 'checked' : '' ?> />
                      <label class="toggle" for="<?= $ubo_id ?>">
                        <span class="toggle__handler"></span>
                      </label>
                    </td>

                    <!-- Copy -->
                    <td>
                      <a href="<?= site_url('reseller/pipeline_report/copy/' . ($item['pipeline_report_id'] ?? '')) ?>"
                         style="color:white"
                         class="btn btn-sm btn-primary">
                        <i class="fa fa-copy"></i> Copy
                      </a>
                    </td>

                    <!-- Manage -->
                    <td>
                      <?php if (($item['merchant_pipeline_status'] ?? '') == "New"): ?>
                        <?php if (!empty($item['ip_address'])): ?>
                          <a style="background:#90D5FF !important"
                             href="<?= site_url('reseller/pipeline_report/view/' . ($item['pipeline_report_id'] ?? '')) ?>"
                             class="btn btn-sm btn-info">Manage</a>
                        <?php else: ?>
                          <a style="background:#90D5FF !important"
                             href="<?= site_url('reseller/pipeline_report/create/' . ($item['app_id'] ?? '')) ?>"
                             class="btn btn-sm btn-info">Manage</a>
                        <?php endif; ?>
                      <?php else: ?>
                        <a style="background:#90D5FF !important"
                           href="<?= site_url('reseller/pipeline_report/view/' . ($item['pipeline_report_id'] ?? '')) ?>"
                           class="btn btn-sm btn-info">Manage</a>
                      <?php endif; ?>
                    </td>

                    <!-- Alerts -->
                    <td style="text-align:center !important">
                      <?php if (!empty($item['is_duplicate'])): ?>
                        <button class="btn btn-sm bg-danger view-duplicates"
                                data-key="<?= strtolower(trim($item['email_address'])) . '|' . trim($item['contact_phone']) . '|' . strtolower(trim($item['pipeline_report_bank_name'])) ?>">
                          Duplication Alerts
                        </button>
                      <?php endif; ?>
                    </td>

                    <!-- Status -->
                    <td>
                      <select style="height:30px !important;width:112px !important;"
                              class="status-dropdown form-control"
                              data-id="<?= htmlspecialchars($item['pipeline_report_id'] ?? '') ?>"
                              onchange="updateSelectColor(this)" disabled>
                        <option value="Inactive" <?= (($item['pipeline_merchant_status'] ?? '') == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                        <option value="PreAPP" <?= (strcasecmp($item['pipeline_merchant_status'] ?? '', 'PreAPP') === 0) ? 'selected' : '' ?>>PreAPP</option>
                        <option value="Onboarding" <?= (($item['pipeline_merchant_status'] ?? '') == 'Onboarding') ? 'selected' : '' ?>>Onboarding</option>
                        <option value="Bank" <?= (($item['pipeline_merchant_status'] ?? '') == 'Bank') ? 'selected' : '' ?>>Bank</option>
                        <option value="Management" <?= (($item['pipeline_merchant_status'] ?? '') == 'Management') ? 'selected' : '' ?>>Management</option>
                        <option value="Approved" <?= (($item['pipeline_merchant_status'] ?? '') == 'Approved') ? 'selected' : '' ?>>Approved</option>
                        <option value="Live" <?= (($item['pipeline_merchant_status'] ?? '') == 'Live') ? 'selected' : '' ?>>Live</option>
                        <option value="Closed" <?= (($item['pipeline_merchant_status'] ?? '') == 'Closed') ? 'selected' : '' ?>>Closed</option>
                        <option value="Declined" <?= (($item['pipeline_merchant_status'] ?? '') == 'Declined') ? 'selected' : '' ?>>Declined</option>
                      </select>
                    </td>

                    <td><?= htmlspecialchars($item['assignment_type'] ?? '') ?></td>

                    <!-- Log -->
                    <td>
                      <?php if(isset($item['pipeline_report_id'])): ?>
                        <a href="<?= site_url('reseller/pipeline_report/activity_log/' . ($item['pipeline_report_id'] ?? '')) ?>"
                           class="btn btn-sm btn-secondary">
                          <i class="fa fa-history me-1"></i> Log
                        </a>
                      <?php endif; ?>
                    </td>

                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div><!-- /.table-wrapper -->

      
        <!-- Bottom horizontal scrollbar -->
<div class="scrollbar-bottom"><div></div></div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Duplicate Details Modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="text-danger">
          <i class="fa fa-exclamation-triangle mr-2"></i>Duplicate Applications Detected
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="duplicateModalBody">Loading...</div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
 
<script>


  
document.addEventListener("DOMContentLoaded", function() {
  const tableWrapper = document.querySelector('.table-wrapper');
  const scrollbarTop = document.querySelector('.scrollbar-top');
  const scrollbarBottom = document.querySelector('.scrollbar-bottom');

  const table = document.querySelector('#pipelineTable');

// Adjust scrollbars width
function adjustScrollbar() {
    scrollbarTop.firstElementChild.style.width = table.scrollWidth + 'px';
    scrollbarBottom.firstElementChild.style.width = table.scrollWidth + 'px';
}
adjustScrollbar();

// Sync scrolls both ways
tableWrapper.addEventListener('scroll', () => {
    scrollbarTop.scrollLeft = tableWrapper.scrollLeft;
    scrollbarBottom.scrollLeft = tableWrapper.scrollLeft;
});

scrollbarTop.addEventListener('scroll', () => {
    tableWrapper.scrollLeft = scrollbarTop.scrollLeft;
    scrollbarBottom.scrollLeft = scrollbarTop.scrollLeft;
});

scrollbarBottom.addEventListener('scroll', () => {
    tableWrapper.scrollLeft = scrollbarBottom.scrollLeft;
    scrollbarTop.scrollLeft = scrollbarBottom.scrollLeft;
});


  window.addEventListener('resize', adjustScrollbar);

  // Status dropdown colors
  function updateSelectColor(select) {
    select.className = 'status-dropdown form-control';
    switch(select.value.toLowerCase()){
      case 'inactive': select.classList.add('inactive'); break;
      case 'preapp': select.classList.add('preapp'); break;
      case 'onboarding': select.classList.add('onboarding'); break;
      case 'bank': select.classList.add('bank'); break;
      case 'management': select.classList.add('management'); break;
      case 'approved': select.classList.add('approved'); break;
      case 'live': select.classList.add('live'); break;
      case 'closed': select.classList.add('closed'); break;
      case 'declined': select.classList.add('declined'); break;
    }
  }

  document.querySelectorAll('.status-dropdown').forEach(function(sel){
    updateSelectColor(sel);
    sel.addEventListener('change', () => updateSelectColor(sel));
  });
});
</script>

<script>
const combinedList = <?= json_encode($combined_list ?? []); ?>;
</script>
<script>

var admin_url = '<?= admin_url(); ?>';

function openCancelModal(id) {
    $('#cancelModalTitle').text("Cancel Application");
    $('#cancel_id').val(id);
    $('#cancel_reason').val('').prop('readonly', false);
    $('#cancelInfoSection').hide();
    $('#cancelSubmitBtn').show();
    $('#cancelModal').modal('show');
}

function viewCancelDetails(id) {
    $.ajax({
        url: admin_url + "pipeline_report/get_cancel_details",
        type: "POST",
        dataType: "json",
        data: { id: id },
        success: function (response) {
            if (response.success) {
                $('#cancelModalTitle').text("Cancelled Information");
                $('#cancel_id').val(id);
                $('#cancel_reason').val(response.data.cancel_reason).prop('readonly', true);
                $('#cancelled_by').text(response.data.cancelled_by_name);
                $('#cancelled_at').text(response.data.cancelled_at);
                $('#cancelInfoSection').show();
                $('#cancelSubmitBtn').hide();
                $('#cancelModal').modal('show');
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert("Something went wrong while fetching cancel details.");
        }
    });
}

function submitCancel() {
    var id = $('#cancel_id').val();
    var reason = $('#cancel_reason').val();

    if (reason.trim() === '') {
        alert("Please enter a reason for cancellation.");
        return;
    }

    $.ajax({
        url: admin_url + "pipeline_report/cancel_record",
        type: "POST",
        dataType: "json",
        data: { id: id, reason: reason },
        success: function (response) {
            if (response.success) {
                //alert(response.message);

                // Dynamically update the button to badge
                var cancelBtn = $('button[onclick="openCancelModal(' + id + ')"]');
                if (cancelBtn.length) {
                    //cancelBtn.replaceWith('<span class="badge badge-danger cursor-pointer" onclick="viewCancelDetails(' + id + ')">Cancelled</span>');
                  cancelBtn.replaceWith('<span style="background-color:#dc3545;color:white;cursor:pointer;padding:3px 8px;border-radius:4px;" onclick="viewCancelDetails(' + id + ')">Cancelled</span>');

                }

                $('#cancelModal').modal('hide');
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert("Something went wrong. Please try again.");
        }
    });
}
  
  $(document).on('click', '.delete-record', function() {
    var recordId = $(this).data('id');
    var recordType = $(this).data('type');
    
    $('#deleteRecordId').val(recordId);
    $('#deleteRecordType').val(recordType);
    $('#deleteReason').val('');
    $('#deleteModal').modal('show');
});


  
  
// -------------- End of Duplication Alert -------------- //  


$('.view-duplicates').click(function(){
    const key = $(this).data('key');
    console.log("Clicked key:", key);

    const matched = combinedList.filter(row => {
        const email = (row.final_email ?? '').toLowerCase().trim();
        const phone = (row.final_phone ?? '').trim();
        const bank  = (row.final_bank_name ?? '').toLowerCase().trim();
        const rowKey = email + '|' + phone + '|' + bank;

        console.log("Comparing with rowKey:", rowKey);

        return rowKey === key;
    });

    console.log("Matched count:", matched.length);

    let html = `
      <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
          <thead class="thead-dark">
            <tr>
              <th>Email</th>
              <th>Mobile Number</th>
              <th>Bank Name</th>
              <th>Application ID</th>
            </tr>
          </thead>
          <tbody>`;

    matched.forEach(row => {
        html += `
          <tr>
            <td><span class="badge badge-secondary">${row.final_email ?? '-'}</span></td>
            <td>${row.final_phone ?? '-'}</td>
            <td>${row.final_bank_name ?? '-'}</td>
            <td><strong>${row.pipeline_report_id ?? row.app_id ?? '-'}</strong></td>
          </tr>`;
    });

    html += `
          </tbody>
        </table>
      </div>
    `;

    $('#duplicateModalBody').html(html);
    $('#duplicateModal').modal('show');
});


// -------------- End of Duplication Alert -------------- //


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
            $.ajax({
                url: '<?= admin_url('pipeline_report/delete_multiple'); ?>',
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


      // Apply color on page load
    $('.status-dropdown').each(function () {
        updateStatusColor($(this), $(this).val());
    });
	
  

  function updateStatusColor(selectElement, status) {
        selectElement.removeClass('preapp onboarding underwriting bank management inactive approved live closed');

        switch (status.toLowerCase()) {
            case 'inactive':
                selectElement.addClass('inactive');
                break;
            case 'preapp':
                selectElement.addClass('preapp');
                break;
            case 'onboarding':
                selectElement.addClass('onboarding');
                break;
            case 'underwriting':
                selectElement.addClass('underwriting');
                break;
            case 'bank':
                selectElement.addClass('bank');
                break;         
            case 'management':
                selectElement.addClass('management');
                break;
            case 'approved':
                selectElement.addClass('approved');
                break;
            case 'live':
                selectElement.addClass('live');
                break;
            case 'closed':
                selectElement.addClass('closed');
                break;
        }
    }



    $(document).on('change', '.status-dropdown', function () {
        var $this = $(this); // Correctly define $this here
        updateStatusColor($this, $this.val());
        var $dropdown = $(this);
        var newStatus = $dropdown.val();
        var id = $dropdown.data('id');

        var $wrapper = $dropdown.closest('.status-wrapper');

        $.ajax({
            type: "POST",
            url: admin_url + "pipeline_report/update_status",
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

$(document).on('change', '.match-toggle', function () {
    const field = $(this).data('field'); // match_tmf or ubo
    const id = $(this).data('id');       // record ID
    const value = $(this).is(':checked') ? 'Yes' : 'No';

    $.ajax({
        url: "<?= admin_url('pipeline_report/update_toggle') ?>",
        type: "POST",
        data: {
            id: id,
            field: field,
            value: value
        },
        success: function (response) {
            console.log('Updated successfully:', response);
        },
        error: function () {
            alert('Failed to update. Please try again.');
        }
    });
});
function updateSelectColor(selectElement) {
    if (!selectElement || !selectElement.classList) return; // safety

    // Remove all status classes
    selectElement.classList.remove(
        'preapp','onboarding','underwriting','bank','declined',
        'management','inactive', 'approved', 'live', 'closed'
    );

    // Add class based on selected value
    const status = selectElement.value?.toLowerCase(); // like "approved"
    if (status) {
        selectElement.classList.add(status);
    }
}


// On page load, initialize all status-dropdowns with the right color
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".status-dropdown").forEach(function (el) {
        updateSelectColor(el);
    });
});

$(document).on('change', '.match-toggle', function () {
  const field = $(this).data('field');
  const id = $(this).data('id');
  const value = $(this).is(':checked') ? 'Yes' : 'No';

  $.ajax({
    url: "<?= site_url('reseller/pipeline_report/update_toggle') ?>",
    type: "POST",
    data: { id: id, field: field, value: value },
    error: function () { alert('Failed to update. Please try again.'); }
  });
});
</script>

<?php
$rolevalue = $this->roles_model->get($current_user->role);
if (isset($rolevalue->name) && in_array($rolevalue->name, ["agent", "reseller", "partner admin"])): ?>
<script>
  document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
  document.onkeydown = function(e) {
    if (
      e.keyCode === 123 ||
      (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) ||
      (e.ctrlKey && e.keyCode === 85)
    ) return false;
  };
</script>
<?php endif; ?>

</script>
<script>
$(document).ready(function () {

  // ✅ Filter by Status (Improved)
  $('#statusFilter').on('change', function () {
    const selectedStatus = $(this).val().toLowerCase().trim();

    $('#pipelineTable tbody tr').each(function () {
      const $row = $(this);

      // find the "main" merchant status select in this row (ignore cancel ones)
      const $statusSelect = $row.find('select.status-dropdown')
                                .not('[data-field], [data-cancel]')
                                .first();

      const currentStatus = ($statusSelect.find('option:selected').text() || '').toLowerCase().trim();

      if (selectedStatus === '' || selectedStatus === 'all') {
        $row.show(); // show all if All selected
      } else if (currentStatus === selectedStatus) {
        $row.show();
      } else {
        $row.hide();
      }
    });
  });

});

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

  // Handle Cancel Yes/No select
  $(document).on('change', '.status-dropdown[data-id]', function() {
      const dropdown = $(this);
      const pipelineId = dropdown.data('id');
      const selectedValue = dropdown.val();

      // Ask for confirmation before updating
      Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to change Cancel status to '" + selectedValue + "'?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, update it!',
          cancelButtonText: 'No, keep it'
      }).then((result) => {
          if (result.isConfirmed) {
              // Proceed with AJAX update
              $.ajax({
                  url: admin_url + 'pipeline_report/update_cancel_status',
                  type: 'POST',
                  data: {
                      id: pipelineId,
                      status: selectedValue
                  },
                  dataType: 'json',
                  success: function(response) {
                      if (response.success) {
                          Swal.fire({
                              icon: 'success',
                              title: 'Updated!',
                              text: 'Cancel status has been updated successfully.',
                              timer: 1500,
                              showConfirmButton: false
                          });
                      } else {
                          Swal.fire('Error', 'Unable to update status. Try again.', 'error');
                      }
                  },
                  error: function() {
                      Swal.fire('Error', 'AJAX request failed. Check console.', 'error');
                      console.error('AJAX failed for cancel update');
                  }
              });
          } else {
              // Revert to old value if cancelled
              dropdown.val(dropdown.data('previous'));
          }
      });
  });

  // Store previous value to restore if cancelled
  $(document).on('focus', '.status-dropdown[data-id]', function() {
      $(this).data('previous', $(this).val());
  });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const table = document.getElementById("pipelineTable");
  const tbody = table.querySelector("tbody");
  const searchInput = document.getElementById("tableSearch");
  const statusFilter = document.getElementById("statusFilter");

  // Make headers sortable
  const headers = table.querySelectorAll("thead th");
  const sortState = Array(headers.length).fill(null); // null / 'asc' / 'desc'
  headers.forEach((th, idx) => {
    // Prevent making the last few action columns sortable (e.g. buttons)
    // You can add/remove indexes as needed. Here we skip if cell contains button/icon
    if (th.querySelector('a, button, .fa, .btn')) {
      return;
    }
    th.classList.add('sortable');
    const indicator = document.createElement('span');
    indicator.className = 'sort-indicator';
    th.appendChild(indicator);

    th.addEventListener('click', () => {
      const current = sortState[idx];
      const next = current === 'asc' ? 'desc' : 'asc';
      sortState.fill(null);
      sortState[idx] = next;
      headers.forEach((h,i) => {
        const ind = h.querySelector('.sort-indicator');
        if(!ind) return;
        ind.textContent = sortState[i] === 'asc' ? '▲' : (sortState[i] === 'desc' ? '▼' : '');
      });
      sortTableByColumn(idx, next === 'asc');
    });
  });

  function parseCellValue(text) {
    if (!text) return '';
    const trimmed = text.trim();
    // Try number
    const num = trimmed.replace(/[^0-9\.\-]/g, '');
    if (num !== '' && !isNaN(num)) return parseFloat(num);
    // Try date (basic ISO or common formats)
    const date = Date.parse(trimmed);
    if (!isNaN(date)) return date;
    // fallback string
    return trimmed.toLowerCase();
  }

  function sortTableByColumn(colIndex, asc = true) {
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.sort((a, b) => {
      const aText = (a.children[colIndex] && a.children[colIndex].innerText) ? a.children[colIndex].innerText : '';
      const bText = (b.children[colIndex] && b.children[colIndex].innerText) ? b.children[colIndex].innerText : '';
      const A = parseCellValue(aText);
      const B = parseCellValue(bText);
      if (A === B) return 0;
      if (typeof A === 'number' && typeof B === 'number') return asc ? A - B : B - A;
      if (typeof A === 'number' || typeof B === 'number') return asc ? (isNaN(A) ? 1 : -1) : (isNaN(B) ? -1 : 1);
      return asc ? (A > B ? 1 : -1) : (A > B ? -1 : 1);
    });
    rows.forEach(r => tbody.appendChild(r));
    updateShowingText();
  }

  // Live search: filters by search input and status filter
  function filterRows() {
    const term = (searchInput.value || '').trim().toLowerCase();
    const statusVal = (statusFilter.value || '').toLowerCase();
    const rows = tbody.querySelectorAll('tr');

    rows.forEach(row => {
      let text = row.innerText.toLowerCase();
      // If status filter is selected, ensure row's Status column contains it
      let statusMatch = true;
      if (statusVal) {
        // find Status cell text — attempt to find header index for 'Status'
        const statusIndex = findHeaderIndexByText('status');
        if (statusIndex !== -1) {
          const statusCell = row.children[statusIndex];
          const statusText = statusCell ? statusCell.innerText.toLowerCase() : '';
          statusMatch = statusText.includes(statusVal);
        }
      }
      const searchMatch = term === '' || text.includes(term);
      row.style.display = (statusMatch && searchMatch) ? '' : 'none';
    });

    updateShowingText();
  }

  // utility to find header index by header text (case-insensitive)
  function findHeaderIndexByText(txt) {
    for (let i = 0; i < headers.length; i++) {
      if (headers[i].innerText.trim().toLowerCase().startsWith(txt.toLowerCase())) return i;
    }
    return -1;
  }

  // update "Showing X–Y of Z rows" — uses server-provided total_rows if available
  function updateShowingText() {
    // look for the paragraph element we used below (class="pipeline-showing")
    let p = document.querySelector('.pipeline-showing');
    if (!p) return;
    const visibleRows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.style.display !== 'none');
    const allRowsCount = tbody.querySelectorAll('tr').length;
    // If server gives total rows in a data attribute on the table (recommended),
    // e.g. <table id="pipelineTable" data-total-rows="<?= $total_rows ?>">
    const totalRowsAttr = parseInt(table.getAttribute('data-total-rows')) || allRowsCount;
    // If you are using server pagination show rows for current page:
    const start = visibleRows.length ? ( (<?php echo (int)($pagination['current_page'] ?? 1); ?> - 1) * <?php echo (int)(20); ?> + 1 ) : 0;
    const end = start + visibleRows.length - 1;
    p.innerText = `Showing ${start}–${end} of ${totalRowsAttr} rows`;
  }

  // event listeners
  searchInput.addEventListener('input', filterRows);
  statusFilter.addEventListener('change', filterRows);

  // Initial update (and call once to populate showing text)
  updateShowingText();

  // If your table data changes async, call updateShowingText() afterwards
});
</script>


