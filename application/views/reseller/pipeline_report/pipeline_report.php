<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="no-margin"><b>Add Business Merchant Application</b></h3>
                        <hr>
                            <div class="card-body" ><h4 class="no-margin"><b>Basic Information</b></h4></div>
                        <hr>
                        <?php echo form_open(base_url('reseller/pipeline_report/submit'), ['class' => 'pipeline_report-form', 'enctype' => 'multipart/form-data']); ?>

                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="row">
                <!-- Lead Source -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lead_source">Lead Source:</label>
                        <select id="lead_source" name="lead_source" class="form-control" required>
                            <option value="" selected>Select</option>
                            <option value="In-House">In-House</option>
                            <option value="Web">Web</option>
                            <option value="Partner">Partner</option>
                            <option value="Sales">Sales</option>
                            <option value="Affiliate">Affiliate</option>
                        </select>
                    </div>
                </div>
                  
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="region">Select the region:</label>
                        <select id="region" name="region" class="form-control" required>
                            <option value="" selected>Select</option>
                            <option value="U">US</option>
                            <option value="N">North America</option>
                            <option value="A">Asia Pacific</option>
                            <option value="E">Europe</option>
                            <option value="L">Latin America and Caribbean</option>
                            <option value="F">Africa</option>
                            <option value="M">Middle East</option>
                        </select>
                    </div>
                </div>


                <!-- Application Date -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="application_date">Application Date:</label>
                        <input type="date" id="application_date" name="application_date" value="<?= isset($pre_application['date_received']) ? $pre_application['date_received'] : date('Y-m-d') ?>" class="form-control" required>
                        
                    </div>
                </div>

                <!-- Application IP -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="application_ip">Application IP:</label>
                        <input type="text" id="application_ip" name="application_ip" class="form-control">

            <input type="hidden" name="pre_application_id" id="pre_application_id">
                   
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
                                 <?php if (!empty($staff_inhouse)): ?>
                          <?php foreach ($staff_inhouse as $member): ?>
                              <option value="<?= $member->staffid ?>"
                                  <?= (isset($pre_application['assigned_employee']) && $member->staffid == $pre_application['assigned_employee']) ? 'selected' : '' ?>>
                                  <?= $member->firstname . ' ' . $member->lastname ?>
                              </option>
                          <?php endforeach; ?>
                      <?php endif; ?>

                        </select>
                    </div>
                </div>

                 <?php } ?>
                  
                <!-- Company Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_name">Company Name:</label>
                        <input type="text" id="company_name" name="company_name" value="<?= $pre_application['business_name'] ?? '' ?>" class="form-control" required>
                    </div>
                </div>


                <!-- Payments Type -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_type">Payments Type:</label>
                        <select id="payment_type" name="payment_type" class="form-control" required>
                            <option value="" selected>Select</option>
                            <option value="Cards">Cards</option>
                            <option value="eWallet">eWallet</option>
                            <option value="eBank">eBank</option>
                            <option value="Remittance">Remittance</option>
                            <option value="eMoney">eMoney</option>
                            <option value="Crypto">Crypto</option>
                            <option value="Gateway">Gateway</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Management">Management</option>
                            <option value="SaaS">SaaS</option>
                          <option value="ACH">ACH</option>
                           <option value="Paypal">Paypal</option>
                           <option value="UPI">UPI</option>
                           <option value="Zelle">Zelle</option>
                        </select>
                    </div>
                </div>


                <!-- Application Type -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="application_type">Application Type:</label>
                        <select id="application_type" name="application_type" class="form-control" required>
                            <option value="" selected>Select</option>
                            <option value="BM (POS)">BM (POS)</option>
                            <option value="eCommerce">eCommerce</option>
                            <option value="Continuity">Continuity</option>
                            <option value="SaaS">SaaS</option>
                            <option value="BaaS">BaaS</option>
                            <option value="Crypto">Crypto</option>
                            <option value="Wallet">Wallet</option>
                            <option value="Remittance">Remittance</option>
                            <option value="PL Back Office">PL Back Office</option>
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
                        <input type="text" id="legal_name" name="legal_name" value="<?= $pre_application['business_name'] ?? '' ?>"  class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dba">DBA (Doing Business As):</label>
                        <input type="text" id="dba" name="dba" value="<?= $pre_application['business_system'] ?? '' ?>" maxlength="100"  class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_address">Business Address:</label>
                        <input type="text" id="business_address" name="business_address" <?= $pre_application['business_address'] ?? '' ?> class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_city">City:</label>
                        <input type="text" id="business_city" name="business_city" value="<?= $pre_application['business_city'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_state">State:</label>
                        <input type="text" id="business_state" name="business_state" value="<?= $pre_application['business_state'] ?? '' ?>"  class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_zip">ZIP:</label>
                        <input type="text" id="business_zip" name="business_zip" value="<?= $pre_application['business_zip_code'] ?? '' ?>"  class="form-control">
                    </div>
                </div>

