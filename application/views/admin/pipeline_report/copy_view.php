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
    <?php echo form_open(admin_url('pipeline_report/submit'), ['class' => 'pipeline_report-form', 'enctype' => 'multipart/form-data']); ?>

            <div class="row">
                <!-- Lead Source -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lead_source">Lead Source:</label>
                        <select id="lead_source" name="lead_source" class="form-control" required>
                         <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['lead_source'] : 'Please Select'; 
                        ?>
                        <option value="In-House" <?= ($selected_status == 'In-House') ? 'selected' : ''; ?>>In-House</option>
                        <option value="Web" <?= ($selected_status == 'Web') ? 'selected' : ''; ?>>Web</option>
                        <option value="Partner" <?= ($selected_status == 'Partner') ? 'selected' : ''; ?>>Partner</option>
                        <option value="Sales" <?= ($selected_status == 'Sales') ? 'selected' : ''; ?>>Sales</option>
                        <option value="Affiliate" <?= ($selected_status == 'Affiliate') ? 'selected' : ''; ?>>Affiliate</option>
                        </select>
                    </div>
                </div>
              
              <div class="col-md-6">
                    <div class="form-group">
                        <label for="region">Select the region:</label>
                        <select id="region" name="region" class="form-control" required>
                         <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['region'] : 'Please Select'; 
                        ?>
                        <option value="U" <?= ($selected_status == 'U') ? 'selected' : ''; ?>>US</option>
                        <option value="N" <?= ($selected_status == 'N') ? 'selected' : ''; ?>>North America</option>
                        <option value="A" <?= ($selected_status == 'A') ? 'selected' : ''; ?>>Asia Pacific</option>
                        <option value="E" <?= ($selected_status == 'E') ? 'selected' : ''; ?>>Europe</option>
                        <option value="L" <?= ($selected_status == 'L') ? 'selected' : ''; ?>>Latin America and Caribbean</option>
                        <option value="F" <?= ($selected_status == 'F') ? 'selected' : ''; ?>>Africa</option>
                        <option value="M" <?= ($selected_status == 'M') ? 'selected' : ''; ?>>Middle East</option>
                        </select>
                    </div>
                </div>

                <!-- Application Date -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="application_date">Application Date:</label>
                        <input type="date" id="application_date" name="application_date" value="<?= $pipeline_report['application_date'] ?? '' ?>" class="form-control" required>
                        
                    </div>
                </div>

                <!-- Application IP -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="application_ip">Application IP:</label>
                        <input type="text" id="application_ip" name="application_ip" value="<?= $pipeline_report['application_ip'] ?? '' ?>" class="form-control">

            <input type="hidden" name="pre_application_id" value="<?= $pipeline_report['pre_application_id'] ?? '' ?>" >
                   
            </div>
                </div>
                <?php  
                $rolevalue = $this->roles_model->get($current_user->role); 
                $excluded_roles = ['partner admin', 'agent', 'reseller'];

                if (!in_array(strtolower($rolevalue->name), $excluded_roles)) {  
                ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="assigned_to_mst">Assigned to (MST):</label>
                        <select id="assigned_to_mst" name="assigned_to_mst" class="form-control">
                            <option label="" value="" selected>Select In-House Users</option>
                                        <?php foreach ($staff_inhouse as $member): ?>
                                            <option value="<?= $member->staffid ?>"
                                                <?= ($member->staffid == $pipeline_report['assigned_to_mst']) ? 'selected' : '' ?>>
                                                <?= $member->firstname . ' ' . $member->lastname ?>
                                            </option>
                                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
                <!-- Company Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_name">Company Name:</label>
                        <input type="text" id="company_name" name="company_name" value="<?= $pipeline_report['company_name'] ?? '' ?>" class="form-control" required>
                    </div>
                </div>

  				<!-- Payment Type -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_type">Payments Type:</label>
                        <select id="payment_type" name="payment_type" class="form-control" required>
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['payment_type'] : 'Please Select'; 
                        ?>
                        <option value="Cards" <?= ($selected_status == 'Cards') ? 'selected' : ''; ?>>Cards</option>
                        <option value="eWallet" <?= ($selected_status == 'eWallet') ? 'selected' : ''; ?>>eWallet</option>
                        <option value="eBank" <?= ($selected_status == 'eBank') ? 'selected' : ''; ?>>eBank</option>
                        <option value="Remittance" <?= ($selected_status == 'Remittance') ? 'selected' : ''; ?>>Remittance</option>
                        <option value="eMoney" <?= ($selected_status == 'eMoney') ? 'selected' : ''; ?>>eMoney</option>
                        <option value="Crypto" <?= ($selected_status == 'Crypto') ? 'selected' : ''; ?>>Crypto</option>
                        <option value="Gateway" <?= ($selected_status == 'Gateway') ? 'selected' : ''; ?>>Gateway</option>
                        <option value="Marketing" <?= ($selected_status == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                        <option value="Management" <?= ($selected_status == 'Management') ? 'selected' : ''; ?>>Management</option>
                        <option value="SaaS" <?= ($selected_status == 'SaaS') ? 'selected' : ''; ?>>SaaS</option>
                        </select>
                    </div>
                </div>


                <!-- Application Type -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="application_type">Application Type:</label>
                        <select id="application_type" name="application_type" class="form-control" required>
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['application_type'] : 'Please Select'; 
                        ?>
                        <option value="BM (POS)" <?= ($selected_status == 'BM (POS)') ? 'selected' : ''; ?>>BM (POS)</option>
                        <option value="eCommerce" <?= ($selected_status == 'eCommerce') ? 'selected' : ''; ?>>eCommerce</option>
                        <option value="SaaS" <?= ($selected_status == 'SaaS') ? 'selected' : ''; ?>>SaaS</option>
                        <option value="BaaS" <?= ($selected_status == 'BaaS') ? 'selected' : ''; ?>>BaaS</option>
                        <option value="Crypto" <?= ($selected_status == 'Crypto') ? 'selected' : ''; ?>>Crypto</option>
                        <option value="Wallet" <?= ($selected_status == 'Wallet') ? 'selected' : ''; ?>>Wallet</option>
                        <option value="Remittance" <?= ($selected_status == 'Remittance') ? 'selected' : ''; ?>>Remittance</option>
                        <option value="PL Back Office" <?= ($selected_status == 'PL Back Office') ? 'selected' : ''; ?>>PL Back Office</option>
                        </select>
                    </div>
                </div>
            </div>

                <hr>
                    <div class="card-body" >
                        <h4 class="no-margin"><b>Business (KYB) Details</b></h4>
                    </div>
                <hr>

                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="legal_name">Legal Business Name:</label>
                        <input type="text" id="legal_name" name="legal_name" value="<?= $pipeline_report['legal_name'] ?? '' ?>"  class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dba">DBA (Doing Business As):</label>
                        <input type="text" id="dba" name="dba" value="<?= $pipeline_report['dba'] ?? '' ?>" maxlength="100" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_address">Business Address:</label>
                        <input type="text" id="business_address" name="business_address" <?= $pipeline_report['business_address'] ?? '' ?> class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_city">City:</label>
                        <input type="text" id="business_city" name="business_city" value="<?= $pipeline_report['business_city'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_state">State:</label>
                        <input type="text" id="business_state" name="business_state" value="<?= $pipeline_report['business_state'] ?? '' ?>"  class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_zip">ZIP:</label>
                        <input type="text" id="business_zip" name="business_zip" value="<?= $pipeline_report['business_zip'] ?? '' ?>"  class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_country">Country:</label>
                        <input type="text" id="business_country" name="business_country" value="<?= $pipeline_report['business_country'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_phone">Business Phone:</label>
                        <input type="tel" id="business_phone" name="business_phone" value="<?= $pipeline_report['business_phone'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="website_url">Website URL:</label>
                        <input type="url" id="website_url" name="website_url" value="<?= $pipeline_report['website_url'] ?? 'https://' ?>"  class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_type">Corporate Type:</label>
                        <select id="business_type" name="business_type" class="form-control">
                            <?php 
                            $selected_status = isset($pipeline_report) ? $pipeline_report['business_type'] : 'Please Select'; 
                            ?>
                            <option value="C Corporation - Closely Held" <?= ($selected_status == 'C Corporation - Closely Held') ? 'selected' : ''; ?>>C Corporation - Closely Held</option>
                            <option value="C Corporation - Private Company" <?= ($selected_status == 'C Corporation - Private Company') ? 'selected' : ''; ?>>C Corporation - Private Company</option>
                            <option value="C Corporation - Public Company" <?= ($selected_status == 'C Corporation - Public Company') ? 'selected' : ''; ?>>C Corporation - Public Company</option>
                            <option value="Estate" <?= ($selected_status == 'Estate') ? 'selected' : ''; ?>>Estate</option>
                            <option value="General Partnership" <?= ($selected_status == 'General Partnership') ? 'selected' : ''; ?>>General Partnership</option>
                            <option value="Government fed/state/local" <?= ($selected_status == 'Government fed/state/local') ? 'selected' : ''; ?>>Government fed/state/local</option>
                            <option value="Limited Liability Company" <?= ($selected_status == 'Limited Liability Company') ? 'selected' : ''; ?>>Limited Liability Company</option>
                            <option value="Limited Partnership" <?= ($selected_status == 'Limited Partnership') ? 'selected' : ''; ?>>Limited Partnership</option>
                            <option value="Sole Proprietorship" <?= ($selected_status == 'Sole Proprietorship') ? 'selected' : ''; ?>>Sole Proprietorship</option>
                            <option value="Sub S Corp" <?= ($selected_status == 'Sub S Corp') ? 'selected' : ''; ?>>Sub S Corp</option>
                            <option value="Tax Exempt Organization" <?= ($selected_status == 'Tax Exempt Organization') ? 'selected' : ''; ?>>Tax Exempt Organization</option>
                            <option value="Trust" <?= ($selected_status == 'Trust') ? 'selected' : ''; ?>>Trust</option>
                            <option value="Unincorporated Association" <?= ($selected_status == 'Unincorporated Association') ? 'selected' : ''; ?>>Unincorporated Association</option>
                            <option value="Privet Personal" <?= ($selected_status == 'Privet Personal') ? 'selected' : ''; ?>>Privet Personal</option>
                            <option value="LTD" <?= ($selected_status == 'LTD') ? 'selected' : ''; ?>>LTD</option>
                            <option value="Other" <?= ($selected_status == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group">
                <label for="mcc_code">Industry/MCC Code:</label>
                <select id="mcc_code" name="mcc_code" class="form-control">
                        <option value="" selected>Select</option>
                        <?php foreach ($mcc_codes as $mcc): ?>
                            <option value="<?= $mcc['code'] ?>"
                                <?= ($mcc['code'] == $pipeline_report['mcc_code']) ? 'selected' : '' ?>>
                                <?= $mcc['code'] . ' - ' . $mcc['description'] ?>
                  			</option>                       
                        <?php endforeach; ?>
                       <option value="OTHER" style="color: red; font-weight: bold;">OTHER</option>
                    </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tin_ein">TIN / EIN:</label>
                        <input type="text" id="tin_ein" name="tin_ein" value="<?= $pipeline_report['tin_ein'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="incorporation_country">Country of Incorporation:</label>
                        <select id="incorporation_country" name="incorporation_country" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['incorporation_country'] : 'Please Select'; 
                        ?>
                        <option value="USA" <?= ($selected_status == 'USA') ? 'selected' : ''; ?>>USA</option>
                        <option value="International" <?= ($selected_status == 'International') ? 'selected' : ''; ?>>International</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="incorporation_date">Date of Incorporation:</label>
                        <input type="date" id="incorporation_date" name="incorporation_date" value="<?= $pipeline_report['incorporation_date'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="registration_number">Company Registration Number: ( For International )</label>
                        <input type="text" id="registration_number" name="registration_number" value="<?= $pipeline_report['registration_number'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ownership_structure">Ownership Structure:</label>
                        <select id="ownership_structure" name="ownership_structure" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['ownership_structure'] : 'Please Select'; 
                        ?>
                        <option value="Single Owner" <?= ($selected_status == 'Single Owner') ? 'selected' : ''; ?>>Single Owner</option>
                        <option value="Partnership" <?= ($selected_status == 'Partnership') ? 'selected' : ''; ?>>Partnership</option>
                        <option value="Corp" <?= ($selected_status == 'Corp') ? 'selected' : ''; ?>>Corp</option>

                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driving_license">Driver Licence Number</label>
                                    <input type="text" id="driving_license" name="driving_license" value="<?= $pipeline_report['driving_license'] ?? '' ?>" class="form-control" >
                                    <input type="hidden" id="merchant_status" name="merchant_status" value="<?= $pipeline_report['merchant_status'] ?? '' ?>" class="form-control" >

                                    
                                </div>
                </div> 

                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_of_license">State of Driver Licence</label>
                                    <input type="text" id="state_of_license" name="state_of_license" value="<?= $pipeline_report['state_of_license'] ?? '' ?>" class="form-control" >
                                </div>
                </div> 
            </div>

                     
        <hr>
        <div class="card-body">
            <h4 class="no-margin"><b>Representative (KYC) Details</b></h4>
        </div>
        <hr>

        <div class="row">
            <!-- First Name -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?= $pipeline_report['first_name'] ?? '' ?>" class="form-control">
                </div>
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?= $pipeline_report['last_name'] ?? '' ?>" class="form-control">
                </div>
            </div>
            <!-- Role/Relationship -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role_relationship">Role/Relationship:</label>
                    <select id="role_relationship" name="role_relationship" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['role_relationship'] : 'Please Select'; 
                        ?>
                        <option value="Owner" <?= ($selected_status == 'Owner') ? 'selected' : ''; ?>>Owner</option>
                        <option value="PSP" <?= ($selected_status == 'PSP') ? 'selected' : ''; ?>>PSP</option>
                        <option value="Assistant" <?= ($selected_status == 'Assistant') ? 'selected' : ''; ?>>Assistant</option>
                        <option value="Other" <?= ($selected_status == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>
            <!-- Contact Phone -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact_phone">Contact Phone:</label>
                    <input type="tel" id="contact_phone" name="contact_phone" value="<?= $pipeline_report['contact_phone'] ?? '' ?>" class="form-control">
                </div>
            </div>
            <!-- Email Address -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email_address">Email Address:</label>
                    <input type="email" id="email_address" name="email_address" value="<?= $pipeline_report['email_address'] ?? '' ?>" class="form-control">
                </div>
            </div>
            <!-- SSN/Passport ID -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="id_number">SSN / Passport ID:</label>
                    <input type="text" id="id_number" value="<?= $pipeline_report['id_number'] ?? '' ?>" name="id_number" class="form-control">
                </div>
            </div>
            <!-- Principal Address -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="principal_address">Principal Address:</label>
                    <select id="principal_address" name="principal_address" class="form-control">
                        <option value="" selected>Select</option>
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['principal_address'] : 'Please Select'; 
                        ?>
                        <option value="Business Street Address" <?= ($selected_status == 'Business Street Address') ? 'selected' : ''; ?>>Business Street Address</option>
                        <option value="Physical Residential Address" <?= ($selected_status == 'Physical Residential Address') ? 'selected' : ''; ?>>Physical Residential Address</option>
                </select>
                </div>
            </div>
            <!-- DOB -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" value="<?= $pipeline_report['dob'] ?? '' ?>" id="dob" name="dob" class="form-control">
                </div>
            </div>
            <!-- Home Address -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="home_address">Home Address:</label>
                    <input type="text" id="home_address" name="home_address" value="<?= $pipeline_report['home_address'] ?? '' ?>" class="form-control">
                </div>
            </div>

                            <div class="col-md-6">
                    <div class="form-group">
                        <label for="kyc_city">City:</label>
                        <input type="text" id="kyc_city" name="kyc_city" value="<?= $pipeline_report['kyc_city'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kyc_state">State:</label>
                        <input type="text" id="kyc_state" name="kyc_state"  value="<?= $pipeline_report['kyc_state'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kyc_zip">ZIP:</label>
                        <input type="text" id="kyc_zip" name="kyc_zip" value="<?= $pipeline_report['kyc_zip'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kyc_country">Country:</label>
                        <input type="text" id="kyc_country" name="kyc_country" value="<?= $pipeline_report['kyc_country'] ?? '' ?>" class="form-control">
                    </div>
                </div>

            <!-- Ownership % -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ownership_percentage">Ownership %:</label>
                    <input type="number" id="ownership_percentage" name="ownership_percentage" class="form-control" min="0" value="<?= $pipeline_report['ownership_percentage'] ?? '' ?>" max="100" step="0.01">
                </div>
            </div>
            <!-- Title/Role -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title_role">Title/Role:</label>
                    <select id="title_role" name="title_role" class="form-control">
                        <option value="" selected>Select</option>
                        <option value="CEO">CEO</option>
                        <option value="CFO">CFO</option>
                        <option value="Partner">Partner</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
        </div>

    <hr>
    <div class="card-body">
        <h4 class="no-margin"><b>Banking / Payout Information</b></h4>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="bank_name">Bank Name:</label>
                <input type="text" id="bank_name" value="<?= $pipeline_report['bank_name'] ?? '' ?>" name="bank_name" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="bank_account">Bank Account #:</label>
                <input type="text" id="bank_account" name="bank_account" value="<?= $pipeline_report['bank_account'] ?? '' ?>"  class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="routing_swift_iban">Routing / SWIFT / IBAN:</label>
                <input type="text" id="routing_swift_iban" name="routing_swift_iban" value="<?= $pipeline_report['routing_swift_iban'] ?? '' ?>"  class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_holder">Account Holder Name:</label>
                <input type="text" id="account_holder" name="account_holder" value="<?= $pipeline_report['account_holder'] ?? '' ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_type">Account Type:</label>
                <select id="account_type" name="account_type" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['account_type'] : 'Please Select'; 
                        ?>
                        <option value="Personal" <?= ($selected_status == 'Personal') ? 'selected' : ''; ?>>Personal</option>
                        <option value="Business" <?= ($selected_status == 'Business') ? 'selected' : ''; ?>>Business</option>
                </select>
            </div>
        </div>
    </div>

    <hr>
    <div class="card-body">
        <h4 class="no-margin"><b>Processing Details</b></h4>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="projected_volume">Projected Monthly Volume:</label>
                <input type="number" id="projected_volume" name="projected_volume" value="<?= $pipeline_report['projected_volume'] ?? '' ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="current_volume">Current Monthly Volume:</label>
                <input type="number" id="current_volume" name="current_volume" value="<?= $pipeline_report['current_volume'] ?? '' ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="currency">Currency:</label>
                <select id="currency" name="currency" class="form-control">
                <?php 
                $selected_status = isset($pipeline_report) ? $pipeline_report['currency'] : 'Please Select'; 
                ?>
                        <option value="USD" <?= ($selected_status == 'USD') ? 'selected' : ''; ?>>USD</option>
                        <option value="Euro" <?= ($selected_status == 'Euro') ? 'selected' : ''; ?>>Euro</option>
                        <option value="Crypto" <?= ($selected_status == 'Crypto') ? 'selected' : ''; ?>>Crypto</option>
                        <option value="Multi" <?= ($selected_status == 'Multi') ? 'selected' : ''; ?>>Multi</option>
                        <option value="Other" <?= ($selected_status == 'Other') ? 'selected' : ''; ?>>Other</option>

                </select>
            </div>
        </div>

        <div class="col-md-6" id="other_currency_box" style="display:none;">
            <div class="form-group">
                <label for="other_currency">Specify Other Currency:</label>
                <input type="text" id="other_currency" name="other_currency" class="form-control" placeholder="Enter currency name">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="processing_type">Processing Type:</label>
                <select id="processing_type" name="processing_type" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['processing_type'] : 'Please Select'; 
                        ?>
                        <option value="Card Present" <?= ($selected_status == 'Card Present') ? 'selected' : ''; ?>>Card Present</option>
                        <option value="Card Not Present" <?= ($selected_status == 'Card Not Present') ? 'selected' : ''; ?>>Card Not Present</option>
                        <option value="MOTO" <?= ($selected_status == 'MOTO') ? 'selected' : ''; ?>>MOTO</option>
                        <option value="Recurring" <?= ($selected_status == 'Recurring') ? 'selected' : ''; ?>>Recurring</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="sales_type">Sales Type:</label>
                <select id="sales_type" name="sales_type" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['sales_type'] : 'Please Select'; 
                        ?>
                        <option value="Direct" <?= ($selected_status == 'Direct') ? 'selected' : ''; ?>>Direct</option>
                        <option value="Partner" <?= ($selected_status == 'Partner') ? 'selected' : ''; ?>>Partner</option>
                        <option value="ISO" <?= ($selected_status == 'ISO') ? 'selected' : ''; ?>>ISO</option>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="risk_category">Risk Category:</label>
                <select id="risk_category" name="risk_category" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['risk_category'] : 'Please Select'; 
                        ?>
                        <option value="Low" <?= ($selected_status == 'Low') ? 'selected' : ''; ?>>Low</option>
                        <option value="Medium" <?= ($selected_status == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="High" <?= ($selected_status == 'High') ? 'selected' : ''; ?>>High</option>
                        <option value="Prohibited" <?= ($selected_status == 'Prohibited') ? 'selected' : ''; ?>>Prohibited</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="chargeback_percent">Chargeback % Avg:</label>
                <input type="number" id="chargeback_percent" name="chargeback_percent" value="<?= $pipeline_report['chargeback_percent'] ?? '' ?>" class="form-control" step="0.01">
            </div>
        </div>

<div class="col-md-6">
    <div class="form-group">
        <fieldset>
            <legend>RDR:</legend>
            
            <div class="col-md-3">
                <input type="radio" id="rdr_yes" value="YES" name="rdr" 
                    <?= (!empty($pipeline_report['rdr']) && $pipeline_report['rdr'] == 'YES' ? 'checked' : '') ?> />
                <label for="rdr_yes">YES</label>
            </div>

            <div class="col-md-3">
                <input type="radio" id="rdr_no" value="NO" name="rdr" 
                    <?= (!empty($pipeline_report['rdr']) && $pipeline_report['rdr'] == 'NO' ? 'checked' : '') ?> />
                <label for="rdr_no">NO</label>
            </div>
        </fieldset>
    </div>
</div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="boarding_platform">Boarding Platform:</label>
                <select id="boarding_platform" name="boarding_platform" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['boarding_platform'] : 'Please Select'; 
                        ?>
                        <option value="eData" <?= ($selected_status == 'eData') ? 'selected' : ''; ?>>eData</option>
                        <option value="TSYS" <?= ($selected_status == 'TSYS') ? 'selected' : ''; ?>>TSYS</option>
                        <option value="First-Data" <?= ($selected_status == 'First-Data') ? 'selected' : ''; ?>>First-Data</option>
                        <option value="Elavon" <?= ($selected_status == 'Elavon') ? 'selected' : ''; ?>>Elavon</option>
                        <option value="NAB" <?= ($selected_status == 'NAB') ? 'selected' : ''; ?>>NAB</option>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="back_processor">Back Processor:</label>
                <input type="text" id="back_processor" name="back_processor" value="<?= $pipeline_report['back_processor'] ?? '' ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="mid_assigned">MID Assigned:</label>
                <select id="mid_assigned" name="mid_assigned" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['mid_assigned'] : 'Please Select'; 
                        ?>
                        <option value="Yes" <?= ($selected_status == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?= ($selected_status == 'No') ? 'selected' : ''; ?>>No</option>
                </select>
            </div>
        </div>

                <div class="col-md-12">
            <div class="form-group">
                <label for="country">Country:</label>
                    <select id="country" name="country" class="form-control">
                    <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['country'] : 'Please Select'; 
                    ?>
                        <option value="USA" <?= ($selected_status == 'USA') ? 'selected' : ''; ?>>USA</option>
                        <option value="International" <?= ($selected_status == 'International') ? 'selected' : ''; ?>>International</option>
                    
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="boarded_urls">Boarded URLs:</label>
                <input type="text" id="boarded_urls" name="boarded_urls" value="<?= $pipeline_report['boarded_urls'] ?? '' ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="boarding_date">Boarding Date:</label>
                <input type="date" id="boarding_date" name="boarding_date" value="<?= $pipeline_report['boarding_date'] ?? '' ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="go_live_date">Go Live Date:</label>
                <input type="date" id="go_live_date" name="go_live_date" value="<?= $pipeline_report['go_live_date'] ?? '' ?>" class="form-control">
            </div>
        </div>
    </div>

    <hr>

    
    <div class="card-body">
        <h4 class="no-margin"><b>Documentation / Compliance</b></h4>
    </div>
    <hr>

  <div class="col-md-6">
<div class="form-group">
    <label>KYC/KYB Documents</label>
    <input type="file" name="kyc_kyb_docs" class="form-control" accept="application/pdf, image/png, image/gif, image/jpeg">

    <?php if (!empty($pipeline_report['kyc_kyb_docs'])): ?>
        <div class="existing-file mt-2">
            <a href="<?= base_url('uploads/pipeline_docs/'.$pipeline_report['kyc_kyb_docs']) ?>" target="_blank">
                <?= htmlspecialchars($pipeline_report['kyc_kyb_docs']) ?>
            </a>
            <br>
            <label class="text-danger mt-1">
                <input type="checkbox" name="remove_kyc_kyb_docs" value="1">
                Remove this file
            </label>
            <!-- Add this hidden field -->
            <input type="hidden" name="current_kyc_kyb_docs" value="<?= $pipeline_report['kyc_kyb_docs'] ?>">
        </div>
    <?php endif; ?>
		</div>
            </div>
  <div class="col-md-6">
            <div class="form-group">
                <label>Business License:</label>
                <input type="file" name="business_license" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">

    <?php if (!empty($pipeline_report['business_license'])): ?>
        <div class="existing-file mt-2">
            <a href="<?= base_url('uploads/pipeline_docs/'.$pipeline_report['business_license']) ?>" target="_blank">
                <?= htmlspecialchars($pipeline_report['business_license']) ?>
            </a>
            <br>
            <label class="text-danger mt-1">
                <input type="checkbox" name="remove_business_license" value="1">
                Remove this file
            </label>
            <!-- Add this hidden field -->
            <input type="hidden" name="current_business_license" value="<?= $pipeline_report['business_license'] ?>">
        </div>
    <?php endif; ?>
            </div>
                </div>
  <div class="col-md-6">
            <div class="form-group">
                <label>Articles of Incorporation:</label>
                <input type="file" name="articles_incorporation" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
    <?php if (!empty($pipeline_report['articles_incorporation'])): ?>
        <div class="existing-file mt-2">
            <a href="<?= base_url('uploads/pipeline_docs/'.$pipeline_report['articles_incorporation']) ?>" target="_blank">
                <?= htmlspecialchars($pipeline_report['articles_incorporation']) ?>
            </a>
            <br>
            <label class="text-danger mt-1">
                <input type="checkbox" name="remove_articles_incorporation" value="1">
                Remove this file
            </label>
            <!-- Add this hidden field -->
            <input type="hidden" name="current_articles_incorporation" value="<?= $pipeline_report['articles_incorporation'] ?>">
        </div>
    <?php endif; ?>
            </div>
    </div>
  <div class="col-md-6">
            <div class="form-group">
                <label>Utility Bill / Proof of Address:</label>
                <input type="file" name="utility_bill"  accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
    <?php if (!empty($pipeline_report['utility_bill'])): ?>
        <div class="existing-file mt-2">
            <a href="<?= base_url('uploads/pipeline_docs/'.$pipeline_report['utility_bill']) ?>" target="_blank">
                <?= htmlspecialchars($pipeline_report['utility_bill']) ?>
            </a>
            <br>
            <label class="text-danger mt-1">
                <input type="checkbox" name="remove_utility_bill" value="1">
                Remove this file
            </label>
            <!-- Add this hidden field -->
            <input type="hidden" name="current_utility_bill" value="<?= $pipeline_report['utility_bill'] ?>">
        </div>
    <?php endif; ?>
            </div>
    </div>
       <div class="col-md-6">
            <div class="form-group">
                <label>Refund / Shipping Policy:</label>
                <input type="file" name="refund_policy" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
    <?php if (!empty($pipeline_report['utility_bill'])): ?>
        <div class="existing-file mt-2">
            <a href="<?= base_url('uploads/pipeline_docs/'.$pipeline_report['utility_bill']) ?>" target="_blank">
                <?= htmlspecialchars($pipeline_report['utility_bill']) ?>
            </a>
            <br>
            <label class="text-danger mt-1">
                <input type="checkbox" name="remove_utility_bill" value="1">
                Remove this file
            </label>
            <!-- Add this hidden field -->
            <input type="hidden" name="current_utility_bill" value="<?= $pipeline_report['utility_bill'] ?>">
        </div>
    <?php endif; ?>
            </div>
    </div>
        <div class="col-md-6">
    <div class="form-group">
        <label for="dropbox_files">Dropbox Backup Files:</label>
        <input type="file" name="dropbox_files[]" id="dropbox_files" class="form-control" 
               accept=".pdf, .png, .gif, .jpeg, .jpg" multiple>
        <small class="form-text text-muted">Accepted: PDF, PNG, GIF, JPG. You can select multiple files.</small>
           <?php if (!empty($pipeline_report['dropbox_files'])): ?>
            <div class="existing-files mt-3">
                <h6>Current Files:</h6>
                <?php 
                $files = explode(',', $pipeline_report['dropbox_files']);
                foreach ($files as $file): 
                    if (!empty($file)) {
                        $originalFileName = preg_replace('/^\d+_\d+_/', '', basename($file));
                ?>
                    <div class="file-item">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="remove_files[]" style="display:none" value="<?= htmlspecialchars($file) ?>"> 
                        </label>
                        <a href="<?= base_url('uploads/pipeline_docs/'.$file) ?>" target="_blank">
                            <i class="fa fa-file" style="font-size:14px;color:red"></i>
                            <?= htmlspecialchars($originalFileName) ?>
                        </a>
                    </div>
                <?php 
                    }
                endforeach; 
                ?>
            </div>
        <?php endif; ?>
        
        <div id="id_file_label" style="margin-top: 5px; color: green;"></div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="dropbox_link">Dropbox/External Backup Link:</label>
        <input type="url" name="dropbox_link" id="dropbox_link" class="form-control"
               placeholder="https://www.dropbox.com/s/yourfile">
        <small class="form-text text-muted">Provide a link to a Dropbox or other backup location.</small>
    <?php if (!empty($pipeline_report['dropbox_link'])): ?>
        <div class="existing-file mt-2">
            <a href="<?= base_url('uploads/pipeline_docs/'.$pipeline_report['dropbox_link']) ?>" target="_blank">
                <?= htmlspecialchars($pipeline_report['dropbox_link']) ?>
            </a>
            <br>
            <label class="text-danger mt-1">
                <input type="checkbox" name="remove_dropbox_link" value="1">
                Remove this file
            </label>
            <!-- Add this hidden field -->
            <input type="hidden" name="current_dropbox_link" value="<?= $pipeline_report['dropbox_link'] ?>">
        </div>
    <?php endif; ?>  
</div>
    </div>
      <div class="col-md-6">
            <div class="form-group">
                <label>Voided Check / Bank Letter:</label>
                <input type="file" name="voided_check" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
                        <?php if (!empty($pipeline_report['voided_check'])): ?>
        <div class="existing-file mt-2">
            <a href="<?= base_url('uploads/pipeline_docs/'.$pipeline_report['voided_check']) ?>" target="_blank">
                <?= htmlspecialchars($pipeline_report['voided_check']) ?>
            </a>
            <br>
            <label class="text-danger mt-1">
                <input type="checkbox" name="remove_voided_check" value="1">
                Remove this file
            </label>
            <!-- Add this hidden field -->
            <input type="hidden" name="current_voided_check" value="<?= $pipeline_report['voided_check'] ?>">
        </div>
    <?php endif; ?>
            </div>
        </div>
    
    <hr>
        <div class="card-body">
            <h4 class="no-margin"><b>Follow-up Management</b></h4>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="next_followup">Next Follow-up Date:</label>
                    <input type="date" id="next_followup" name="next_followup" value="<?= $pipeline_report['next_followup'] ?? '' ?>" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="last_contacted">Last Contacted Date:</label>
                    <input type="date" id="last_contacted" name="last_contacted" value="<?= $pipeline_report['last_contacted'] ?? '' ?>" class="form-control">
                </div>
            </div>

<div class="col-md-6">
    <div class="form-group">
        <label>Extra Documents</label>
        <input type="file" id="upload_file" name="upload_file[]" class="form-control" 
               accept="application/pdf, image/png, image/gif, image/jpeg" multiple>
        
        <?php if (!empty($pipeline_report['upload_file'])): ?>
            <div class="existing-files mt-3">
                <h6>Current Files:</h6>
                <?php 
                $files = explode(',', $pipeline_report['upload_file']);
                foreach ($files as $file): 
                    if (!empty($file)) {
                        $originalFileName = preg_replace('/^\d+_\d+_/', '', basename($file));
                ?>
                    <div class="file-item">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="remove_files[]" style="display:none" value="<?= htmlspecialchars($file) ?>"> 
                        </label>
                        <a href="<?= base_url('uploads/pipeline_docs/'.$file) ?>" target="_blank">
                            <i class="fa fa-file" style="font-size:14px;color:red"></i>
                            <?= htmlspecialchars($originalFileName) ?>
                        </a>
                    </div>
                <?php 
                    }
                endforeach; 
                ?>
            </div>
        <?php endif; ?>
        
        <div id="id_file_label" style="margin-top: 5px; color: green;"></div>
    </div>
</div>


                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referred_by">Reffered by</label>
                                    <input type="text" id="referred_by" name="referred_by" value="<?= $pipeline_report['referred_by'] ?? '' ?>" class="form-control" >
                                </div>
                        </div>
<?php  $rolevalue = $this->roles_model->get($current_user->role); if (($rolevalue->name == "Employee" || $rolevalue->name == "Management")) {  ?>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="internal_notes">Internal Notes / Comments:</label>
                    <textarea id="internal_notes" name="internal_notes" rows="4" class="form-control"><?= $pipeline_report['internal_notes'] ?? '' ?></textarea>
                </div>
            </div>
<?php } ?>   
            <?php  $rolevalue = $this->roles_model->get($current_user->role); if (($rolevalue->name == "Employee" || $rolevalue->name == "Management" || $rolevalue->name == "agent")) {  ?>
                          <?php
$readonly = false;
$staff_id = get_staff_user_id(); // or use session('staff_user_id') if needed
$staff_data = $this->staff_model->get($staff_id); // assuming you have access to staff_model

if (in_array($staff_data['role'], ['agent', 'reseller'])) {
    $readonly = true;
}
?>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="underwriting">Underwriting:</label>
                    <textarea id="underwriting" name="underwriting" rows="4" class="form-control" <?= $readonly ? 'readonly' : '' ?>><?= $pipeline_report['underwriting'] ?? '' ?></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="management">Management:</label>
                    <textarea id="management" name="management" rows="4" class="form-control" <?= $readonly ? 'readonly' : '' ?>><?= $pipeline_report['management'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes" rows="4" class="form-control"><?= $pipeline_report['notes'] ?? '' ?></textarea>
                </div>
            </div>
            <?php } else { ?>

                   <div class="col-md-12">
                <div class="form-group">
                    <label for="underwriting">Underwriting:</label>
                    <textarea id="underwriting" name="underwriting" readonly rows="4" class="form-control"><?= $pipeline_report['underwriting'] ?? '' ?></textarea>
                </div>
            </div>      
            <div class="col-md-12">
                <div class="form-group">
                    <label for="management">Management:</label>
                    <textarea id="management" name="management" readonly rows="4" class="form-control"><?= $pipeline_report['management'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes" readonly rows="4" class="form-control"><?= $pipeline_report['notes'] ?? '' ?></textarea>
                </div>
            </div>

             <?php } ?>
             <?php  $rolevalue = $this->roles_model->get($current_user->role); if (($rolevalue->name == "Employee" || $rolevalue->name == "Management" || $rolevalue->name == "agent")) {  ?>
                <!-- Question 1: Existing or Cancelled Merchant Account -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="other_merchant_account">
                            Do you currently have or previously had any other merchant accounts (live or cancelled)?
                        </label>
                        <select id="other_merchant_account" name="other_merchant_account" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['other_merchant_account'] : 'Please Select'; 
                        ?>
                        <option value="Yes" <?= ($selected_status == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?= ($selected_status == 'No') ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>

                <!-- Question 2: Terminated or Matt Blacklist -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matt_blacklist">
                            Have you ever been terminated or placed on the MATCH blacklist by card brands?
                        </label>
                        <select id="matt_blacklist" name="matt_blacklist" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['matt_blacklist'] : 'Please Select'; 
                        ?>
                        <option value="Yes" <?= ($selected_status == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?= ($selected_status == 'No') ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>

                <!-- Question 3: Bankruptcy History -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bankruptcy_history">
                            Have you ever filed for bankruptcy?
                        </label>
                        <select id="bankruptcy_history" name="bankruptcy_history" class="form-control">
                        <?php 
                        $selected_status = isset($pipeline_report) ? $pipeline_report['bankruptcy_history'] : 'Please Select'; 
                        ?>
                        <option value="Yes" <?= ($selected_status == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?= ($selected_status == 'No') ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>

                <?php } ?>
           
       
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
        $(document).ready(function () {
    $('#currency').on('change', function () {
        if ($(this).val() === 'Other') {
            $('#other_currency_box').show();
        } else {
            $('#other_currency_box').hide();
            $('#other_currency').val(''); // Clear if hidden
        }
    });
});
document.getElementById('upload_file').addEventListener('change', function(e) {
    var files = e.target.files;
    var label = document.getElementById('id_file_label');
    var html = '';
    
    if (files.length > 0) {
        html += '<strong>Selected files:</strong><br>';
        for (var i = 0; i < files.length; i++) {
            html += '<i class="fa fa-check text-success"></i> ' + files[i].name + '<br>';
        }
    }
    
    label.innerHTML = html;
});

// Add event listeners for all file inputs
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const labelId = this.name + '_label';
        const label = document.getElementById(labelId);
        
        if (this.files.length > 0) {
            label.innerHTML = '<i class="fa fa-check"></i> Selected: ' + this.files[0].name;
        } else {
            label.innerHTML = '';
        }
    });
});
  
  document.addEventListener('DOMContentLoaded', function () {
    const mccSelect = document.getElementById('mcc_code');

    mccSelect.addEventListener('change', function () {
        if (this.value === 'OTHER') {
            this.style.border = '2px solid red';
            if (!document.getElementById('mcc-warning')) {
                const warning = document.createElement('p');
                warning.id = 'mcc-warning';
                warning.style.color = 'red';
                warning.textContent = 'You selected OTHER. This will be flagged for review.';
                this.closest('.form-group').appendChild(warning);
            }
        } else {
            this.style.border = '';
            const warning = document.getElementById('mcc-warning');
            if (warning) warning.remove();
        }
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