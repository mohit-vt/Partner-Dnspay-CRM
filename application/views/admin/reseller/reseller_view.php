<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                      <?php if (!empty($reseller['reseller_code'])) { ?>
                       <h3 class="no-margin"><b>Application #: <?= $reseller['reseller_code'] ?? ''; ?></b></h3>
                       <?php } ?>
                        <hr>
                         <!-- Add a form to update data -->
                        <?php echo form_open(admin_url('reseller/update/'.$reseller['reseller_id']), ['class' => 'reseller-form', 'enctype' => 'multipart/form-data']); ?>

                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                        <div class="row">
                            <!-- Reseller Type -->
                            <div class="col-md-12">
                                <div class="form-group">
                                <label for="agent_name">Reseller Type</label>
                                <select name="reseller_type" id="reseller_type"  class="form-control" required>
                                <?php 
                                    $selected_status = isset($reseller) ? $reseller->status : 'Please Select'; 
                                    ?>
                                <option value="Individual" <?= ($selected_status == 'Individual') ? 'selected' : ''; ?>>Individual</option>
                                <option value="Company" <?= ($selected_status == 'Company') ? 'selected' : ''; ?>>Company</option>
                                <option value="Agency" <?= ($selected_status == 'Agency') ? 'selected' : ''; ?>>Agency</option>
                                <option value="PSP/ISO Office" <?= ($selected_status == 'PSP/ISO Office') ? 'selected' : ''; ?>>PSP/ISO Office</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Company/Individual Name -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Company/Individual Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?= $reseller['name'] ?>" required>
                                </div>
                            </div>

                            
                            <!-- First Name -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" value="<?= $reseller['first_name'] ?>" required>
                                </div>
                            </div>

                            
                            <!-- Last Name -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" value="<?= $reseller['last_name'] ?>" required>
                                </div>
                            </div>

                            <!-- Agent Number -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" id="contact_person" name="contact_person" class="form-control" value="<?= $reseller['contact_person'] ?>" required>
                                </div>
                            </div>
                      
                   
                            <!-- Email -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Contact Email</label>
                                        <input type="text" id="email" name="email" value="<?= $reseller['email'] ?>" class="form-control">
                                    </div>
                            </div>

                            <!-- Cell Phone Number -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="phone">Cell Phone Number</label>
                                        <input type="tel" id="phone" name="phone" value="<?= $reseller['phone'] ?>"  pattern="\d*"
               oninput="this.value=this.value.replace(/[^0-9]/g,'');"  maxlength="12" class="form-control">
                                    </div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" id="address" name="address" value="<?= $reseller['address'] ?>" class="form-control">
                                    </div>
                            </div>

                            <!-- Street -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="street">Street</label>
                                        <input type="text" id="street" name="street" value="<?= $reseller['street'] ?>" class="form-control">
                                    </div>
                            </div>

                            <!-- City -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" id="city" name="city" value="<?= $reseller['city'] ?>" class="form-control">
                                    </div>
                            </div>

                            <!-- Street -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" id="state" name="state" value="<?= $reseller['state'] ?>" class="form-control">
                                    </div>
                            </div>

                            <!-- Zip Code -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="zip_code">Zip Code</label>
                                        <input type="text" id="zip_code" name="zip_code" value="<?= $reseller['zip_code'] ?>" class="form-control">
                                    </div>
                            </div>

                            <!-- Country -->
                            <div class="col-md-12">
                                    <div class="form-group">
                            <label
                                for="lastname"><?= _l('clients_country'); ?></label>
                            <select
                                data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>"
                                data-live-search="true" name="country" class="form-control" id="country">
                              <option value=""></option>
            <?php foreach (get_all_countries() as $country) { ?>
                <?php
                    $selected = false;

                    // Match reseller country
                    if (!empty($reseller['country']) && $reseller['country'] == $country['country_id']) {
                        $selected = true;
                    }

                    // If no reseller country, default to United States
                    if (empty($reseller['country']) && $country['short_name'] === "United States") {
                        $selected = true;
                    }
                ?>
                <option value="<?= e($country['country_id']); ?>"
                        <?= set_select('country', $country['country_id'], $selected); ?>>
                    <?= e($country['short_name']); ?>
                </option>
            <?php } ?>
                            </select>
                            </div>
                        </div>
