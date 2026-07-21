<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (!empty($agents['agents_code'])) { ?>
                            <h3 class="no-margin"><b>Application #: <?= $agents['agents_code'] ?? ''; ?></b></h3>
                        <?php } ?>
                        <hr>
                        <div class="card-body"><h4 class="no-margin"><b>ISO/Agent Information</b></h4></div>
                        <hr>
                        
                        <?php echo form_open(admin_url('agents/update/'.$agents['id']), ['class' => 'agents-form']); ?>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                        <div class="row">
                            <!-- Business Nature -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_nature">Business nature of your company *</label>
                                    <select id="business_nature" name="business_nature" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="ISO" <?= isset($agents['business_nature']) && $agents['business_nature'] == 'ISO' ? 'selected' : '' ?>>ISO</option>
                                        <option value="Reseller" <?= isset($agents['business_nature']) && $agents['business_nature'] == 'Reseller' ? 'selected' : '' ?>>Reseller</option>
                                        <option value="Agent" <?= isset($agents['business_nature']) && $agents['business_nature'] == 'Agent' ? 'selected' : '' ?>>Agent</option>
                                      <option value="Sub-Agent" <?= isset($agents['business_nature']) && $agents['business_nature'] == 'Sub-Agent' ? 'selected' : '' ?>>Sub-Agent</option>
                                        <option value="Other" <?= isset($agents['business_nature']) && $agents['business_nature'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Years in Business -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="years_in_business">Years in the business *</label>
                                    <select id="years_in_business" name="years_in_business" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="1-3 years" <?= isset($agents['years_in_business']) && $agents['years_in_business'] == '1-3 years' ? 'selected' : '' ?>>1-3 years</option>
                                        <option value="3-5 years" <?= isset($agents['years_in_business']) && $agents['years_in_business'] == '3-5 years' ? 'selected' : '' ?>>3-5 years</option>
                                        <option value="More than 15" <?= isset($agents['years_in_business']) && $agents['years_in_business'] == 'More than 15' ? 'selected' : '' ?>>More than 15</option>
                                        <option value="New" <?= isset($agents['years_in_business']) && $agents['years_in_business'] == 'New' ? 'selected' : '' ?>>New</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Current Processing Relationships -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="current_relationships">Your current processing relationships</label>
                                    <input type="text" id="current_relationships" name="current_relationships" value="<?= html_escape($agents['current_relationships'] ?? '') ?>" class="form-control">
                                </div>
                            </div>

                                                   <!-- Sales Reps -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="has_sales_reps">Do you have sales reps in your office? *</label>
                                    <select id="has_sales_reps" name="has_sales_reps" class="form-control" required>
                                        <option value="">Select</option>
                                   <option value="Yes" <?= isset($agents['has_sales_reps']) && $agents['has_sales_reps'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" <?= isset($agents['has_sales_reps']) && $agents['has_sales_reps'] == 'No' ? 'selected' : '' ?>>No</option>
                                     <option value="Reseller" <?= isset($agents['has_sales_reps']) && $agents['has_sales_reps'] == 'Reseller' ? 'selected' : '' ?>>Reseller</option>
                                      <option value="Affiliate" <?= isset($agents['has_sales_reps']) && $agents['has_sales_reps'] == 'Affiliate' ? 'selected' : '' ?>>Affiliate</option>
                                       <option value="Leads Generation" <?= isset($agents['has_sales_reps']) && $agents['has_sales_reps'] == 'Leads Generation' ? 'selected' : '' ?>>Leads Generation</option>
                                    </select>
                                </div>
                            </div>

                           <!-- Primary Interest -->
<div class="col-md-6">
    <div class="form-group">
        <label for="primary_interest">Your primary interest is *</label><br>
        <select id="primary_interest" name="primary_interest" class="form-control" required>
            <option value="">Select</option>
            <option value="POS" <?= isset($agents['primary_interest']) && $agents['primary_interest'] == 'POS' ? 'selected' : '' ?>>POS</option>
            <option value="Bonus Program" <?= isset($agents['primary_interest']) && $agents['primary_interest'] == 'Bonus Program' ? 'selected' : '' ?>>Bonus Program</option>
            <option value="Residual" <?= isset($agents['primary_interest']) && $agents['primary_interest'] == 'Residual' ? 'selected' : '' ?>>Residual</option>
            <option value="Portfolio Sale" <?= isset($agents['primary_interest']) && $agents['primary_interest'] == 'Portfolio Sale' ? 'selected' : '' ?>>Portfolio Sale</option>
            <option value="Registered ISO" <?= isset($agents['primary_interest']) && $agents['primary_interest'] == 'Registered ISO' ? 'selected' : '' ?>>Registered ISO</option>
            <option value="ISO Partner" <?= isset($agents['primary_interest']) && $agents['primary_interest'] == 'ISO Partner' ? 'selected' : '' ?>>ISO Partner</option>
            <option value="Other" <?= isset($agents['primary_interest']) && $agents['primary_interest'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
    </div>
</div>

<!-- Custom Interest (only visible if "Other" is selected) -->
<div class="col-md-6" id="custom_interest_div" style="display: <?= (isset($agents['primary_interest']) && $agents['primary_interest'] == 'Other') ? 'block' : 'none' ?>;">
    <div class="form-group">
        <label for="custom_interest">Specify other interest *</label>
        <input type="text" id="custom_interest" name="custom_interest"
               value="<?= html_escape($agents['custom_interest'] ?? '') ?>"
               class="form-control" placeholder="Enter your interest">
    </div>
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
                                    <input type="number" id="brick_mortar_percentage" name="brick_mortar_percentage" value="<?= html_escape($agents['brick_mortar_percentage'] ?? '') ?>" class="form-control" min="0" max="100">
                                </div>
                            </div>

                            <!-- Retail High Risk -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="retail_high_risk_percentage">Retail high risk (%)</label>
                                    <input type="number" id="retail_high_risk_percentage" name="retail_high_risk_percentage" value="<?= html_escape($agents['retail_high_risk_percentage'] ?? '') ?>" class="form-control" min="0" max="100">
                                </div>
                            </div>

                            <!-- Card Not Present High Risk -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="card_not_present_percentage">Card not present high risk (%)</label>
                                    <input type="number" id="card_not_present_percentage" name="card_not_present_percentage" value="<?= html_escape($agents['card_not_present_percentage'] ?? '') ?>" class="form-control" min="0" max="100">
                                </div>
                            </div>

                            <!-- International Merchants -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="international_percentage">International merchants (%)</label>
                                    <input type="number" id="international_percentage" name="international_percentage" value="<?= html_escape($agents['international_percentage'] ?? '') ?>" class="form-control" min="0" max="100">
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
                                    <input type="text" id="first_name" name="first_name" value="<?= html_escape($agents['first_name'] ?? '') ?>" class="form-control" required>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" id="last_name" name="last_name" value="<?= html_escape($agents['last_name'] ?? '') ?>" class="form-control" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" value="<?= html_escape($agents['email'] ?? '') ?>" class="form-control" required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone *</label>
                                    <input type="tel" id="phone" name="phone" value="<?= html_escape($agents['phone'] ?? '') ?>" class="form-control"  maxlength="14" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,14);">
                                </div>
                            </div>

                            <!-- Legal Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="legal_name">ISO/Reseller Legal Name *</label>
                                    <input type="text" id="legal_name" name="legal_name" value="<?= html_escape($agents['legal_name'] ?? '') ?>" class="form-control" required>
                                </div>
                            </div>

                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="office_number">Office Number</label>
                                    <input type="tel" id="office_number" name="office_number" value="<?= html_escape($agents['office_number'] ?? '') ?>" class="form-control"  maxlength="14" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,14);">
                                </div>
                            </div>
                      
                      		        <div class="col-md-12">
                    <div class="form-group">
                        <label for="assigned_to_agent">Assigned to Agent:</label>
                        <select id="assigned_to_agent" name="assigned_to_agent" class="form-control">
                            <option label="" value="" selected>Select In-House Users</option>                         
                            <?php if (!empty($staff_agents)): ?>
                              <?php foreach ($staff_agents as $agent): ?>
                                  <option value="<?= $agent->staffid ?>"
                                      <?= (isset($agents['assigned_to_agent']) && $agent->staffid == $agents['assigned_to_agent']) ? 'selected' : '' ?>>
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
                                    <textarea id="comments" name="comments" class="form-control" rows="3"><?= html_escape($agents['comments'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="Pending" <?= isset($agents['status']) && $agents['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Approved" <?= isset($agents['status']) && $agents['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="Rejected" <?= isset($agents['status']) && $agents['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-12">
                                <div class="panel-footer text-right">
                                    <button type="submit" class="btn btn-primary">Update Application</button>
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
<script>
  document.addEventListener("DOMContentLoaded", function() {
    let primaryInterest = document.getElementById("primary_interest");
    let customInterestDiv = document.getElementById("custom_interest_div");

    function toggleCustomInterest() {
        if (primaryInterest.value === "Other") {
            customInterestDiv.style.display = "block";
        } else {
            customInterestDiv.style.display = "none";
            document.getElementById("custom_interest").value = ""; // Clear if not other
        }
    }

    // Run on page load
    toggleCustomInterest();

    // Run when selection changes
    primaryInterest.addEventListener("change", toggleCustomInterest);
});
  
<?php 
$current_user = get_staff(get_staff_user_id());
$rolevalue = $this->roles_model->get($current_user->role);
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