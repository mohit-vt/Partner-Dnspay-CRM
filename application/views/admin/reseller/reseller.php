<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="no-margin"><b>New Reseller Application</b></h3>
                        <hr>
                           
                        <hr>
                        <?php echo form_open(admin_url('reseller/submit'), ['class' => 'reseller-form', 'enctype' => 'multipart/form-data']); ?>

                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                        <div class="row">
                            <!-- Reseller Type -->
                            <div class="col-md-12">
                                <div class="form-group">
                                <label for="agent_name">Reseller Type</label>
                                <select name="reseller_type" id="reseller_type"  class="form-control">
                                        <option value="">Please Select</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Company">Company</option>
                                        <option value="Agency">Agency</option>
                                        <option value="PSP/ISO Office">PSP/ISO Office</option>
                                        <option value="others">others</option>
                                    </select>
                                </div>
                            </div>

                            <div id="otherInputContainer" class="col-md-12">
                               
                            </div>
                            

                            <!-- Company/Individual Name -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Company/Individual Name</label>
                                    <input type="text" id="name" name="name" class="form-control">
                                    <input type="hidden" name="leads_id" id="leads_id">
                                </div>
                            </div>

                            <!-- First Name -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" value="<?= $lead['name'] ?? ' ' ?>">
                                </div>
                            </div>

                            
                            <?php
                            $last_name = '';
                            foreach ($custom_fields as $field) {
                                if ($field['name'] === 'Last Name') {
                                    $last_name = html_escape($field['value']);
                                    break;
                                }
                            }
                            ?>
                            
                            <!-- Last Name -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                             <input type="text" id="last_name" name="last_name" class="form-control" value="<?= $last_name; ?>" >


                                </div>
                            </div>


                            <!-- Agent Number -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" id="contact_person" name="contact_person" class="form-control">
                                </div>
                            </div>
                      
                   
                            <!-- Email -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Contact Email</label>
                                        <input type="text" id="email" name="email" value="<?= $lead['email'] ?? ' ' ?>" class="form-control">
                                    </div>
                            </div>

                            <!-- Cell Phone Number -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="phone">Cell Phone Number</label>
                                        <input type="tel" id="phone" name="phone" value="<?= $lead['phonenumber'] ?? ' ' ?>"  pattern="\d*"
               oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,14);"  maxlength="14" class="form-control">
                                    </div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" id="address" name="address" class="form-control">
                                    </div>
                            </div>

                            <!-- Street -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="street">Street</label>
                                        <input type="text" id="street" name="street" class="form-control">
                                    </div>
                            </div>

                            <!-- City -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" id="city" name="city" class="form-control">
                                    </div>
                            </div>

                            <!-- Street -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" id="state" name="state" class="form-control">
                                    </div>
                            </div>

                            <!-- Zip Code -->
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="zip_code">Zip Code</label>
                                        <input type="text" id="zip_code" name="zip_code" class="form-control">
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

                              // If client has country, match it
                              if (!empty($client->country) && $client->country == $country['country_id']) {
                                  $selected = true;
                              }

                              // If no client country set, default to United States
                              if (empty($client->country) && $country['short_name'] === "United States") {
                                  $selected = true;
                              }
                          ?>
                          <option
                              value="<?= e($country['country_id']); ?>"
                              <?= set_select('country', $country['country_id'], $selected); ?>>
                              <?= e($country['short_name']); ?>
                          </option>
                      <?php } ?>
                            </select>
                            </div>
                        </div>
                          
                          <div class="col-md-12">
                          <div class="form-group">
                              <label for="assigned_to_agent">Assigned to (Agent):</label>
                              <select id="assigned_to_agent" name="assigned_to_agent" class="form-control selectpicker" data-live-search="true">
                                  <option label="" value="" selected>Select Agent</option>
                                  <?php if (!empty($staff_agents)): ?>
                                      <?php foreach ($staff_agents as $agent): ?>
                                          <option value="<?= $agent->staffid ?>">
                                              <?= $agent->firstname . ' ' . $agent->lastname ?>
                                          </option>
                                      <?php endforeach; ?>
                                  <?php endif; ?>
                              </select>
                          </div>
                      </div>

                            <!-- Company DBA Name -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nda_file">NDA Agreement Upload</label>
                                    <input type="file" id="nda_file" name="nda_file" class="form-control" accept="application/pdf">
                                </div>
                            </div>

                               <!-- Reseller W9 -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reseller_w9">Reseller W9</label>
                                    <input type="file" name="reseller_w9" id="reseller_w9" accept="application/pdf, image/png, image/gif, image/jpeg" class="form-control">
                                </div>
                        </div>
 
                        <!-- ISO Agreement Upload -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="iso_agreement_file">ISO Agreement Upload</label>
                                    <input type="file" id="iso_agreement_file" accept="application/pdf" name="iso_agreement_file" class="form-control">
                                </div>
                        </div>

                        <!-- Government-issued ID -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="id_file">Government-issued ID</label>
                                    <input type="file" id="id_file" name="id_file" accept="application/pdf, image/png, image/gif, image/jpeg" class="form-control">
                                </div>
                        </div>

                        
                        <!-- Canceled Check/Bank Letter -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bank_file">Canceled Check/Bank Letter</label>
                                    <input type="file" name="bank_file" id="bank_file" accept="application/pdf, image/png, image/gif, image/jpeg" class="form-control" >
                                </div>
                        </div>

                        

                        <!-- Preferred Payout Method -->
                        <div class="col-md-12">
                                <div class="form-group">
                                <label for="payout_method">Preferred Payout Method</label>
                                <select name="payout_method" id="payout_method"  class="form-control" >
                                        <option value="">Please Select</option>
                                        <option value="ACH">ACH</option>
                                        <option value="Wire">Wire</option>
                                        <option value="Check">Check</option>
                                    </select>
                                </div>
                        </div>


                     

                        <!-- Bank Name -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" class="form-control">
                                </div>
                        </div>
                  


                        <!-- Bank Routing Number -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="routing_number">Bank Routing Number</label>
                                    <input type="text" id="routing_number"  name="routing_number" class="form-control">
                                </div>
                        </div>
                        
                        <!-- Bank Account Number -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="account_number">Bank Account Number</label>
                                    <input type="number" id="account_number" name="account_number" class="form-control">
                                </div>
                        </div>

                        <!-- Bank Account Number -->
                        <div class="col-md-12">
                                <div class="form-group">
                                <label for="alternative_payment">Alternative Payment</label>
                                <select name="alternative_payment" id="alternative_payment"  class="form-control">
                                        <option value="">Please Select</option>
                                        <option value="International payment instruction and currency">International payment instruction and currency</option>
                                        <option value="Crypto Exchange">Crypto Exchange</option>
                                        <option value="Wallet">Wallet</option>
                                    </select>
                                </div>
                            </div>
                        
                            <?php
                            $website_address = '';
                            foreach ($custom_fields as $field) {
                                if ($field['name'] === 'Iso/Reseller Website address') {
                                    $website_address = html_escape($field['value']);
                                    break;
                                }
                            }
                            ?>    

                        <!-- Reseller Website -->
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="website">Reseller Website</label>
                <input type="url" id="website" name="website" value="<?= !empty($website_address) ? $website_address : 'https://'; ?>" class="form-control">
                                </div>
                        </div>

                                    <!-- Status                  
                        <div class="col-md-12">
                                <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status"  class="form-control" required>
                                        <option value="">Please Select</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Declined">Declined</option>
                                    </select>
                                </div>
                        </div>
                    </div>-->   

        

                        <!-- Submit Button -->
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary">Submit </button>
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
$(document).ready(function() {
    $('#assigned_to_agent').select2({
        placeholder: "Select Agent",
        allowClear: true,
        width: '100%'
    });
});
</script>
  
<script>
$(document).ready(function() {
  $('#reseller_type').on('change', function() {
    const selectedValue = $(this).val();
    const container = $('#otherInputContainer');

    if (selectedValue === 'others') {
      // Add input only if not already added
      if ($('#otherInput').length === 0) {
        container.append(`
          <label for="others">Please specify:</label>
          <input type="text" id="otherInput" name="others" class="form-control" />
        `);
      }
    } else {
      // Remove input if it exists
      container.empty();
    }
  });
});

  window.onload = function () {
    const path = window.location.pathname;
    const segments = path.split('/');
    const id = segments[segments.length - 1];
    document.getElementById('leads_id').value = id;

  };
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

