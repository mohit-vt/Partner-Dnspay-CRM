<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="no-margin"><b>Merchant Pre Application</b></h3>
                        <hr>
                            <div class="card-body" ><h4 class="no-margin"><b>Personal Details</b></h4></div>
                        <hr>
                       
                
                      <?php echo form_open(base_url('reseller/pre_application/create'), ['class' => 'pre_application-form', 'enctype' => 'multipart/form-data']); ?>
                        
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                        <div class="row">
                            <!-- ISO Office -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="owner_title">Owner Title List:</label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="" selected>Select</option>
                                        <option value="CEO">CEO</option>
                                        <option value="CFO">CFO</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Owner">Owner</option>
                                        <option value="Partner">Partner</option>
                                        <option value="President">President</option>
                                        <option value="Vice President">Vice President</option>
                                        </select>
                                </div>
                            </div>

                            <!-- Agent Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="owner_percentage">Owner Equity Percentage:</label>
                                    <input type="text" id="owner_percentage" name="owner_percentage" class="form-control" required>
                                </div>
                            </div>

                            <!-- Agent Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                                </div>
                            </div>
                       

                            <!-- Agent Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                                </div>
                            </div>
                      


                            <!-- Home Address -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="home_address">Home Address</label>
                                    <textarea type="text" id="home_address" name="home_address" class="form-control" rows="1" required></textarea>
                                </div>
                            </div>
                      

                                                
                            <!-- Agent Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="suite_appartment">Suite/Apartment:</label>
                                    <input type="text" id="suite_appartment" name="suite_appartment" class="form-control" required>
                                </div>
                            </div> 

                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">City:</label>
                                    <input type="text" id="city" name="city" class="form-control" required>
                                </div>
                            </div>  
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state">State:</label>
                                    <input type="text" id="state" name="state" class="form-control" required>
                                </div>
                            </div> 
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zip_code">Zip Code:</label>
                                    <input type="text" id="zip_code" name="zip_code" class="form-control" required>
                                </div>
                            </div>  

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country:</label>
                                    <input type="text" id="country" name="country" class="form-control" required>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cell_number">Cell Telephone Number:</label>
                                    <input type="text" id="cell_number" name="cell_number" pattern="\d*"
               oninput="this.value=this.value.replace(/[^0-9]/g,'');"  maxlength="12" class="form-control">
                                </div>
                        </div> 
                        
                        
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="date_received">Date Received</label>
                                    <input type="date" id="date_received" name="date_received" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="date_of_birth">Date of birth</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="personal_email_address">Personal Email Address</label>
                                    <input type="text" id="personal_email_address" name="personal_email_address" class="form-control" required>
                                   
                                </div>
                        </div>                         

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ssn_passport">SSN/Passport #:</label>
                                    <input type="text" id="ssn_passport" name="ssn_passport" class="form-control" required>
                                </div>
                        </div>   
                        
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driving_license">Driver Licence Number</label>
                                    <input type="text" id="driving_license" name="driving_license" class="form-control" >
                                </div>
                        </div> 

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_of_license">State of Driver Licence</label>
                                    <input type="text" id="state_of_license" name="state_of_license" class="form-control" >
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
                                    <input type="text" id="business_system" name="business_system" class="form-control" required>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_name">Corporate / Legal Business Name:</label>
                                    <input type="text" id="business_name" name="business_name" class="form-control" required>
                                </div>
                            </div>

                             
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_type">Business Type</label>
                    <select name="business_type" id="business_type" class="form-control" required title="Business Type">
                        <option value="" selected>Select</option>
                        <option label="Association/Estate/Trust" value="Association_Estate_Trust">Association/Estate/Trust</option>
                        <option label="Corporation" value="Corporation">Corporation</option>
                        <option label="Government" value="Government">Government</option>
                        <option label="International Organization" value="International_Organization">International Organization</option>
                        <option label="LLC" value="LLC">LLC</option>
                        <option label="Medical/Legal Corporation" value="Medical_Legal_Corporation">Medical/Legal Corporation</option>
                        <option label="Non-Profit" value="Non_Profit">Non-Profit</option>
                        <option label="Partnership" value="Partnership">Partnership</option>
                        <option label="Sole Proprietor" value="Sole_Proprietor">Sole Proprietor</option>
                        <option label="Tax Exempt Organization" value="Tax_Exempt_Organization">Tax Exempt Organization</option>
                        <option label="LTD" value="LTD">LTD</option>
                        <option label="Public Company" value="Public_Company">Public Company</option>
                        </select>
                                </div>
                        </div>

                    
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fed_tax_id">Fed Tax ID</label>
                                    <input type="text" name="fed_tax_id" id="fed_tax_id" class="form-control">
                                </div>
                        </div>

                                                     
                      
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merchandise_service">Merchandise/Services Sold:</label>
                                    <input type="text" id="merchandise_service" name="merchandise_service" class="form-control">
                                </div>
                        </div>

                       
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="high_ticket_amount">High Ticket Amount:</label>
                                    <input type="text" id="high_ticket_amount" value="0.00" name="high_ticket_amount" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="average_ticket_amount">Average Ticket Amount:</label>
                                    <input type="text" id="average_ticket_amount" value="0.00" name="average_ticket_amount" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="average_monthly_volume">Average Monthly Volume:</label>
                                    <input type="text" id="average_monthly_volume" value="0.00" name="average_monthly_volume" class="form-control">
                                </div>
                        </div> 
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_telephone_number">Business Telephone Number:</label>
                                    <input type="text" id="business_telephone_number"  name="business_telephone_number" class="form-control" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  maxlength="10">
          
                                </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_cs_number">Business CS Number:</label>
                                    <input type="text" id="business_cs_number"  name="business_cs_number" class="form-control">
                                </div>
                        </div>
                          

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_fax_number">Business Fax Number:</label>
                                    <input type="text" id="business_fax_number"  name="business_fax_number" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_website_address">Business Website Address:</label>
                                    <input type="text" id="business_website_address" value="https://" name="business_website_address" class="form-control">
                                </div>
                        </div>                                                                         
                    

                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_email_address">Business Email Address:</label>
                                    <input type="text" id="business_email_address"  name="business_email_address" class="form-control">
                                </div>
                        </div>
                        
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_address">Business Address:</label>
                                    <textarea type="text" id="business_address"  name="business_address" class="form-control" rows="2"></textarea>
                                    
                                </div>
                        </div>  

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_address_type">Business Suite/Apartment:</label>
                                    <input type="text" id="business_address_type"  name="business_address_type" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_city">Business City:</label>
                                    <input type="text" id="business_city"  name="business_city" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_state">Business State:</label>
                                    <input type="text" id="business_state"  name="business_state" class="form-control">
                                </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_zip_code">Business Zip Code:</label>
                                    <input type="text" id="business_zip_code"  name="business_zip_code" class="form-control">
                                </div>
                        </div>   

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_country">Business Country:</label>
                                    <input type="text" id="business_country"  name="business_country" class="form-control">
                                </div>
                        </div>  

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select_your_rates_fees">Select Your Rates, Fees & Structure:</label>
                        <select name="select_your_rates_fees" id="select_your_rates_fees" title="Select Your Rates, Fees &amp; Structure" class="form-control">
                                    <option label="Cash Discount" value="Cash_Discount" selected>Cash Discount</option>
                                    <option label="Flat Rates" value="Flat_Rates">Flat Rates</option>
                                    <option label="Interchange Plus" value="Interchange_Plus">Interchange Plus</option>
                                    <option label="Tired Pricing" value="Tired_Pricing">Tired Pricing</option>
                                    <option label="International Banking Rates" value="International_Banking_Rates">International Banking Rates</option>
                                    <option label="Line of Credit, Loan and financing" value="Line_of_Credit_Loan_and_financing">Line of Credit, Loan and financing</option>
                                    <option label="Consumers Product Financing" value="Consumers_Product_Financing">Consumers Product Financing</option>
                                    <option label="Custom" value="Custom">Custom</option>
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
                        <!-- First Name -->
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name:</label>
                                    <input type="text" id="bank_name" name="bank_name" class="form-control">
                                </div>
                        </div>


                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_phone_number">Bank Phone Number:</label>
                                    <input type="text" id="bank_phone_number" name="bank_phone_number" class="form-control" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  maxlength="10">
          
                                </div>
                        </div>


                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="routing_number">Routing Number:</label>
                                    <input type="text" id="routing_number" name="routing_number" class="form-control">
                                </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_number">Account Number:</label>
                                    <input type="text" id="account_number" name="account_number" class="form-control">
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="business_established">Date Business Established</label>
                                    <input type="date" id="business_established" name="business_established" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div> 

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="swift_number">Swift Number:</label>
                                    <input type="text" id="swift_number" name="swift_number" class="form-control">
                                </div>
                        </div>

                   
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="iban">Iban</label>
                                    <input type="text" id="iban" name="iban" class="form-control">
                                </div>
                        </div>
                        
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="upload_file">Extra Upload Documents</label>
                                    <input type="file" id="upload_file" name="upload_file[]" class="form-control" accept="application/pdf, image/png, image/gif, image/jpeg" multiple>
                                </div>
                        </div> 


                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referred_by">Reffered by</label>
                                    <input type="text" id="referred_by" name="referred_by" class="form-control" >
                                </div>
                        </div>
          <?php $rolevalue = $this->roles_model->get($current_user->role);