<div class="col-md-6">
    <div class="form-group">
        <label for="business_country">Country:</label>
        <select id="business_country" name="business_country" class="form-control">
             <option value=""></option>
<?php
$countries = get_all_countries();

// Find USA entry
$usa = null;
foreach ($countries as $country) {
    if (strtolower($country['short_name']) === 'united states of america') {
        $usa = $country;
        break;
    }
}
?>

<!-- United States first -->
<?php if ($usa): ?>
    <?php
        $selected = false;

        // If client has a country, match it
        if (!empty($client->country) && $client->country == $usa['country_id']) {
            $selected = true;
        }

        // If no client country set, default to United States
        if (empty($client->country)) {
            $selected = true;
        }
    ?>
    <option
        value="<?= e($usa['country_id']); ?>"
        <?= set_select('country', $usa['country_id'], $selected); ?>>
        <?= e($usa['short_name']); ?>
    </option>
<?php endif; ?>

<!-- Then list all others (skip USA to avoid duplication) -->
<?php foreach ($countries as $country): ?>
    <?php if (strtolower($country['short_name']) === 'united states of america') continue; ?>

    <?php
        $selected = false;
        if (!empty($client->country) && $client->country == $country['country_id']) {
            $selected = true;
        }
    ?>
    <option
        value="<?= e($country['country_id']); ?>"
        <?= set_select('country', $country['country_id'], $selected); ?>>
        <?= e($country['short_name']); ?>
    </option>
<?php endforeach; ?>


        </select>
    </div>
</div>
                  
<div class="col-md-6"> 
  <div class="form-group">
    <label for="country_code">Country Code:</label>
    <select id="country_code" name="country_code" class="form-control select2">
        <option value="" disabled selected hidden>Select</option>

        <!-- Make USA the first option -->
        <option value="+1" 
            <?= (isset($pipeline_report['country_code']) && $pipeline_report['country_code'] == '+1') 
                ? 'selected' : ''; ?>>
            United States of America (+1)
        </option>

        <?php foreach ($countries as $country): ?>
            <?php 
                // Skip USA if it's already added manually
                if (strtolower($country['short_name']) === 'united states of america') {
                    continue;
                }

                $code = $country['calling_code']; 
                $code_display = str_replace(' ', '', $code);
            ?>
            <option value="+<?= $code_display; ?>"
                <?= (isset($pipeline_report['country_code']) && $pipeline_report['country_code'] == '+' . $code_display) 
                    ? 'selected' : ''; ?>>
                <?= $country['short_name']; ?> (+<?= $code_display; ?>)
            </option>
        <?php endforeach; ?>
    </select>
  </div>          
</div>
                     

