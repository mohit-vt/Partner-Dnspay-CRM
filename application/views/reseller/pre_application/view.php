<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="no-margin"><b>View Pre Application</b></h3>
                        <hr>
                        <div class="card-body"><h4 class="no-margin"><b>Personal Details</b></h4></div>
                        <hr>

                        <!-- Add a form to update data -->
                        <!--Commented on 27.05.2025 for copy mode in list.-->
                        <?php //echo form_open(admin_url('pre_application/update/'.$pre_application['id']), ['class' => 'pre_application-form', 'enctype' => 'multipart/form-data']); ?>

               		<?php
                          $form_action = isset($copy_mode) && $copy_mode
                              ? base_url('reseller/pre_application/submit')
                              : (isset($pre_application['id'])
                                  ? base_url('reseller/pre_application/update/' . $pre_application['id'])
                                  : base_url('reseller/pre_application/submit'));

                          echo form_open($form_action, [
                              'class' => 'pre_application-form',
                              'enctype' => 'multipart/form-data'
                          ]);
                          ?>



                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                   
                        <div class="row">
                            <!-- ISO Office -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="owner_title">Owner Title List:</label>
                                        <select id="status" name="status" class="form-control" required>
                                        <?php 
                                        $selected_status = isset($pre_application['status']) ? trim($pre_application['status']) : '';
                                        $options = ["CEO", "CFO", "Manager", "Owner", "Partner", "President", "Vice President"];

                                        foreach ($options as $option) {
                                            $selected = ($selected_status === $option) ? 'selected' : '';
                                            echo "<option value=\"" . htmlspecialchars($option) . "\" $selected>" . htmlspecialchars($option) . "</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <!-- Agent Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="owner_percentage">Owner Equity Percentage:</label>
                                    <input type="text" id="owner_percentage" name="owner_percentage" value="<?= $pre_application['owner_percentage'] ?>" class="form-control" required>
                                </div>
                            </div>

                            <!-- Agent Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="<?= $pre_application['first_name'] ?>" class="form-control" required>
                                </div>
                            </div>
                       

                            <!-- Agent Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" value="<?= $pre_application['last_name'] ?>" class="form-control" required>
                                </div>
                            </div>
                      


                            <!-- Home Address -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="home_address">Home Address</label>
                                    <textarea type="text" id="home_address" name="home_address" value="<?= $pre_application['home_address'] ?>"  class="form-control" rows="1" required><?= $pre_application['home_address'] ?></textarea>
                                </div>
                            </div>
                      

                                                
                            <!-- Agent Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="suite_appartment">Suite/Apartment:</label>
                                    <input type="text" id="suite_appartment" name="suite_appartment" value="<?= $pre_application['suite_appartment'] ?>"  class="form-control" required>
                                </div> 
                            </div> 

                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">City:</label>
                                    <input type="text" id="city" name="city" class="form-control" value="<?= $pre_application['city'] ?>" required>
                                </div>
                            </div>  
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state">State:</label>
                                    <input type="text" id="state" name="state" value="<?= $pre_application['state'] ?>" class="form-control" required>
                                </div>
                            </div> 
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zip_code">Zip Code:</label>
                                    <input type="text" id="zip_code" name="zip_code" value="<?= $pre_application['zip_code'] ?>" class="form-control" required>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country:</label>
                                    <input type="text" id="country" name="country" value="<?= $pre_application['country'] ?>" class="form-control" required>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cell_number">Cell Telephone Number:</label>
                                    <input type="text" id="cell_number" name="cell_number" value="<?= $pre_application['cell_number'] ?>" class="form-control" required>
                                </div>
                        </div> 
                        
                        
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="date_received">Date Received</label>
                                    <input type="date" id="date_received" name="date_received" class="form-control" value="<?= $pre_application['date_received'] ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="date_of_birth">Date of birth</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?= $pre_application['date_of_birth'] ?>" required>
                                </div>
                            </div>    
                                     <div class="col-md-6">
                            <div class="form-group">
                                    <label for="personal_email_address">Personal Email Address</label>
                                    <input type="text" id="personal_email_address" name="personal_email_address" value="<?= $pre_application['personal_email_address'] ?>" class="form-control" required>
                                   
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ssn_passport">SSN/Passport #:</label>
                                    <input type="text" id="ssn_passport" name="ssn_passport" value="<?= $pre_application['ssn_passport'] ?>" class="form-control" required>
                                </div>
                        </div>                     
                            
                        </div>
                <hr>
                    <div class="card-body" >
                        <h4 class="no-margin"><b>Business Details</b></h4>
                    </div>
                <hr>

                    <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_system">Business Name / DBA / Descriptor:</label>
                                    <input type="text" id="business_system" name="business_system"  value="<?= $pre_application['business_system'] ?>" class="form-control" required>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_name">Corporate / Legal Business Name:</label>
                                    <input type="text" id="business_name" name="business_name" class="form-control" value="<?= $pre_application['business_name'] ?>" required>
                                </div>
                            </div>

                             
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_type">Business Type</label>
                                    <select name="business_type" id="business_type" class="form-control" required title="Business Type">
                                        <?php
                                        // Get selected value safely
                                        $selected_type = isset($pre_application['business_type']) ? trim($pre_application['business_type']) : '';

                                        // List of business type options (value => label)
                                        $business_types = [
                                            "" => "Select",
                                            "Association_Estate_Trust" => "Association/Estate/Trust",
                                            "Corporation" => "Corporation",
                                            "Government" => "Government",
                                            "International_Organization" => "International Organization",
                                            "LLC" => "LLC",
                                            "Medical_Legal_Corporation" => "Medical/Legal Corporation",
                                            "Non_Profit" => "Non-Profit",
                                            "Partnership" => "Partnership",
                                            "Sole_Proprietor" => "Sole Proprietor",
                                            "Tax_Exempt_Organization" => "Tax Exempt Organization",
                                            "LTD" => "LTD",
                                            "Public_Company" => "Public Company"
                                        ];

                                        // Render options
                                        foreach ($business_types as $value => $label) {
                                            $selected = ($selected_type === $value) ? 'selected' : '';
                                            echo "<option value=\"" . htmlspecialchars($value) . "\" $selected>" . htmlspecialchars($label) . "</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                        </div>

                    
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fed_tax_id">Fed Tax ID</label>
                                    <input type="text" name="fed_tax_id" id="fed_tax_id" value="<?= $pre_application['fed_tax_id'] ?>" class="form-control">
                                </div>
                        </div>

                                                     
                      
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merchandise_service">Merchandise/Services Sold:</label>
                                    <input type="text" id="merchandise_service" name="merchandise_service" value="<?= $pre_application['merchandise_service'] ?>" class="form-control">
                                </div>
                        </div>

                       
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="high_ticket_amount">High Ticket Amount:</label>
                                    <input type="text" id="high_ticket_amount"  value="<?= $pre_application['high_ticket_amount'] ?>" name="high_ticket_amount" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="average_ticket_amount">Average Ticket Amount:</label>
                                    <input type="text" id="average_ticket_amount"  value="<?= $pre_application['average_ticket_amount'] ?>" name="average_ticket_amount" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="average_monthly_volume">Average Monthly Volume:</label>
                                    <input type="text" id="average_monthly_volume" value="<?= $pre_application['average_monthly_volume'] ?>" name="average_monthly_volume" class="form-control">
                                </div>
                        </div> 
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_telephone_number">Business Telephone Number:</label>
                                    <input type="text" id="business_telephone_number" value="<?= $pre_application['business_telephone_number'] ?>"  name="business_telephone_number" class="form-control" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  maxlength="10">
          
                                </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_cs_number">Business CS Number:</label>
                                    <input type="text" id="business_cs_number" value="<?= $pre_application['business_cs_number'] ?>" name="business_cs_number" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_fax_number">Business Fax Number:</label>
                                    <input type="text" id="business_fax_number"  name="business_fax_number" value="<?= $pre_application['business_fax_number'] ?>"class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_website_address">Business Website Address:</label>
                                    <input type="text" id="business_website_address"  name="business_website_address" value="<?= !empty($pre_application['business_website_address']) ? $pre_application['business_website_address'] : 'https://'; ?>" class="form-control">
                                </div>
                        </div>                                                                         
                    

                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_email_address">Business Email Address:</label>
                                    <input type="text" id="business_email_address" value="<?= $pre_application['business_email_address'] ?>"  name="business_email_address" vqlr class="form-control">
                                </div>
                        </div>
                        
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_address">Business Address:</label>
                                    <textarea type="text" id="business_address"  name="business_address" class="form-control" rows="2"><?= $pre_application['business_address'] ?></textarea>
                                    
                                </div>
                        </div>  

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_address_type">Business Suite/Apartment:</label>
                                    <input type="text" id="business_address_type"  name="business_address_type" value="<?= $pre_application['business_address_type'] ?>" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_city">Business City:</label>
                                    <input type="text" id="business_city"  value="<?= $pre_application['business_city'] ?>" name="business_city" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_state">Business State:</label>
                                    <input type="text" id="business_state"  value="<?= $pre_application['business_state'] ?>" name="business_state" class="form-control">
                                </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_zip_code">Business Zip Code:</label>
                                    <input type="text" id="business_zip_code"  value="<?= $pre_application['business_zip_code'] ?>"  name="business_zip_code" class="form-control">
                                </div>
                        </div> 
                        
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_country">Business Country:</label>
                                    <input type="text" id="business_country" value="<?= $pre_application['business_country'] ?>" name="business_country" class="form-control">
                                </div>
                        </div> 


                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select_your_rates_fees">Select Your Rates, Fees & Structure:</label>
                                    <select name="select_your_rates_fees" id="select_your_rates_fees" title="Select Your Rates, Fees &amp; Structure" class="form-control" required>
                                        <?php
                                        // Get selected value from DB (object format)
                                        $selected_rate_fee = isset($pre_application['select_your_rates_fees']) ? trim($pre_application['select_your_rates_fees']) : '';

                                        // Define options (value => label)
                                        $rates_fees_options = [
                                            "Cash_Discount" => "Cash Discount",
                                            "Flat_Rates" => "Flat Rates",
                                            "Interchange_Plus" => "Interchange Plus",
                                            "Tired_Pricing" => "Tired Pricing",
                                            "International_Banking_Rates" => "International Banking Rates",
                                            "Line_of_Credit_Loan_and_financing" => "Line of Credit, Loan and financing",
                                            "Consumers_Product_Financing" => "Consumers Product Financing",
                                            "Custom" => "Custom"
                                        ];

                                        // Render dropdown options
                                        foreach ($rates_fees_options as $value => $label) {
                                            $selected = ($selected_rate_fee === $value) ? 'selected' : '';
                                            echo "<option value=\"" . htmlspecialchars($value) . "\" $selected>" . htmlspecialchars($label) . "</option>";
                                        }
                                        ?>
                                    </select>

                                    </div>
                        </div>  

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="source_of_the_lead">Source Of The lead:</label>
                                    <select name="source_of_the_lead" id="source_of_the_lead" class="form-control" title="Source Of The lead">
                                    <option label="Internet" value="Internet" selected>Internet</option>
                                    <option label="Reseller Agent" value="Reseller_Agent">Reseller Agent</option>
                                    </select>
                                </div>
                        </div>
                                                                                                                
            </div>
                     
                <hr>
                    <div class="card-body" >
                        <h4 class="no-margin"><b>Where should we send your money?</b></h4>
                    </div>
                <hr>

                    <div class="row">

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name:</label>
                                    <input type="text" id="bank_name"  value="<?= $pre_application['bank_name'] ?>"  name="bank_name" class="form-control">
                                </div>
                        </div>


                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_phone_number">Bank Phone Number:</label>
                                    <input type="text" id="bank_phone_number" name="bank_phone_number"  value="<?= $pre_application['bank_phone_number'] ?>"  class="form-control" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  maxlength="10">
                                </div>
                                </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="routing_number">Routing Number:</label>
                                    <input type="text" id="routing_number"  value="<?= $pre_application['routing_number'] ?>"  name="routing_number" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_number">Account Number:</label>
                                    <input type="text" id="account_number" name="account_number" value="<?= $pre_application['account_number'] ?>"  class="form-control">
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="business_established">Date Business Established</label>
                                    <input type="date" id="business_established" name="business_established" value="<?= $pre_application['business_established'] ?>" class="form-control">
                                </div>
                            </div> 

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="swift_number">Swift Number:</label>
                                    <input type="text" id="swift_number" name="swift_number"  value="<?= $pre_application['swift_number'] ?>" class="form-control">
                                </div>
                        </div>

                   
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="iban">Iban</label>
                                    <input type="text" id="iban" name="iban" value="<?= $pre_application['iban'] ?>"  class="form-control">
                                </div>
                        </div>
                     

     
                        
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="upload_file">Extra Upload Documents</label>
                                    <input type="file" id="upload_file" name="upload_file[]" class="form-control" accept="application/pdf, image/png, image/gif, image/jpeg" multiple onchange="updateFileName(this, 'upload_file')">
                            <!-- Show Existing File Name & Download Link -->
                            <?php if (!empty($pre_application['upload_file'])): ?>
                                <?php 
                                    // Extract original file name (remove the unique ID prefix)
                                    $filePath = $pre_application['upload_file'];
                                    //$originalFileName = preg_replace('/^\d+_\d+_/', '', basename($filePath)); 

                                    $files = explode(',', $pre_application['upload_file']);
                                    foreach ($files as $file) {
                                    $originalFileName = preg_replace('/^\d+_\d+_/', '', basename($file));
                                    echo '<p><i class="fa fa-file" style="font-size:14px;color:red"></i><a href="' . base_url('uploads/merchant_docs/' . $file) . '" target="_blank">' . ' ' . htmlspecialchars($originalFileName) . '</a></p>';
                                        }
                                    ?>
                                      
                                
                            <?php endif; ?>
        
                                <!-- Placeholder for Selected File Name -->
                                <p id="id_file_label" style="margin-top: 5px; color: green;"></p>
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driving_license">Driver Licence</label>
                                    <input type="text" id="driving_license" name="driving_license" value="<?= $pre_application['driving_license'] ?>" class="form-control" >
                                </div>
                        </div> 

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_of_license">State of the licence</label>
                                    <input type="text" id="state_of_license" name="state_of_license" value="<?= $pre_application['state_of_license'] ?>" class="form-control" >
                                </div>
                        </div> 

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referred_by">Reffered by</label>
                                    <input type="text" id="referred_by" name="referred_by" value="<?= $pre_application['comments'] ?>" class="form-control" >
                                </div>
                        </div> 
                        
   <?php $rolevalue = $this->roles_model->get($current_user->role);
if ((isset($rolevalue->name)) && $rolevalue->name != "agent" && $rolevalue->name != "reseller") {  ?>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="assigned_employee">Assigned To In House Employee:</label>
                                    <select name="assigned_employee" id="assigned_employee" class="form-control" title="Source Of The lead">
                                        <option label="" value="" selected>Select In-House Users</option>
                                        <?php foreach ($staff_inhouse as $member): ?>
                                            <option value="<?= $member->staffid ?>"
                                                <?= ($member->staffid == $pre_application['assigned_employee']) ? 'selected' : '' ?>>
                                                <?= $member->firstname . ' ' . $member->lastname ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                        </div>
                      

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="assigned_agent">Assigned to agent:</label>
                                <select name="assigned_agent" id="assigned_agent" class="form-control" title="Source Of The lead">
                                    <option label="" value="" selected>Select Agents</option>
                                    <?php foreach ($staff_agents as $member): ?>
                                        <option value="<?= $member->staffid ?>"
                                            <?= ($member->staffid == $pre_application['assigned_agent']) ? 'selected' : '' ?>>
                                            <?= $member->firstname . ' ' . $member->lastname ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                </div>
                        </div> 
                        <?php } ?>

                        
                        <div class="col-md-12">
                            <div class="form-group">
                            <label for="comments">Agent/Partner Comments (max 250 characters):</label><br>
                            <textarea id="comments" name="comments" rows="5" cols="50" maxlength="250" class="form-control"><?= $pre_application['comments'] ?></textarea>
                                </div>
                        </div>
                    </div>                  
                        
                    
                        <div >
                            <button type="submit" style="float:right" class="btn btn-primary">Save</button>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
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