if ((isset($rolevalue->name)) && $rolevalue->name != "agent" && $rolevalue->name != "reseller") {  ?>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="assigned_employee">Assigned To In House Employee:</label>
                                    <select name="assigned_employee" id="assigned_employee" class="form-control" title="Source Of The lead">
                                     <option value="" selected>Select In-House Users</option>                                                                         
                                    <?php $selected_staff_id = isset($selected_staff_id) ? $selected_staff_id : '';
                                    ?>
                                    <?php foreach ($staff_inhouse as $member): ?>
                                        <option value="<?= $member->staffid ?>" <?= $member->staffid == $selected_staff_id ? 'selected' : '' ?>>
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
                                    <option value="" selected>Select Agents</option>
                                    <?php foreach ($staff_agents as $member): ?>
                                        <option value="<?= $member->staffid ?>" <?= $member->staffid == $selected_staff_id ? 'selected' : '' ?>>
                                            <?= $member->firstname . ' ' . $member->lastname ?>
                                        </option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                        </div>    
                        <?php } ?>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                            <label for="comments">Agent/Partner Comments (max 180 characters):</label><br>
                            <textarea id="comments" name="comments" rows="5" cols="50" maxlength="180" class="form-control"></textarea>
                                </div>
                        </div>

                    </div>                  

                    

                        <!-- Submit Button -->
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