<div class="col-md-6">
    <div class="form-group">
        <label for="business_phone">Business Phone:</label>
        <input type="tel" id="business_phone" name="business_phone" value="<?= $pre_application['business_telephone_number'] ?? '' ?>" class="form-control" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  maxlength="10">
                                </div>
    </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label for="website_url">Website URL:</label>
                        <input type="url" id="website_url" name="website_url" value="<?= !empty($pre_application['business_website_address']) ? $pre_application['business_website_address'] : 'https://'; ?>"  class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="business_type">Corporate Type:</label>
                        <select id="business_type" name="business_type" class="form-control">
                            <option value="" selected>Select</option>
                            <option value="C Corporation - Closely Held">C Corporation - Closely Held</option>
                            <option value="C Corporation - Private Company">C Corporation - Private Company</option>
                            <option value="C Corporation - Public Company">C Corporation - Public Company</option>
                            <option value="Estate">Estate</option>
                            <option value="General Partnership">General Partnership</option>
                            <option value="Government fed/state/local">Government fed/state/local</option>
                            <option value="Limited Liability Company">Limited Liability Company</option>
                            <option value="Limited Partnership">Limited Partnership</option>
                            <option value="Sole Proprietorship">Sole Proprietorship</option>
                            <option value="Sub S Corp">Sub S Corp</option>
                            <option value="Tax Exempt Organization">Tax Exempt Organization</option>
                            <option value="Trust">Trust</option>
                            <option value="Unincorporated Association">Unincorporated Association</option>
                            <option value="Privet Personal">Privet Personal</option>
                            <option value="LTD">LTD</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

<div class="col-md-6">
    <div class="form-group">
        <label for="mcc_code">Industry/MCC Code:</label>
        <input list="mcc_codes_list" id="mcc_code" name="mcc_code" class="form-control" placeholder="Search MCC Code"
               value="<?= isset($pipeline_report['mcc_code']) ? $pipeline_report['mcc_code'] : '' ?>">
        
        <datalist id="mcc_codes_list">
            <?php foreach ($mcc_codes as $mcc): ?>
                <option value="<?= $mcc['code'] . ' - ' . $mcc['description'] ?>"></option>
            <?php endforeach; ?>
           <option value="OTHER">OTHER</option>
        </datalist>
      
        <!-- Hidden message for OTHER -->
        <small id="otherMessage" class="text-danger" style="display: none; margin-top:5px;">
            Please enter your custom Industry/MCC Code below
        </small>

        <!-- Hidden input for custom MCC code -->
        <input type="text" name="custom_mcc_code" id="custom_mcc_code" class="form-control mt-2"
               placeholder="Enter custom MCC Code" style="display: none;"
               value="<?= isset($pipeline_report['custom_mcc_code']) ? $pipeline_report['custom_mcc_code'] : '' ?>">
    </div>
</div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tin_ein">TIN / EIN:</label>
                        <input type="text" id="tin_ein" name="tin_ein" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="incorporation_country">Country of Incorporation:</label>
                        <select id="incorporation_country" name="incorporation_country" class="form-control">
                               <option value=""></option>
                                <?php
$countries = get_all_countries();

// Find USA entry
$usa = null;
foreach ($countries as $country) {
    if (strtolower($country['short_name']) === 'united states of america') {
        $usa = $country;
        break;
    }
}
?>

<!-- United States first -->
<?php if ($usa): ?>
    <?php
        $selected = false;

        // If client has a country, match it
        if (!empty($client->country) && $client->country == $usa['country_id']) {
            $selected = true;
        }

        // If no client country set, default to United States
        if (empty($client->country)) {
            $selected = true;
        }
    ?>
    <option
        value="<?= e($usa['country_id']); ?>"
        <?= set_select('country', $usa['country_id'], $selected); ?>>
        <?= e($usa['short_name']); ?>
    </option>
<?php endif; ?>

<!-- Then list all others (skip USA to avoid duplication) -->
<?php foreach ($countries as $country): ?>
    <?php if (strtolower($country['short_name']) === 'united states of america') continue; ?>

    <?php
        $selected = false;
        if (!empty($client->country) && $client->country == $country['country_id']) {
            $selected = true;
        }
    ?>
    <option
        value="<?= e($country['country_id']); ?>"
        <?= set_select('country', $country['country_id'], $selected); ?>>
        <?= e($country['short_name']); ?>
    </option>