<div class="col-md-12">
    <div class="form-group">
        <label for="assigned_to_agent">Assigned to agent:</label>
        <select name="assigned_to_agent" id="assigned_to_agent" class="form-control" title="Source Of The lead">
            <option label="" value="">Select Agents</option>
            <?php foreach ($staff_agents as $member): ?>
                <option value="<?= $member->staffid ?>"
                    <?= (isset($reseller['assigned_to_agent']) && (string)$member->staffid === (string)$reseller['assigned_to_agent']) ? 'selected' : '' ?>>
                    <?= html_escape($member->firstname . ' ' . $member->lastname) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

                            <!-- NDA Agreement Upload -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nda_file">NDA Agreement Upload</label>
                                    
                                    <!-- File Input -->
                                    <input type="file" id="nda_file" name="nda_file" class="form-control" accept="application/pdf" onchange="updateFileName(this, 'nda_file_label')">
                                    
                                       <?php if (!empty($reseller['nda_file'])): ?>
                                <div class="existing-file mt-2">
                                    <a href="<?= base_url('uploads/reseller_docs/'.$reseller['nda_file']) ?>" target="_blank">
                                        <?= htmlspecialchars($reseller['nda_file']) ?>
                                    </a>
                                    <br>
                                    <label class="text-danger mt-1">
                                        <input type="checkbox" name="remove_nda_file" value="1">
                                        Remove this file
                                    </label>
                                    <!-- Add this hidden field -->
                                    <input type="hidden" name="current_nda_file" value="<?= $reseller['nda_file'] ?>">
                                    </div>
                                <?php endif; ?>
                                        </div>
                                    </div>
                               <!-- Reseller W9 -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reseller_w9">Reseller W9</label>
                                    <input type="file" name="reseller_w9"  id="reseller_w9" accept="application/pdf, image/*" class="form-control" onchange="updateFileName(this, 'reseller_w9_file_label')" >

                                       <?php if (!empty($reseller['reseller_w9'])): ?>
                                <div class="existing-file mt-2">
                                    <a href="<?= base_url('uploads/reseller_docs/'.$reseller['reseller_w9']) ?>" target="_blank">
                                        <?= htmlspecialchars($reseller['reseller_w9']) ?>
                                    </a>
                                    <br>
                                    <label class="text-danger mt-1">
                                        <input type="checkbox" name="remove_reseller_w9" value="1">
                                        Remove this file
                                    </label>
                                    <!-- Add this hidden field -->
                                    <input type="hidden" name="current_reseller_w9" value="<?= $reseller['reseller_w9'] ?>">
                                    </div>
                                <?php endif; ?>
                                        </div>
                                    </div>
 
                        <!-- ISO Agreement Upload -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="iso_agreement_file">ISO Agreement Upload</label>
                                    <input type="file" id="iso_agreement_file" accept="application/pdf" name="iso_agreement_file" class="form-control" onchange="updateFileName(this, 'iso_agreement_file_label')" >

                                       <?php if (!empty($reseller['iso_agreement_file'])): ?>
                                <div class="existing-file mt-2">
                                    <a href="<?= base_url('uploads/reseller_docs/'.$reseller['iso_agreement_file']) ?>" target="_blank">
                                        <?= htmlspecialchars($reseller['iso_agreement_file']) ?>
                                    </a>
                                    <br>
                                    <label class="text-danger mt-1">
                                        <input type="checkbox" name="remove_iso_agreement_file" value="1">
                                        Remove this file
                                    </label>
                                    <!-- Add this hidden field -->
                                    <input type="hidden" name="current_iso_agreement_file" value="<?= $reseller['iso_agreement_file'] ?>">
                                    </div>
                                <?php endif; ?>
                                        </div>
                                    </div>

                        <!-- Government-issued ID -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="id_file">Government-issued ID</label>
                                    <input type="file" id="id_file" name="id_file" accept="application/pdf, image/*" class="form-control" onchange="updateFileName(this, 'id_file_label')" >

                                       <?php if (!empty($reseller['id_file'])): ?>
                                <div class="existing-file mt-2">
                                    <a href="<?= base_url('uploads/reseller_docs/'.$reseller['id_file']) ?>" target="_blank">
                                        <?= htmlspecialchars($reseller['id_file']) ?>
                                    </a>
                                    <br>
                                    <label class="text-danger mt-1">
                                        <input type="checkbox" name="remove_id_file" value="1">
                                        Remove this file
                                    </label>
                                    <!-- Add this hidden field -->
                                    <input type="hidden" name="current_id_file" value="<?= $reseller['id_file'] ?>">
                                    </div>
                                <?php endif; ?>
                                        </div>
                                    </div>

                        
                        <!-- Canceled Check/Bank Letter -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bank_file">Canceled Check/Bank Letter</label>
                                    <input type="file" name="bank_file" id="bank_file" accept="application/pdf, image/*" class="form-control" onchange="updateFileName(this, 'bank_file_label')" >

                                       <?php if (!empty($reseller['bank_file'])): ?>
                                <div class="existing-file mt-2">
                                    <a href="<?= base_url('uploads/reseller_docs/'.$reseller['bank_file']) ?>" target="_blank">
                                        <?= htmlspecialchars($reseller['bank_file']) ?>
                                    </a>
                                    <br>
                                    <label class="text-danger mt-1">
                                        <input type="checkbox" name="remove_bank_file" value="1">
                                        Remove this file
                                    </label>
                                    <!-- Add this hidden field -->
                                    <input type="hidden" name="current_bank_file" value="<?= $reseller['bank_file'] ?>">
                                    </div>
                                <?php endif; ?>
                                        </div>
                                    </div>

                        

                        <!-- Preferred Payout Method -->
                        <div class="col-md-12">
                                <div class="form-group">
                                <label for="payout_method">Preferred Payout Method</label>
                                <select name="payout_method" id="payout_method"  class="form-control" >
                                <?php 
                                    $selected_status = isset($pipeline_report) ? $pipeline_report->status : 'Please Select'; // Default to 'Leads'
                                    ?>
                                <option value="ACH" <?= ($selected_status == 'ACH') ? 'selected' : ''; ?>>ACH</option>
                                <option value="Wire" <?= ($selected_status == 'Wire') ? 'selected' : ''; ?>>Wire</option>
                                <option value="Check" <?= ($selected_status == 'Check') ? 'selected' : ''; ?>>Check</option>
                                    </select>
                                </div>
                        </div>


                     

                        <!-- Bank Name -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" value="<?= $reseller['bank_name'] ?>" class="form-control">
                                </div>
                        </div>
                  


                        <!-- Bank Routing Number -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="routing_number">Bank Routing Number</label>
                                    <input type="text" id="routing_number" value="<?= $reseller['routing_number'] ?>" name="routing_number" class="form-control" required>
                                </div>
                        </div>
                        
                        <!-- Bank Account Number -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="account_number">Bank Account Number</label>
                                    <input type="number" id="account_number" value="<?= $reseller['account_number'] ?>" name="account_number" class="form-control">
                                </div>
                        </div>

                        <!-- Reseller Website -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="website">Reseller Website</label>
                                    <input type="url" id="website" name="website" value="<?= !empty($reseller['website']) ? $reseller['website'] : 'https://'; ?>" placeholder="Enter a valid URL" class="form-control" required>
                                </div>
                        </div>


                    </div>
                  
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    // Get the country ID from PHP
    const selectedCountryId = <?= json_encode($reseller['country'] ?? ''); ?>;

    // Run when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country');
        if (selectedCountryId && countrySelect) {
            // Find the option with the matching value and select it
            countrySelect.value = selectedCountryId;
            // Alternatively, loop through options if needed:
            /*
            for (let option of countrySelect.options) {
                if (option.value == selectedCountryId) {
                    option.selected = true;
                    break;
                }
            }
            */
        }
    });
</script>
<script>
function updateFileName(input, labelId) {
    if (input.files.length > 0) {
        document.getElementById(labelId).textContent = "Selected File: " + input.files[0].name;
    }
}
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

