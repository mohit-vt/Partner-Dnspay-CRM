<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="no-margin"><b>Sub - Agent Reseller Application</b></h3>
                        <hr>
                            <div class="card-body" ><h4 class="no-margin"><b>ISO/Agent Information</b></h4></div>
                        <hr>
                    <?php echo form_open(site_url('admin/agents/submit'), ['class' => 'agents-form']); ?>

                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="submit_token" value="<?= bin2hex(random_bytes(16)); ?>">
            <?php 
                $last_name = '';
                if (!empty($custom_fields) && is_array($custom_fields)) {
                    foreach ($custom_fields as $field) {
                        if ($field['name'] === 'Last Name') {
                            $last_name = html_escape($field['value']);
                            break;
                        }
                    }
                }
                ?>

                       <div class="row">
                            <!-- Business Nature -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_nature">Business nature of your company *</label>
                                    <select id="business_nature" name="business_nature" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="ISO">ISO</option>
                                        <option value="Reseller">Reseller</option>
                                        <option value="Agent">Agent</option>
                                      	<option value="Sub-Agent">Sub-Agent</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Years in Business -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="years_in_business">Years in the business *</label>
                                    <select id="years_in_business" name="years_in_business" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="1-3 years">1-3 years</option>
                                        <option value="3-5 years">3-5 years</option>
                                        <option value="More than 15">More than 15</option>
                                        <option value="New">New</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Current Processing Relationships -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="current_relationships">Your current processing relationships</label>
                                    <input type="text" id="current_relationships" name="current_relationships" placeholder="Relationships" class="form-control" >
                                </div>
                            </div>

                            <!-- Sales Reps -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="has_sales_reps">Do you have sales reps in your office? *</label>
                                    <select id="has_sales_reps" name="has_sales_reps" class="form-control" required>
                                        <option value="">Select</option>
                                 		<option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="Reseller">Reseller</option>
                                        <option value="Affiliate">Affiliate</option>
                                        <option value="Leads Generation">Leads Generation</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Your primary interest is *</label><br>
                                    <select id="primary_interest" name="primary_interest[]" class="form-control">
                                        <option value="">Select</option>
                                        <option value="POS">POS</option>
                                        <option value="Bonus Program">Bonus Program</option>
                                        <option value="Residual">Residual</option>
                                        <option value="Portfolio Sale">Portfolio Sale</option>
                                      	<option value="Registered ISO">Registered ISO</option>
                                      	<option value="ISO Partner">ISO Partner</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                         
                         	<!-- Hidden input for custom value -->
                            <div class="col-md-6" id="custom_interest_div" style="display:none;">
                                <div class="form-group">
                                    <label>Specify other interest *</label>
                                    <input type="text" id="custom_interest" name="custom_interest" class="form-control" placeholder="Enter your interest">
                                </div>
                            </div>

                            <!-- Merchant Types Section -->
                            <div class="col-md-12">
                                <h4>What kind of merchants are you looking to board with us?</h4>
                                <hr>
                            </div>

                            <!-- Brick and Mortar Low Risk -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brick_mortar_percentage">Brick and mortar low risk (%)</label>
                                    <input type="number" id="brick_mortar_percentage" name="brick_mortar_percentage" placeholder="Percentage" class="form-control" min="0" max="100">
                                </div>
                            </div>

                            <!-- Retail High Risk -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="retail_high_risk_percentage">Retail high risk (%)</label>
                                    <input type="number" id="retail_high_risk_percentage" name="retail_high_risk_percentage" placeholder="Percentage" class="form-control" min="0" max="100">
                                </div>
                            </div>

                            <!-- Card Not Present High Risk -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="card_not_present_percentage">Card not present high risk (%)</label>
                                    <input type="number" id="card_not_present_percentage" name="card_not_present_percentage" placeholder="Percentage" class="form-control" min="0" max="100">
                                </div>
                            </div>

                            <!-- International Merchants -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="international_percentage">International merchants (%)</label>
                                    <input type="number" id="international_percentage" name="international_percentage" placeholder="Percentage" class="form-control" min="0" max="100">
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="col-md-12">
                                <h4>Contact Information</h4>
                                <hr>
                            </div>

                            <!-- First Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name *</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone *</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" maxlength="14" pattern="\d*"
               oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,14);">
                                </div>
                            </div>

                            <!-- Legal Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="legal_name">ISO/Reseller Legal Name *</label>
                                    <input type="text" id="legal_name" name="legal_name" class="form-control" required>
                                </div>
                            </div>

                            <!-- Website -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="office_number">Office Number</label>
                                    <input type="tel" id="office_number" name="office_number" class="form-control"  maxlength="14" pattern="\d*"
               oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,14);">
                                </div>
                            </div>
                         
                         	<div class="col-md-12">
                            <div class="form-group">
                                <label for="assigned_to_agent">Assigned to (Agent):</label>
                                <select id="assigned_to_agent" name="assigned_to_agent" class="form-control">
                                    <option label="" value="" selected>Select Agent</option>
                                    <?php if (!empty($staff_agents)): ?>
                                        <?php foreach ($staff_agents as $agent): ?>
                                            <option value="<?= $agent->staffid ?>"
                                                <?= (isset($pre_application['assigned_agent']) && $agent->staffid == $pre_application['assigned_agent']) ? 'selected' : '' ?>>
                                                <?= $agent->firstname . ' ' . $agent->lastname ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
							</div>

                            <!-- Comments -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comments">Comments</label>
                                    <textarea id="comments" name="comments" class="form-control" rows="3"></textarea>
                                </div>
                            </div>



                            <!-- Submit Button -->
                            <div class="col-md-12">
                                 <!-- Submit Button -->
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                            </div>
                        </div>

                        <?php echo form_close(); ?>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?= base_url('assets/plugins/jquery-circle-progress/circle-progress.min.js'); ?>"></script>
<script>
$(document).ready(function () {
    $('.agents-form').on('submit', function () {
        const btn = $(this).find('button[type="submit"]');
        if (btn.prop('disabled')) return false;
        btn.prop('disabled', true);
        btn.text('Submitting...');
    });
});
</script>
<script>

document.getElementById('primary_interest').addEventListener('change', function() {
    var otherDiv = document.getElementById('custom_interest_div');
    if (this.value === 'Other') {
        otherDiv.style.display = 'block';
    } else {
        otherDiv.style.display = 'none';
        document.getElementById('custom_interest').value = '';
    }
});
// $(document).ready(function() {
//     // Remove any event handlers that might be interfering

//     // Add simple test handler
//     $('input[type="checkbox"]').on('click', function() {
//         console.log('Checkbox clicked:', this.checked);
//     });
// });

//     $('.agents-form').submit(function(e) {
//     if ($('input[name="primary_interest[]"]:checked').length === 0) {
//         alert('Please select at least one primary interest');
//         e.preventDefault();
//     }
// });
      
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