<?php endforeach; ?>


                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="incorporation_date">Date of Incorporation:</label>
                        <input type="date" id="incorporation_date" name="incorporation_date" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="registration_number">Company Registration Number: ( For International )</label>
                        <input type="text" id="registration_number" name="registration_number" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ownership_structure">Ownership Structure:</label>
                        <select id="ownership_structure" name="ownership_structure" class="form-control">
                            <option value="" selected>Select</option>
                            <option value="Single Owner">Single Owner</option>
                            <option value="Partnership">Partnership</option>
                            <option value="Corp">Corp</option>
                        </select>
                    </div>
                </div>

				</div>
                     
        <hr>
        <div class="card-body">
            <h4 class="no-margin"><b>Representative (KYC) Details</b></h4>
        </div>
        <hr>

        <div id="representative-container">
    <div class="row representative" data-index="1">
            <!-- First Name -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?= $pre_application['first_name'] ?? '' ?>" class="form-control">
                </div>
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?= $pre_application['last_name'] ?? '' ?>" class="form-control">
                </div>
            </div>

            <!-- Role/Relationship -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role_relationship">Role/Relationship:</label>
                    <select id="role_relationship" name="role_relationship" class="form-control">
                        <option value="" selected>Select</option>
                        <option value="Owner">Owner</option>
                        <option value="PSP">PSP</option>
                        <option value="Assistant">Assistant</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <!-- Contact Phone -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact_phone">Mobile Number:</label>
                    <input type="tel" id="contact_phone" name="contact_phone" value="<?= $pre_application['cell_number'] ?? '' ?>" class="form-control"  maxlength="10" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                </div>
            </div>
            <!-- Email Address -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email_address">Email Address:</label>
                    <input type="email" id="email_address" name="email_address" value="<?= $pre_application['personal_email_address'] ?? '' ?>" class="form-control">
                </div>
            </div>
            <!-- SSN/Passport ID -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="id_number">SSN / Passport ID:</label>
                    <input type="text" id="id_number" value="<?= $pre_application['ssn_passport'] ?? '' ?>" name="id_number" class="form-control">
                </div>
            </div>
                   <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driving_license">Driver Licence Number</label>
                                    <input type="text" id="driving_license" name="driving_license" value="<?= $pipeline_report['driving_license'] ?? '' ?>" class="form-control" <?= $is_readonly ? 'disabled' : '' ?>>
                                    <input type="hidden" id="merchant_status" name="merchant_status" value="<?= $pipeline_report['merchant_status'] ?? '' ?>" class="form-control" >

                                    
                                </div>
                </div> 

                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_of_license">State of Driver Licence</label>
                                    <input type="text" id="state_of_license" name="state_of_license" value="<?= $pipeline_report['state_of_license'] ?? '' ?>" class="form-control" <?= $is_readonly ? 'disabled' : '' ?>>
                                </div>
                </div> 
            <!-- Principal Address -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="principal_address">Principal Address:</label>
                    <select id="principal_address" name="principal_address" class="form-control">
                        <option value="" selected>Select</option>
                        <option value="Business Street Address">Business Street Address</option>
                        <option value="Physical Residential Address">Physical Residential Address</option>
                    </select>
                </div>
            </div>
            <!-- DOB -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" value="<?= $pre_application['date_of_birth'] ?? '' ?>" id="dob" name="dob" class="form-control">
                </div>
            </div>
            <!-- Home Address -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="home_address">Business Address:</label>
                    <input type="text" id="home_address" name="home_address" value="<?= $pre_application['home_address'] ?? '' ?>" class="form-control">
                </div>
            </div>

                            <div class="col-md-6">
                    <div class="form-group">
                        <label for="kyc_city">City:</label>
                        <input type="text" id="kyc_city" name="kyc_city" value="<?= $pre_application['city'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kyc_state">State:</label>
                        <input type="text" id="kyc_state" name="kyc_state"  value="<?= $pre_application['state'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kyc_zip">ZIP:</label>
                        <input type="text" id="kyc_zip" name="kyc_zip" value="<?= $pre_application['zip_code'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kyc_country">Country:</label>
                         <select id="kyc_country" name="kyc_country" class="form-control">
                      <option value=""></option>
                      <?php
