<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO / ISV / Affiliates / Sales partner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .form-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .form-group label {
            font-weight: 500;
            color: #333;
        }
        .form-control {
            border-radius: 4px;
        }
        .col-left, .col-right {
            padding: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="card">
            <div class="card-header">ISO / ISV / Affiliates / Sales partner</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('agent/submit'); ?>" enctype="multipart/form-data">
      
               <!-- ✅ CSRF protection -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
                           value="<?= $this->security->get_csrf_hash(); ?>">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6 col-left">
                            <div class="form-section">
                                
                                <div class="form-group">
                                    <label for="business_nature">Business nature of your company:</label>
                                    <select id="business_nature" name="business_nature" class="form-control">
                                        <option value="">Select</option>
                                        <option value="ISO">ISO</option>
                                        <option value="Reseller">Reseller</option>
                                        <option value="Agent">Agent</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                    <div class="form-group">
                                    <label for="years_in_business">Years in the business:</label>
                                    <select id="years_in_business" name="years_in_business" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1-3">1-3</option>
                                        <option value="3-5">3-5</option>
                                        <option value="More than 15">More than 15</option>
                                        <option value="New">New</option>
                                    </select>
                                </div>
                                     <div class="form-group">
                                    <label for="process_relationship">Your current processing relationships:</label>
                                    <input type="text" id="process_relationship" placeholder="Relationships" name="process_relationship" class="form-control">
                                </div>
                              <div class="form-group">
                                    <label for="sales_reps">Do you have sales reps in your office:</label>
                                    <select id="sales_reps" name="sales_reps" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1-3">1-3</option>
                                        <option value="3-5">3-5</option>
                                        <option value="More than 15">More than 15</option>
                                        <option value="New">New</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="primary_interest">Your primary interest is:</label>
                                    <select id="primary_interest" name="primary_interest[]" class="form-control" multiple>
                                        <option value="">Select</option>
                                        <option value="POS">POS</option>
                                        <option value="Bonus program">Bonus program</option>
                                        <option value="Residual loan">Residual loan</option>
                                        <option value="Portfolio sale">Portfolio sale</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="lead_source">Lead Source:</label>
                                    <select id="lead_source" name="lead_source" class="form-control">
                                        <option value="">Select</option>
                                        <option value="In-House">In-House</option>
                                        <option value="Web">Web</option>
                                        <option value="Partner">Partner</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Affiliate" selected>Affiliate</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="region">Select the region:</label>
                                    <select id="region" name="region" class="form-control">
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="U">USA</option>
                                        <option value="N">North America</option>
                                        <option value="A">Asia Pacific</option>
                                        <option value="E">Europe</option>
                                        <option value="L">Latin America and Caribbean</option>
                                        <option value="F">Africa</option>
                                        <option value="M">Middle East</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="application_date">Application Date:</label>
                                    <input type="date" id="application_date" name="application_date" value="<?= date('Y-m-d') ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="application_ip">Application IP:</label>
                                    <input type="text" id="application_ip" name="application_ip" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Company Name:</label>
                                    <input type="text" id="company_name" name="company_name" class="form-control">
                                </div>
                           
                            </div>

                            <div class="form-section">
                                <h5>Representative (KYC) Details</h5>
                                <div class="form-group">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="role_relationship">Role/Relationship:</label>
                                    <select id="role_relationship" name="role_relationship" class="form-control">
                                <option value="" disabled selected hidden>Select</option>
                                <option value="Owner">Owner</option>
                                <option value="PSP">PSP</option>
                                <option value="Assistant">Assistant</option>
                                <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="contact_phone">Mobile Number:</label>
                                    <input type="tel" id="contact_phone" name="contact_phone" class="form-control" maxlength="12" pattern="\d*">
                                </div>
                                <div class="form-group">
                                    <label for="email_address">Email Address:</label>
                                    <input type="email" id="email_address" name="email_address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="id_number">SSN / Passport ID:* </label>
                                    <input type="text" id="id_number" name="id_number" class="form-control" required>
                                </div>
                              <div class="form-group">
                                    <label for="principal_address">Principal Address:</label>
                                    <select id="principal_address" name="principal_address" class="form-control">
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="Business Street Address">Business Street Address</option>
                                        <option value="Physical Residential Address">Physical Residential Address</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="dob">Date of Birth:</label>
                                    <input type="date" id="dob" name="dob" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="home_address">Home Address:</label>
                                    <input type="text" id="home_address" name="home_address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="kyc_city">City:</label>
                                    <input type="text" id="kyc_city" name="kyc_city" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="kyc_state">State:</label>
                                    <input type="text" id="kyc_state" name="kyc_state" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="kyc_zip">ZIP:</label>
                                    <input type="text" id="kyc_zip" name="kyc_zip" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="kyc_country">Country:</label>
                                    <select id="kyc_country" name="kyc_country" class="form-control">
                                         <option value=""></option>
                      
            <?php
            $countries = get_all_countries();

            // Sort countries so that "United States" appears first
            usort($countries, function($a, $b) {
                return ($a['short_name'] === 'United States of America') ? -1 : 1;
            });

            foreach ($countries as $country) {
                $selected = false;

                // If client has a country, match it
                if (!empty($client->country) && $client->country == $country['country_id']) {
                    $selected = true;
                }

                // If no client country is set, default to United States
                if (empty($client->country) && $country['short_name'] === "United States of America") {
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
                                <div class="form-group">
                                    <label for="ownership_percentage">Ownership %:</label>
                                    <input type="number" id="ownership_percentage" name="ownership_percentage" class="form-control" min="0" max="100" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="title_role">Principal Title:</label>
                                    <select id="title_role" name="title_role" class="form-control">
                                    <option value="" disabled selected hidden>Select</option>
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
                            <div class="form-section">
                                <h5>Banking / Payout Information</h5>
                                <div class="form-group">
                                    <label for="bank_name">Bank Name:</label>
                                    <input type="text" id="bank_name" name="bank_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="bank_account">Bank Account #:</label>
                                    <input type="text" id="bank_account" name="bank_account" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="routing_swift_iban">Routing / SWIFT / IBAN:</label>
                                    <input type="text" id="routing_swift_iban" name="routing_swift_iban" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="account_holder">Account Holder Name:</label>
                                    <input type="text" id="account_holder" name="account_holder" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="account_type">Bank Account Type:</label>
                                    <select id="account_type" name="account_type" class="form-control">
                                        <option value="" selected>Select</option>
                                        <option value="Personal Checking">Personal Checking</option>
                                      <option value="Personal Saving">Personal Saving</option>
                                      <option value="Business Checking">Business Checking</option>
                                      <option value="Business Saving">Business Saving</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                  <label for="bank_location">Bank Location:</label>
                                  <select id="bank_location" name="bank_location" class="form-control">
                                      <option value="" selected>Select</option>
                                      <option value="USA">USA</option>
                                      <option value="INTL">INTL</option>
                                      <option value="CRYPTO">CRYPTO</option>
                                  </select>
                              </div>
                                      <div class="form-group">
                                    <label for="account_holder">Bank Address:</label>
                                    <input type="text" id="bank_address" name="bank_address" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="col-md-6 col-right">
                            <div class="form-section">
                                <h5>Business (KYB) Details</h5>
                                              <div class="form-group">
                                    <label for="legal_name">Legal Business Name:</label>
                                    <input type="text" id="legal_name" name="legal_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="dba">DBA (Doing Business As):</label>
                                    <input type="text" id="dba" name="dba" class="form-control" maxlength="21">
                                </div>
                                <div class="form-group">
                                    <label for="business_address">Business Address:</label>
                                    <input type="text" id="business_address" name="business_address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="business_city">City:</label>
                                    <input type="text" id="business_city" name="business_city" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="business_state">State:</label>
                                    <input type="text" id="business_state" name="business_state" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="business_zip">ZIP:</label>
                                    <input type="text" id="business_zip" name="business_zip" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="business_country">Country:</label>
                                    <select id="business_country" name="business_country" class="form-control">
                                       <option value=""></option>
                            
            <?php
            $countries = get_all_countries();

            // Sort countries so that "United States" appears first
            usort($countries, function($a, $b) {
                return ($a['short_name'] === 'United States of America') ? -1 : 1;
            });

            foreach ($countries as $country) {
                $selected = false;

                // If client has a country, match it
                if (!empty($client->country) && $client->country == $country['country_id']) {
                    $selected = true;
                }

                // If no client country is set, default to United States
                if (empty($client->country) && $country['short_name'] === "United States of America") {
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

                                <div class="form-group">
                                    <label for="business_phone">Business Phone:</label>
                                    <input type="tel" id="business_phone" name="business_phone" class="form-control" maxlength="12" pattern="\d*">
                                </div>
                                <div class="form-group">
                                    <label for="website_url">Website URL:</label>
                                    <input type="url" id="website_url" name="website_url" class="form-control" value="https://">
                                </div>
                                <div class="form-group">
                                    <label for="business_type">Corporate Type:</label>
                                    <select id="business_type" name="business_type" class="form-control">
                                    <option value="" disabled selected hidden>Select</option>
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
                                <div class="form-group">
                                    <label for="tin_ein">TIN / EIN:</label>
                                    <input type="text" id="tin_ein" name="tin_ein" class="form-control">
                                </div>
                                
                               <div class="form-group">
        <label for="incorporation_country">Country of Incorporation:</label>
        <select id="incorporation_country" name="incorporation_country" class="form-control">
          <option value="" disabled selected hidden>Select Country</option>

            <?php
            $countries = get_all_countries();

            // Sort countries so that "United States" appears first
            usort($countries, function($a, $b) {
                return ($a['short_name'] === 'United States of America') ? -1 : 1;
            });

            foreach ($countries as $country) {
                $selected = false;

                // If client has a country, match it
                if (!empty($client->country) && $client->country == $country['country_id']) {
                    $selected = true;
                }

                // If no client country is set, default to United States
                if (empty($client->country) && $country['short_name'] === "United States of America") {
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
                                <div class="form-group">
                                    <label for="incorporation_date">Date of Incorporation:</label>
                                    <input type="date" id="incorporation_date" name="incorporation_date" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="registration_number">Company Registration Number: ( For International )</label>
                                    <input type="text" id="registration_number" name="registration_number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="ownership_structure">Ownership Structure:</label>
                                    <select id="ownership_structure" name="ownership_structure" class="form-control">
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="Single Owner">Single Owner</option>
                                        <option value="Partnership">Partnership</option>
                                      	<option value="Corp">Corp</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="driving_license">Driver Licence Number:</label>
                                    <input type="text" id="driving_license" name="driving_license" class="form-control">
                                </div>
                                 <div class="form-group">
                                    <label for="state_of_license">State of Driver Licence</label>
                                    <input type="text" id="state_of_license" name="state_of_license" class="form-control" >
                                </div>
                            </div>

                           
                     
                          <div class="form-section">
                                <h5>Documentation / Compliance</h5>
                                
       
   
        <div class="form-group">
            <label>Business License:</label>
            <input type="file" name="business_license" accept="application/pdf, image/png, image/gif, image/jpeg" class="form-control">
        </div>
   
        <div class="form-group">
            <label>Articles of Incorporation:</label>
            <input type="file" name="articles_incorporation" accept="application/pdf, image/png, image/gif, image/jpeg" class="form-control">
        </div>
                            
    	    <div class="form-group">
            <label>Corporate Documentation:</label>
            <input type="file" name="corporate_documentation" accept="application/pdf, image/png, image/gif, image/jpeg" class="form-control">
        </div>
       
        <div class="form-group">
            <label>Voided Check / Bank Letter:</label>
            <input type="file" name="voided_check" accept="application/pdf, image/png, image/gif, image/jpeg" class="form-control">
        </div>
    	
                 <div class="form-group">
                    <label for="internal_notes">Comments:</label>
                    <textarea id="internal_notes" name="internal_notes" rows="4" class="form-control"></textarea>
                </div>
</div>

                         
            
                            </div>
                        </div>
                 
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
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
  </script>
</body>
</html>