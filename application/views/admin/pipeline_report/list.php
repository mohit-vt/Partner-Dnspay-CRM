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
th.sortable::after { content: ""; } /* disable the fixed arrow */

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

.status-dropdown.yes { background-color: #008000; color: #fff; }
.status-dropdown.no  { background-color: #dc3545; color: #fff; }

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
/* Status colors (FULL) — target merchant-status-dropdown directly */
.merchant-status-dropdown.status-all { background:#6c757d !important; color:#fff !important; }

.merchant-status-dropdown.status-inactive { background:#adb5bd !important; color:#fff !important; }
.merchant-status-dropdown.status-preapp { background:#f0ad4e !important; color:#fff !important; }
.merchant-status-dropdown.status-onboarding { background:#ffc107 !important; color:#111 !important; }
.merchant-status-dropdown.status-bank { background:#17a2b8 !important; color:#fff !important; }
.merchant-status-dropdown.status-management { background:#dc3545 !important; color:#fff !important; }

.merchant-status-dropdown.status-approved { background:#28a745 !important; color:#fff !important; }
.merchant-status-dropdown.status-approved-collections { background:#20c997 !important; color:#fff !important; }
.merchant-status-dropdown.status-approved-conditional { background:#198754 !important; color:#fff !important; }

.merchant-status-dropdown.status-closed { background:#6c757d !important; color:#fff !important; }
.merchant-status-dropdown.status-closed-collections { background:#495057 !important; color:#fff !important; }
.merchant-status-dropdown.status-closed-risk { background:#343a40 !important; color:#fff !important; }

.merchant-status-dropdown.status-collections { background:#0d6efd !important; color:#fff !important; }
.merchant-status-dropdown.status-declined { background:#b02a37 !important; color:#fff !important; }
.merchant-status-dropdown.status-declined-agent-ai { background:#8b0000 !important; color:#fff !important; }

.merchant-status-dropdown.status-enrollment { background:#6610f2 !important; color:#fff !important; }
.merchant-status-dropdown.status-fraud { background:#fd7e14 !important; color:#fff !important; }
.merchant-status-dropdown.status-import-pending { background:#0dcaf0 !important; color:#111 !important; }
.merchant-status-dropdown.status-pending { background:#ffc107 !important; color:#111 !important; }
.merchant-status-dropdown.status-withdrawn { background:#795548 !important; color:#fff !important; }

.merchant-status-dropdown.status-advanced-search { background:#3f51b5 !important; color:#fff !important; }
  

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
            <a href="<?= admin_url('pipeline_report/create'); ?>" class="btn btn-primary">Add New Application</a>
            <hr>
<!-- Filter + Search row -->
<div class="row mb-3 align-items-center">

    <!-- Left side: Filter -->
    <div class="col-md-4 col-sm-12 d-flex align-items-center mb-2">
        <label for="statusFilter" class="mr-2"><strong>Filter by Status:</strong></label>
        <select id="statusFilter" class="form-control" style="max-width:220px;">
    <option value="">All</option>

    <option value="All">All</option>
    <option value="Inactive">Inactive</option>
    <option value="PreAPP">PreAPP</option>
    <option value="Onboarding">Onboarding</option>
    <option value="Bank">Bank</option>
    <option value="Management">Management</option>

    <option value="Approved">Approved</option>
    <option value="Approved - Collections">Approved - Collections</option>
    <option value="Approved - Conditional">Approved - Conditional</option>

    <option value="Closed">Closed</option>
    <option value="Closed - Collections">Closed - Collections</option>
    <option value="Closed - Risk">Closed - Risk</option>

    <option value="Collections">Collections</option>

    <option value="Declined">Declined</option>
    <option value="Declined - Agent-Ai">Declined - Agent-Ai</option>

    <option value="Enrollment">Enrollment</option>
    <option value="Fraud">Fraud</option>
    <option value="Import Pending">Import Pending</option>
    <option value="Pending">Pending</option>
    <option value="Withdrawn">Withdrawn</option>

    <option value="Advanced Search">Advanced Search</option>
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
            <th>Agent Name</th> <!-- Always included, hide via CSS if not needed -->
            <th>Owner</th>
            <th>Email</th>
            <th>Cell Phone Number</th>
            <th>Bank Name</th>
            <th>Date Recieved</th>
          	<th>IP Address</th>
            <th>Browser</th>
            <th>Match/TMF</th>
            <th>UBO</th>
          	<th>Social Media</th>
            <th>Status</th>
            <th>Copy</th>
            <th>Manage</th>
            <th>Alerts</th>
          <th>Download</th>
            <th>Activity Log</th>
           <th class="<?= (!is_admin()) ? 'd-none' : '' ?>">Delete</th>
          <th>Archieved</th>
          <th>Cancel</th>
           <th>Application Source</th>
        </tr>
    </thead>
              
                <tbody>
                  <?php  $index = 1; foreach ($combined_list as $item): 
                
// Detect the record type
$is_preapp = (isset($item['merchant_status_final']) && strcasecmp($item['merchant_status_final'], 'PreAPP') === 0);
$record_id = $is_preapp ? ($item['app_id'] ?? '') : ($item['pipeline_report_id'] ?? '');
$record_status = $is_preapp ? 'PreApp' : 'pipeline';
?>
        <tr>

           
     <td><?= isset($item['lead_source']) ? htmlspecialchars($item['lead_source']) : ''; ?></td>
    <td>
        <?= htmlspecialchars($item['final_company'] ?? '') ?>
    </td>

 
            <?php
            $business_system = isset($item['business_system']) ? $item['business_system'] : '';
            $dba = isset($item['dba']) ? $item['dba'] : '';
            ?>
            <td><?= htmlspecialchars(!empty($business_system) ? $business_system : $dba); ?></td>
            <?php
            $business_country = isset($item['user_business_country']) ? $item['user_business_country'] : '';
            $pipeline_report_country = isset($item['pipeline_report_country']) ? $item['pipeline_report_country'] : '';

            $country_val = !empty($business_country) ? $business_country : $pipeline_report_country;

            $country_name = '';
            if (!empty($country_val)) {
                if (is_numeric($country_val)) {
                    // It's an ID → get country name
                    $country = get_country($country_val);
                    $country_name = $country ? $country->short_name : $country_val;
                } else {
                    // It's already a name → use directly
                    $country_name = $country_val;
                }
            }
            ?>
    <td><?= htmlspecialchars($country_name); ?></td>
      <!-- Agent Name -->
            <?php
            $rolevalue = $this->roles_model->get($current_user->role);
            $first_name = $item['staff_firstname'] ?? $item['first_name'] ?? '';
            $last_name  = $item['staff_lastname'] ?? $item['last_name'] ?? '';
            $full_name  = trim($first_name . ' ' . $last_name);
            ?>
            <!--<td class="<?= ($rolevalue->name == 'agent') ? 'd-none' : '' ?>">
                <?= ($rolevalue->name != 'agent') ? htmlspecialchars($full_name) : ''; ?>
            </td>-->
    <td>
        <?php
        $agentId = $item['assigned_to_agent'] ?? null;
        echo isset($agent_map[$agentId]) 
            ? htmlspecialchars($agent_map[$agentId]) 
            : '';
        ?>
    </td>
    <td>
        <?= htmlspecialchars(trim(($item['final_first_name'] ?? '') . ' ' . ($item['final_last_name'] ?? ''))) ?>
    </td>
    <td>
        <?= htmlspecialchars($item['final_email'] ?? '') ?>
    </td>
    <td>
        <?= htmlspecialchars($item['final_phone'] ?? '') ?>
    </td>
    <td>
        <?= htmlspecialchars($item['final_bank_name'] ?? '') ?>
    </td>
          
    <td>
        <?= htmlspecialchars($item['final_created_at'] ?? '') ?>
    </td>
    <td><?= htmlspecialchars($item['ip_address'] ?? '') ?></td>

<td><?= htmlspecialchars($item['user_agent'] ?? '') ?></td>


<td>

<?php if(isset($item['merchant_pipeline_status']) && $item['merchant_pipeline_status'] == "New"){ ?>


        <?php }else if((isset($item['merchant_pipeline_status']) && $item['merchant_pipeline_status'] == "Completed")){ ?>
    <?php $math_id = 'match_tmf_' . $index++; ?>
    <input class="input match-toggle" data-field="match_tmf" data-id="<?= isset($item['pipeline_report_id']) ? htmlspecialchars($item['pipeline_report_id']) : '' ?>" id="<?= $math_id ?>" type="checkbox" style="display:none" <?= (isset($item['match_tmf']) && $item['match_tmf'] === 'Yes') ? 'checked' : '' ?> />
    <label class="toggle" for="<?= $math_id ?>">
        <span class="toggle__handler"></span>
    </label>
         <?php }else{ ?>
    <?php //$math_id = 'match_tmf_' . $index++; ?>
    <!--<input class="input match-toggle" data-field="match_tmf" data-id="<?= isset($item['pipeline_report_id']) ? htmlspecialchars($item['pipeline_report_id']) : '' ?>" id="<?= $math_id ?>" type="checkbox" style="display:none" <?= (isset($item['match_tmf']) && $item['match_tmf'] === 'Yes') ? 'checked' : '' ?> />
    <label class="toggle" for="<?= $math_id ?>">
        <span class="toggle__handler"></span>
    </label>-->
          <?php
$math_id = 'match_tmf_' . $index++;
?>
<input class="input match-toggle"
       data-field="match_tmf"
       data-id="<?= htmlspecialchars($item['pipeline_report_id'] ?? '') ?>"
       id="<?= $math_id ?>"
       type="checkbox"
       style="display:none"
       <?= (!empty($item['match_tmf']) && ($item['match_tmf'] == 1 || strtolower($item['match_tmf']) === 'yes')) ? 'checked' : '' ?> />
<label class="toggle" for="<?= $math_id ?>">
    <span class="toggle__handler"></span>
</label>

       <?php } ?>
    </td>
    <td>
    <?php if(isset($item['merchant_pipeline_status']) && $item['merchant_pipeline_status'] == "New"){ ?>
    <?php }else if((isset($item['merchant_pipeline_status']) && $item['merchant_pipeline_status'] == "Completed")){ ?>
    <?php $ubo_id = 'ubo_' . $index++; ?>
    <input class="input match-toggle" data-field="ubo" data-id="<?= isset($item['pipeline_report_id']) ? htmlspecialchars($item['pipeline_report_id']) : '' ?>" id="<?= $ubo_id ?>" type="checkbox" style="display:none" <?= (isset($item['ubo']) && $item['ubo'] === 'Yes') ? 'checked' : '' ?> />
    <label class="toggle" for="<?= $ubo_id ?>">
        <span class="toggle__handler"></span>
             <?php }else{ ?>
                    <?php $ubo_id = 'ubo_' . $index++; ?>
    <input class="input match-toggle" data-field="ubo" data-id="<?= isset($item['pipeline_report_id']) ? htmlspecialchars($item['pipeline_report_id']) : '' ?>" id="<?= $ubo_id ?>" type="checkbox" style="display:none" <?= (isset($item['ubo']) && $item['ubo'] === 'Yes') ? 'checked' : '' ?> />
    <label class="toggle" for="<?= $ubo_id ?>">
        <span class="toggle__handler"></span>
          <?php } ?>
    </label>
</td>
<td>
  <select class="form-control social-media-select" 
          data-id="<?= $item['pipeline_report_id'] ?? $item['id'] ?>" 
          style="height: 30px; width: 150px;">
      <option value="">Select</option>
      <option value="X" <?= ($item['social_media'] ?? '') == 'X' ? 'selected' : '' ?>>X</option>
      <option value="Instagram" <?= ($item['social_media'] ?? '') == 'Instagram' ? 'selected' : '' ?>>Instagram</option>
      <option value="TikTok" <?= ($item['social_media'] ?? '') == 'TikTok' ? 'selected' : '' ?>>TikTok</option>
      <option value="YouTube" <?= ($item['social_media'] ?? '') == 'YouTube' ? 'selected' : '' ?>>YouTube</option>
      <option value="LinkedIn" <?= ($item['social_media'] ?? '') == 'LinkedIn' ? 'selected' : '' ?>>LinkedIn</option>
      <option value="Facebook" <?= ($item['social_media'] ?? '') == 'Facebook' ? 'selected' : '' ?>>Facebook</option>
      <option value="Other" <?= ($item['social_media'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
  </select>
</td>

<td>
<?php if ($pre_application_merchant_status == "true") { ?>  

  <?php
    // Determine row type
    $type = $item['source_type'] ?? (!empty($item['pipeline_report_id']) ? 'pipeline' : 'preapp');

    // Correct record id
    $rid  = ($type === 'preapp') ? ($item['app_id'] ?? '') : ($item['pipeline_report_id'] ?? '');

    // Correct current status (THIS is what should control selected option)
    $currentStatus = ($type === 'preapp')
        ? ($item['preapp_merchant_status'] ?? '')
        : ($item['pipeline_merchant_status'] ?? '');

    $currentStatusNorm = strtolower(trim($currentStatus));
  ?>

<select
    style="height: 30px !important;width: 112px !important;"
    class="status-dropdown form-control merchant-status-dropdown"
    data-id="<?= htmlspecialchars($rid) ?>"
    data-type="<?= htmlspecialchars($type) ?>"
    onchange="updateSelectColor(this)">

    <option value="All" <?= ($currentStatusNorm === 'all') ? 'selected' : '' ?>>All</option>

    <option value="Inactive" <?= ($currentStatusNorm === 'inactive') ? 'selected' : '' ?>>Inactive</option>
    <option value="PreAPP" <?= ($currentStatusNorm === 'preapp') ? 'selected' : '' ?>>PreAPP</option>
    <option value="Onboarding" <?= ($currentStatusNorm === 'onboarding') ? 'selected' : '' ?>>Onboarding</option>
    <option value="Bank" <?= ($currentStatusNorm === 'bank') ? 'selected' : '' ?>>Bank</option>
    <option value="Management" <?= ($currentStatusNorm === 'management') ? 'selected' : '' ?>>Management</option>

    <option value="Approved" <?= ($currentStatusNorm === 'approved') ? 'selected' : '' ?>>Approved</option>
    <option value="Approved - Collections" <?= ($currentStatusNorm === 'approved - collections') ? 'selected' : '' ?>>Approved - Collections</option>
    <option value="Approved - Conditional" <?= ($currentStatusNorm === 'approved - conditional') ? 'selected' : '' ?>>Approved - Conditional</option>

    <option value="Closed" <?= ($currentStatusNorm === 'closed') ? 'selected' : '' ?>>Closed</option>
    <option value="Closed - Collections" <?= ($currentStatusNorm === 'closed - collections') ? 'selected' : '' ?>>Closed - Collections</option>
    <option value="Closed - Risk" <?= ($currentStatusNorm === 'closed - risk') ? 'selected' : '' ?>>Closed - Risk</option>

    <option value="Collections" <?= ($currentStatusNorm === 'collections') ? 'selected' : '' ?>>Collections</option>

    <option value="Declined" <?= ($currentStatusNorm === 'declined') ? 'selected' : '' ?>>Declined</option>
    <option value="Declined - Agent-Ai" <?= ($currentStatusNorm === 'declined - agent-ai') ? 'selected' : '' ?>>Declined - Agent-Ai</option>

    <option value="Enrollment" <?= ($currentStatusNorm === 'enrollment') ? 'selected' : '' ?>>Enrollment</option>
    <option value="Fraud" <?= ($currentStatusNorm === 'fraud') ? 'selected' : '' ?>>Fraud</option>
    <option value="Import Pending" <?= ($currentStatusNorm === 'import pending') ? 'selected' : '' ?>>Import Pending</option>
    <option value="Pending" <?= ($currentStatusNorm === 'pending') ? 'selected' : '' ?>>Pending</option>
    <option value="Withdrawn" <?= ($currentStatusNorm === 'withdrawn') ? 'selected' : '' ?>>Withdrawn</option>

    <option value="Advanced Search" <?= ($currentStatusNorm === 'advanced search') ? 'selected' : '' ?>>Advanced Search</option>

</select>

<?php } else { ?>
  <button
      class="btn btn-sm cancel-button <?= (isset($item['merchant_status']) && $item['merchant_status'] === 'Cancel') ? 'btn-danger' : 'btn-warning'; ?>"
      data-id="<?= $item['id']; ?>"
      data-status="<?= isset($item['merchant_status']) ? $item['merchant_status'] : ''; ?>">
      <?= (isset($item['merchant_status']) && $item['merchant_status'] === 'Cancel') ? 'Cancelled' : 'Cancel'; ?>
  </button>
<?php } ?>
</td>

    <td>
<?php if(isset($item['merchant_pipeline_status']) && $item['merchant_pipeline_status'] == "New"){ ?>



        <?php }else if((isset($item['merchant_pipeline_status']) && $item['merchant_pipeline_status'] == "Completed")){ ?>

        <a href="<?= admin_url('pipeline_report/copy/' . ($item['pipeline_report_id'] ?? '')) ?>" style="color:white" class="btn btn-sm btn-primary"><i class="fa fa-copy"></i> Copy </a>
      
       <?php }else{ ?>

        <a href="<?= admin_url('pipeline_report/copy/' . ($item['pipeline_report_id'] ?? '')) ?>" style="color:white" class="btn btn-sm btn-primary"><i class="fa fa-copy"></i> Copy </a>
        
        <?php } ?>
    </td>
    <td>
   <?php if(isset($item['merchant_pipeline_status']) && $item['merchant_pipeline_status'] == "New"){ ?>

        <a style="background:#90D5FF !important" href="<?php echo admin_url('pipeline_report/create/' . ($item['app_id'] ?? '')); ?>" class="btn btn-sm btn-info">Manage</a>

      
       <?php }else{ ?>

        <a style="background:#90D5FF !important" href="<?php echo admin_url('pipeline_report/view/' . ($item['pipeline_report_id'] ?? '')); ?>" class="btn btn-sm btn-info">Manage</a>

        
        <?php } ?>
    </td>
<td style="text-align:center !important">
    <?php if (!empty($item['is_duplicate'])): ?>
        <button class="btn btn-sm bg-danger view-duplicates"
                data-key="<?= strtolower(trim($item['final_email'])) . '|' . trim($item['final_phone']) . '|' . strtolower(trim($item['final_bank_name'])) ?>">
            Duplication Alerts
        </button>
    <?php endif; ?>
</td>
     <td>
<a style="background:#6c757d !important;color: white;" 
   href="<?= admin_url('pipeline_report/download_pdf/' . ($item['pipeline_report_id'] ?? '')) ?>" 
   class="btn btn-sm btn-secondary" target="_blank">
   <i class="fa fa-file-pdf-o"></i> Download
</a>


     </td>

    <td>
         <?php if(isset($item['pipeline_report_id'])){ ?>
        <a href="<?= admin_url('pipeline_report/activity_log/' . ($item['pipeline_report_id'] ?? '')) ?>" 
        class="btn btn-sm btn-secondary">
        <i class="fa fa-history me-1"></i> Log
        </a>
          <?php } ?>

    </td>
<td class="<?= (!is_admin()) ? 'd-none' : '' ?>">
        <?php if (is_admin()): ?>
            <button class="btn btn-sm btn-danger delete-record"
                    data-id="<?= $record_id ?>"
                    data-type="<?= $record_status ?>"
                    data-status="<?= $record_status ?>">
                <i class="fa fa-trash"></i> Delete
            </button>
        <?php endif; ?>
              </td>
    <td class="<?= (!is_admin()) ? 'd-none' : '' ?>">
        <?php if (is_admin()): ?>
            <button class="btn btn-sm btn-secondary archive-record"
                    data-id="<?= $record_id ?>"
                    data-status="<?= $record_status ?>">
                <i class="fa fa-archive"></i> Archive
            </button>
        <?php endif; ?>
    </td>

    <td>
        <select style="height:30px !important"
                class="form-control status-dropdown cancel-status-dropdown"
                data-id="<?= $record_id ?>"
                data-status="<?= $record_status ?>">
            <option value="Yes" <?= ($item['pipeline_cancel_status'] ?? '') == 'Yes' ? 'selected' : '' ?>>Yes</option>
            <option value="No" <?= ($item['pipeline_cancel_status'] ?? '') == 'No' ? 'selected' : '' ?>>No</option>
        </select>
    </td>
    <td>
    <?= htmlspecialchars($item['application_source'] ?? '') ?>
</td>

    </tr>
        <?php endforeach; ?>
    </tbody>
              </table>
            </div><!-- /.table-wrapper -->
     <?php if (!empty($combined_list)): 
    // rows per page (keep in sync with controller)
    $per_page = 20;

    // current page and row counts (fallbacks if not present)
    $current_page = isset($pagination['current_page']) ? (int)$pagination['current_page'] : 1;
    $rows_this_page = count($combined_list);

    // start and end for the current page
    $start = ($current_page - 1) * $per_page + 1;
    $end = $start + $rows_this_page - 1;

    // Use server-provided total_rows when possible (recommended)
    if (isset($pagination['total_rows'])) {
        $total_rows = (int)$pagination['total_rows'];
    } elseif (isset($total_rows)) {
        // controller might have passed total_rows separately
        $total_rows = (int)$total_rows;
    } else {
        // fallback: estimate from total_pages if present
        $total_rows = isset($pagination['total_pages']) ? ($pagination['total_pages'] * $per_page) : $rows_this_page;
    }
?>
<p class="text-muted mb-1 pipeline-showing">
    Showing <?= $start ?>–<?= $end ?> of <?= $total_rows ?> rows
</p>
<?php endif; ?>
      
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
        <h5 class="text-danger"><i class="fa fa-exclamation-triangle mr-2"></i>Duplicate Applications Detected</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="duplicateModalBody">
        Loading...
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal 
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title" id="deleteModalLabel">Confirm Deletion</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="deleteReason">Reason for deletion:</label>
                    <textarea class="form-control" id="deleteReason" rows="3" required></textarea>
                </div>
                <input type="hidden" id="deleteRecordId">
                <input type="hidden" id="deleteRecordType">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>-->

<!-- Cancel Confirmation Modal -->

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="cancelModalTitle">Cancel Application</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">
        <input type="hidden" id="cancel_id">
        
        <div id="cancelInfoSection" style="display:none;">
          <p><strong>Cancelled By:</strong> <span id="cancelled_by"></span></p>
          <p><strong>Cancelled At:</strong> <span id="cancelled_at"></span></p>
        </div>
        
        <div class="form-group">
          <label for="cancel_reason">Reason for Cancel</label>
          <textarea id="cancel_reason" class="form-control" required></textarea>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="cancelSubmitBtn" onclick="submitCancel()">Cancel</button>
      </div>
      
    </div>
  </div>
</div>

<?php init_tail(); ?>
 
<script>
// Handle social media dropdown change
$(document).on('change', '.social-media-select', function() {
    var id = $(this).data('id');
    var platform = $(this).val();

    $.ajax({
        url: admin_url + 'pipeline_report/update_social_media',
        type: 'POST',
        dataType: 'json',
        data: { id: id, platform: platform },
        success: function(response) {
            if (response.success) {
                alert_float('success', 'Social media updated successfully');
            } else {
                alert_float('danger', 'Failed to update social media');
            }
        },
        error: function() {
            alert_float('danger', 'Server error while updating social media');
        }
    });
});




  
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
   // $('.status-dropdown').each(function () {
    //    updateStatusColor($(this), $(this).val());
   // });
	
  

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

var admin_url = '<?= admin_url(); ?>';
var UPDATE_STATUS_URL = "<?= admin_url('pipeline_report/update_status'); ?>";

function statusToClass(val) {
  const v = (val || '').toLowerCase().trim();
  const map = {
    'all': 'status-all',
    'inactive': 'status-inactive',
    'preapp': 'status-preapp',
    'onboarding': 'status-onboarding',
    'bank': 'status-bank',
    'management': 'status-management',

    'approved': 'status-approved',
    'approved - collections': 'status-approved-collections',
    'approved - conditional': 'status-approved-conditional',

    'closed': 'status-closed',
    'closed - collections': 'status-closed-collections',
    'closed - risk': 'status-closed-risk',

    'collections': 'status-collections',

    'declined': 'status-declined',
    'declined - agent-ai': 'status-declined-agent-ai',

    'enrollment': 'status-enrollment',
    'fraud': 'status-fraud',
    'import pending': 'status-import-pending',
    'pending': 'status-pending',
    'withdrawn': 'status-withdrawn',

    'advanced search': 'status-advanced-search'
  };

  return map[v] || ('status-' + v.replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''));
}

function applyStatusClass(el) {
  if (!el || !el.classList) return;

  // remove previous status-* classes
  [...el.classList].forEach(c => {
    if (c.startsWith('status-')) el.classList.remove(c);
  });

  // ensure it always has base class
  el.classList.add('merchant-status-dropdown');

  // add current status class
  el.classList.add(statusToClass(el.value));
}

function updateSelectColor(selectElement) {
  applyStatusClass(selectElement);
}

// ✅ run once after everything loads (initial coloring)
window.addEventListener('load', function () {
  document.querySelectorAll('.merchant-status-dropdown').forEach(applyStatusClass);
});

// ✅ handle user changes (delegated)
document.addEventListener('change', function(e){
  if (e.target && e.target.classList && e.target.classList.contains('merchant-status-dropdown')) {
    applyStatusClass(e.target);
  }
});

$(document).ready(function () {

  // init dropdown colors once
  document.querySelectorAll(".merchant-status-dropdown").forEach(function (el) {
    updateSelectColor(el);
  });

 $(document).on('change', '.merchant-status-dropdown', function () {
  const id = $(this).data('id');
  const type = $(this).data('type'); // pipeline or preapp
  const status = $(this).val();

  updateSelectColor(this);

  $.ajax({
    type: "POST",
    url: UPDATE_STATUS_URL,
    dataType: "json",
    data: { id: id, type: type, status: status }, // ✅ include type
    success: function(res){
      if(!res.success){
        alert_float('danger', res.message || 'Failed to update status');
      }
    },
    error: function(){
      alert_float('danger', 'Server error while updating status');
    }
  });
});

 });
</script>
 
  
<?php $rolevalue = $this->roles_model->get($current_user->role);
if (isset($rolevalue->name) && in_array($rolevalue->name, ["agent","reseller","partner admin"])) { ?>
<script>
  // Disable Right-Click
  document.addEventListener('contextmenu', function(e) { e.preventDefault(); });

  // Disable Keyboard Shortcuts (F12, Ctrl+Shift+I/J, Ctrl+U)
  document.onkeydown = function(e) {
    if (
      e.keyCode === 123 ||
      (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) ||
      (e.ctrlKey && e.keyCode === 85)
    ) return false;
  };
</script>
<?php } ?>



<script>
$(document).ready(function () {

  // ✅ Filter by Status (Improved)
  $('#statusFilter').on('change', function () {
    const selectedStatus = $(this).val().toLowerCase().trim();

    $('#pipelineTable tbody tr').each(function () {
      const $row = $(this);

      // find the "main" merchant status select in this row (ignore cancel ones)
     // const $statusSelect = $row.find('select.status-dropdown')
                                //.not('[data-field], [data-cancel]')
                                //.first();
      const $statusSelect = $row.find('select.merchant-status-dropdown').first();

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
  
function updateCancelColor(selectEl) {
  if (!selectEl || !selectEl.classList) return;

  // remove old yes/no
  selectEl.classList.remove('yes', 'no');

  const v = (selectEl.value || '').toLowerCase().trim();
  if (v === 'yes') selectEl.classList.add('yes');
  if (v === 'no')  selectEl.classList.add('no');
}

  $(document).ready(function () {

  // initial color
  document.querySelectorAll('.cancel-status-dropdown').forEach(function(el){
    updateCancelColor(el);
  });



});


$(document).on('focus', '.cancel-status-dropdown', function() {
  $(this).data('previous', $(this).val());
});
  
  $('.status-dropdown').not('.cancel-status-dropdown').each(function () {
  updateStatusColor($(this), $(this).val());
});


$(document).on('change', '.cancel-status-dropdown', function() {
  const dropdown = $(this);
  const pipelineId = dropdown.data('id');
  const selectedValue = dropdown.val();

  Swal.fire({
    title: 'Are you sure?',
    text: "Do you want to change Cancel status to '" + selectedValue + "'?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, update it!',
    cancelButtonText: 'No, keep it'
  }).then((result) => {

    // If user cancels -> revert + recolor
    if (!result.isConfirmed) {
      dropdown.val(dropdown.data('previous'));
      updateCancelColor(dropdown[0]);
      return;
    }

    // User confirmed -> call API
    $.ajax({
      url: admin_url + 'pipeline_report/update_cancel_status',
      type: 'POST',
      dataType: 'json',
      data: { id: pipelineId, status: selectedValue },
      success: function(response) {
        if (!response.success) {
          // API says fail -> revert
          dropdown.val(dropdown.data('previous'));
        }
        // ✅ ALWAYS recolor based on final value (selected or reverted)
        updateCancelColor(dropdown[0]);
      },
      error: function() {
        // server error -> revert + recolor
        dropdown.val(dropdown.data('previous'));
        updateCancelColor(dropdown[0]);
      }
    });

  });
});



  // Store previous value to restore if cancelled
  $(document).on('focus', '.status-dropdown[data-id]', function() {
      $(this).data('previous', $(this).val());
  });

</script>
<script>
$(document).ready(function() {
// $(document).on('click', '.delete-record', function(e) {
       
//         e.preventDefault();
//         var button = $(this);
//         var id = button.data('id');
//         var type = button.data('type');
//         var status = button.data('status');
//         var reason = prompt("Enter reason for deleting this record:");
//         if (reason === null) return;

//         $.ajax({
//             url: admin_url + 'pipeline_report/delete_record',
//             type: 'POST',
//             data: {
//                 id: id,
//                 type: type,
//                 status: status,
//                 reason: reason
//             },
//             dataType: 'json',
//             success: function(response) {
//                 if (response.success) {
//                     alert_float('success', response.message);
//                     $('#pipelineTable').DataTable().ajax.reload();
//                 } else {
//                     alert_float('danger', response.message);
//                 }
//             },
//             error: function() {
//                 alert_float('danger', 'Error deleting record.');
//             }
//         });
//     });
  
  	$('.delete-record').on('click', function() {
    var id = $(this).data('id');
    var type = $(this).data('type');
    var reason = prompt('Enter reason for deletion:');
    if (reason) {
        $.post(admin_url + 'pipeline_report/delete_record', { id: id, type: type, reason: reason }, function(resp) {
            var res = JSON.parse(resp);
            alert(res.message);
            if (res.success) location.reload();
        });
    }
});


   
    $(document).on('click', '.archive-record', function(e) {
        e.preventDefault();
        var button = $(this);
        var id = button.data('id');
        var status = button.data('status');
        if (!confirm('Are you sure you want to archive this record?')) return;

        $.ajax({
            url: admin_url + 'pipeline_report/archive_record',
            type: 'POST',
            data: {
                id: id,
                status: status
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert_float('success', response.message);
                    $('#pipelineTable').DataTable().ajax.reload();
                } else {
                    alert_float('danger', response.message);
                }
            },
            error: function() {
                alert_float('danger', 'Error archiving record.');
            }
        });
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