$countries = get_all_countries();

// Find USA entry
$usa = null;
foreach ($countries as $country) {
    if (strtolower($country['short_name']) === 'united states of america') {
        $usa = $country;
        break;
    }
}
?>

<!-- United States first -->
<?php if ($usa): ?>
    <?php
        $selected = false;

        // If client has a country, match it
        if (!empty($client->country) && $client->country == $usa['country_id']) {
            $selected = true;
        }

        // If no client country set, default to United States
        if (empty($client->country)) {
            $selected = true;
        }
    ?>
    <option
        value="<?= e($usa['country_id']); ?>"
        <?= set_select('country', $usa['country_id'], $selected); ?>>
        <?= e($usa['short_name']); ?>
    </option>
<?php endif; ?>

<!-- Then list all others (skip USA to avoid duplication) -->
<?php foreach ($countries as $country): ?>
    <?php if (strtolower($country['short_name']) === 'united states of america') continue; ?>

    <?php
        $selected = false;
        if (!empty($client->country) && $client->country == $country['country_id']) {
            $selected = true;
        }
    ?>
    <option
        value="<?= e($country['country_id']); ?>"
        <?= set_select('country', $country['country_id'], $selected); ?>>
        <?= e($country['short_name']); ?>
    </option>
<?php endforeach; ?>


                      </select>
                    </div>
                </div>

            <!-- Ownership % -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ownership_percentage">Ownership %:</label>
                      <input type="number" id="ownership_percentage" name="ownership_percentage" onblur="checkOwnershipPercentage(this)" class="form-control" min="0" value="<?= $pre_application['owner_percentage'] ?? '' ?>" max="100" step="0.01">
                </div>
            </div>
            <!-- Title/Role -->
<div class="col-md-6">
    <div class="form-group">
        <label for="title_role">Principal Title:</label>
        <select id="title_role" name="title_role" class="form-control">
            <option value="" selected>Select</option>
            <option value="Accounting Manager">Accounting Manager</option>
            <option value="Authorized Signer">Authorized Signer</option>
            <option value="Controller">Controller</option>
            <option value="Chief Executive Officer">Chief Executive Officer</option>
            <option value="Chief Financial Officer">Chief Financial Officer</option>
            <option value="Chairman">Chairman</option>
            <option value="Chief Operating Officer">Chief Operating Officer</option>
            <option value="Cosigner">Cosigner</option>
            <option value="Director">Director</option>
            <option value="General Manager">General Manager</option>
            <option value="General Partner">General Partner</option>
            <option value="Guarantor">Guarantor</option>
            <option value="Managing Member">Managing Member</option>
            <option value="Officer">Officer</option>
            <option value="Owner/Proprietor">Owner/Proprietor</option>
            <option value="President">President</option>
            <option value="Partner/Principal">Partner/Principal</option>
            <option value="Secretary">Secretary</option>
            <option value="Treasurer">Treasurer</option>
            <option value="Trustee">Trustee</option>
            <option value="Vice President">Vice President</option>
            <option value="Other">Other</option>
        </select>
    </div>
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
                <input type="text" id="bank_name" value="<?= $pre_application['bank_name'] ?? '' ?>" name="bank_name" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="bank_account">Bank Account #:</label>
                <input type="text" id="bank_account" name="bank_account" value="<?= $pre_application['account_number'] ?? '' ?>"  class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="routing_swift_iban">Routing / SWIFT / IBAN:</label>
                <input type="text" id="routing_swift_iban" name="routing_swift_iban" value="<?= $pre_application['routing_number'] ?? '' ?>"  class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_holder">Account Holder Name:</label>
                <input type="text" id="account_holder" name="account_holder" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_type">Account Type:</label>
                <select id="account_type" name="account_type" class="form-control">
                    <option value="" selected>Select</option>
                    <option value="Personal">Personal</option>
                    <option value="Business">Business</option>
                  <option value="Wallet">Wallet</option>
                </select>
            </div>
        </div>
           <div class="col-md-6">
                    <div class="form-group">
                        <label for="bank_location">Bank Location:</label>
                        <select id="bank_location" name="bank_location" class="form-control">
                            <option value="" selected>Select</option>
                            <option value="USA">USA</option>
                            <option value="INTL">INTL</option>
                            <option value="CRYPTO">CRYPTO</option>
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
                <input type="number" id="projected_volume" name="projected_volume" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="current_volume">Current Monthly Volume:</label>
                <input type="number" id="current_volume" name="current_volume" value="<?= $pre_application['average_monthly_volume'] ?? '' ?>" class="form-control">
            </div>
        </div>
<div class="col-md-6">
    <div class="form-group">
        <label for="currency">Currency:</label>
        <select id="currency" name="currency" class="form-control">
            <option value="" selected>Select</option>
            <option value="USD">USD</option>
            <option value="Euro">Euro</option>
            <option value="Crypto">Crypto</option>
            <option value="Multi">Multi</option>
            <option value="Other">Other</option>
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
                    <option value="" selected>Select</option>
                    <option value="Card Present">Card Present</option>
                    <option value="Card Not Present">Card Not Present</option>
                    <option value="MOTO">MOTO</option>
                    <option value="Recurring">Recurring</option>
                   <option value="Crypto">Crypto</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="sales_type">Sales Type:</label>
                <select id="sales_type" name="sales_type" class="form-control">
                    <option value="" selected>Select</option>
                    <option value="Direct">Direct</option>
                    <option value="Partner">Partner</option>
                    <option value="ISO">ISO</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="risk_category">Risk Category:</label>
                <select id="risk_category" name="risk_category" class="form-control">
                    <option value="" selected>Select</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Prohibited">Prohibited</option>
                    <option value="Restricted">Restricted</option>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="chargeback_percent">Chargeback % Avg:</label>
                <input type="number" id="chargeback_percent" name="chargeback_percent" class="form-control" step="0.01">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
            <fieldset>
            <legend>Chargeback Management:</legend>

            <div class="col-md-3">
                <input type="checkbox" id="rdr_yes" value="YES" name="rdr" />
                <label for="YES">YES</label>
            </div>

            <div class="col-md-3">
                <input type="checkbox" id="rdr_no" value="NO" name="rdr" />
                <label for="NO">NO</label>
            </div>
            </fieldset>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="boarding_platform">Boarding Platform:</label>
                <select id="boarding_platform" name="boarding_platform" class="form-control">
                    <option value="" selected>Select</option>
                    <option value="eData">eData</option>
                    <option value="TSYS">TSYS</option>
                    <option value="First-Data">First-Data</option>
                    <option value="Elavon">Elavon</option>
                    <option value="NAB-EPX">NAB-EPX</option>
                    <option value="International">International</option>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="back_processor">Back Processor:</label>
                <input type="text" id="back_processor" name="back_processor" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="mid_assigned">MID Assigned:</label>
                <select id="mid_assigned" name="mid_assigned" class="form-control">
                    <option value="" selected>Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
        </div>

                <div class="col-md-12">
            <div class="form-group">
                <label for="country">Country:</label>
                    <select id="country" name="country" class="form-control">
                   <option value=""></option>
                      <?php
$countries = get_all_countries();

// Find USA entry
$usa = null;
foreach ($countries as $country) {
    if (strtolower($country['short_name']) === 'united states of america') {
        $usa = $country;
        break;
    }
}
?>

<!-- United States first -->
<?php if ($usa): ?>
    <?php
        $selected = false;

        // If client has a country, match it
        if (!empty($client->country) && $client->country == $usa['country_id']) {
            $selected = true;
        }

        // If no client country set, default to United States
        if (empty($client->country)) {
            $selected = true;
        }
    ?>
    <option
        value="<?= e($usa['country_id']); ?>"
        <?= set_select('country', $usa['country_id'], $selected); ?>>
        <?= e($usa['short_name']); ?>
    </option>
<?php endif; ?>

<!-- Then list all others (skip USA to avoid duplication) -->
<?php foreach ($countries as $country): ?>
    <?php if (strtolower($country['short_name']) === 'united states of america') continue; ?>

    <?php
        $selected = false;
        if (!empty($client->country) && $client->country == $country['country_id']) {
            $selected = true;
        }
    ?>
    <option
        value="<?= e($country['country_id']); ?>"
        <?= set_select('country', $country['country_id'], $selected); ?>>
        <?= e($country['short_name']); ?>
    </option>
<?php endforeach; ?>


                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="boarded_urls">Boarded URLs:</label>
                <input type="text" id="boarded_urls" name="boarded_urls" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="boarding_date">Boarding Date:</label>
                <input type="date" id="boarding_date" name="boarding_date" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="go_live_date">Go Live Date:</label>
                <input type="date" id="go_live_date" name="go_live_date" class="form-control">
            </div>
        </div>
    </div>

    <hr>
    <div class="card-body">
        <h4 class="no-margin"><b>Documentation / Compliance</b></h4>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>KYC/KYB Documents Upload:</label>
                <input type="file" name="kyc_kyb_docs" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control" >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Business License:</label>
                <input type="file" name="business_license" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Articles of Incorporation:</label>
                <input type="file" name="articles_incorporation" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Utility Bill / Proof of Address:</label>
                <input type="file" name="utility_bill"  accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Refund / Shipping Policy:</label>
                <input type="file" name="refund_policy" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
            </div>
        </div>
<div class="col-md-6">
    <div class="form-group">
        <label for="dropbox_files">Dropbox Backup Files:</label>
        <input type="file" name="dropbox_files[]" id="dropbox_files" class="form-control" 
               accept=".pdf, .png, .gif, .jpeg, .jpg" multiple>
        <small class="form-text text-muted">Accepted: PDF, PNG, GIF, JPG. You can select multiple files.</small>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="dropbox_link">Dropbox/External Backup Link:</label>
        <input type="url" name="dropbox_link" id="dropbox_link" class="form-control"
               placeholder="https://www.dropbox.com/s/yourfile">
        <small class="form-text text-muted">Provide a link to a Dropbox or other backup location.</small>
    </div>
</div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Voided Check / Bank Letter:</label>
                <input type="file" name="voided_check" accept="application/pdf, image/png, image/gif, image/jpeg"  class="form-control">
            </div>
        </div>
    </div>
    <hr>
    <?php  $rolevalue = $this->roles_model->get($current_user->role); if (($rolevalue->name == "Employee" || $rolevalue->name == "Management")) {  ?>
        <div class="card-body">
            <h4 class="no-margin"><b>Follow-up Management</b></h4>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="next_followup">Next Follow-up Date:</label>
                    <input type="date" id="next_followup" name="next_followup" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="last_contacted">Last Contacted Date:</label>
                    <input type="date" id="last_contacted" name="last_contacted" class="form-control">
                </div>
            </div>

             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="upload_file">Extra Upload Documents</label>
                                    <input type="file" id="upload_file" name="upload_file[]" class="form-control" accept="application/pdf, image/png, image/gif, image/jpeg" multiple>
                                                                <!-- Show Existing File Name & Download Link -->
                            <?php if (!empty($pre_application['upload_file'] ?? '')): ?>
                                <?php 
                                    // Extract original file name (remove the unique ID prefix)
                                    $filePath = $pre_application['upload_file'];
                                    //$originalFileName = preg_replace('/^\d+_\d+_/', '', basename($filePath)); 

                                    $files = explode(',', $pre_application['upload_file']);
                                    foreach ($files as $file) {
                                    $originalFileName = preg_replace('/^\d+_\d+_/', '', basename($file));
                                    echo '<p><i class="fa fa-file" style="font-size:14px;color:red"></i><a href="' . base_url('uploads/pipeline_docs/' . $file) . '" target="_blank">' . ' ' . htmlspecialchars($originalFileName) . '</a></p>';
                                        }
                                    ?>
                                      
                                
                            <?php endif; ?>
        
                                <!-- Placeholder for Selected File Name -->
                                <p id="id_file_label" style="margin-top: 5px; color: green;"></p>
                                </div>
                        </div> 


                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referred_by">Reffered by</label>
                                    <input type="text" id="referred_by" name="referred_by" class="form-control" >
                                </div>
                        </div>

            <?php } ?>            
<?php  $rolevalue = $this->roles_model->get($current_user->role); if (($rolevalue->name == "Employee" || $rolevalue->name == "Management")) {  ?>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="internal_notes">Internal Notes / Comments:</label>
                    <textarea id="internal_notes" name="internal_notes" rows="4" class="form-control"></textarea>
                </div>
            </div>     
             <?php } ?> 
          <?php  $rolevalue = $this->roles_model->get($current_user->role); if (($rolevalue->name == "Employee" || $rolevalue->name == "Management")) {  ?>
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
            <?php } ?>
        <?php  $rolevalue = $this->roles_model->get($current_user->role); if (($rolevalue->name == "Employee" || $rolevalue->name == "Management" || $rolevalue->name == "agent")) {  ?>
                <!-- Question 1: Existing or Cancelled Merchant Account -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="other_merchant_account">
                            Do you currently have or previously had any other merchant accounts (live or cancelled)?
                        </label>
                        <select id="other_merchant_account" name="other_merchant_account" class="form-control">
                            <option value="" selected>Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
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
                            <option value="" selected>Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
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
                            <option value="" selected>Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                      <div class="col-md-12">
                <div class="form-group">
                    <label for="internal_notes">Internal Notes / Comments:</label>
                    <textarea id="internal_notes" name="internal_notes" rows="4" class="form-control" ></textarea>
                </div>
            </div>

                <?php } ?>
        <div class="col-md-12">
                        <!-- Submit Button -->
                        <div class="panel-footer text-right" style="float:right">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
  
document.addEventListener('DOMContentLoaded', function() {
    const mccInput = document.getElementById('mcc_code');
    const otherMessage = document.getElementById('otherMessage');
    const customMccInput = document.getElementById('custom_mcc_code');

    function toggleOtherField() {
        if (mccInput.value.trim().toUpperCase() === 'OTHER') {
            otherMessage.style.display = 'block';
            customMccInput.style.display = 'block';
        } else {
            otherMessage.style.display = 'none';
            customMccInput.style.display = 'none';
            customMccInput.value = '';
        }
    }

    // Show OTHER field if already selected on page load
    toggleOtherField();

    // Listen to typing or selecting from datalist
    mccInput.addEventListener('input', toggleOtherField);
});
  
function checkOwnershipPercentage(input) {
    const percentage = parseFloat(input.value);
    const container = document.getElementById('representative-container');

    if (percentage > 25) {
        const currentCount = container.querySelectorAll('.representative').length;
        const lastRep = container.querySelector('.representative:last-of-type');
        const newIndex = currentCount + 1;
        const clone = lastRep.cloneNode(true);
        clone.classList.add('representative-clone');
        clone.setAttribute('data-index', newIndex);

        clone.querySelectorAll('input, select').forEach(function(el) {
            if (el.name) {
                const baseName = el.name.replace(/\[\d*\]/g, '').replace(/_\d+$/, '');
                el.name = `${baseName}_${newIndex}`;
            }
            if (el.id) {
                const baseId = el.id.replace(/_\d+$/, '');
                el.id = `${baseId}_${newIndex}`;
            }
            el.value = '';
        });
      

        const headerHTML = `
            <hr> 
            <div class="card-body">
                <h4 class="no-margin"><b>Owner ${newIndex}</b></h4>
            </div>
            <hr>
        `;
        const headerDiv = document.createElement('div');
        headerDiv.innerHTML = headerHTML;

        container.appendChild(headerDiv);
        container.appendChild(clone);
    }
}

</script>

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

  window.onload = function () {
    const path = window.location.pathname;
    const segments = path.split('/');
    const id = segments[segments.length - 1];
    document.getElementById('merchant_status').value = id;
    document.getElementById('pre_application_id').value = id;

  };
  <?php  $rolevalue = $this->roles_model->get($current_user->role);
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
