<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            Agent Application – <?= html_escape($app->agent_id); ?>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- LEFT COLUMN -->
                <div class="col-md-6">

                    <div class="form-section">
                      
                        <div class="mb-2">
                            <strong>Business nature of your company:</strong><br>
                            <?= html_escape($app->business_nature); ?>
                        </div>
                      
                        <div class="mb-2">
                            <strong>Years in the business:</strong><br>
                            <?= html_escape($app->years_in_business); ?>
                        </div>
                      
                        <div class="mb-2">
                            <strong>Your current processing relationships:</strong><br>
                            <?= html_escape($app->process_relationship); ?>
                        </div>
                      
                      	 <div class="mb-2">
                            <strong>Do you have sales reps in your office:</strong><br>
                            <?= html_escape($app->sales_reps); ?>
                        </div>
                      
                      <?php
                      $primary_interests = [];
                      if (!empty($app->primary_interest)) {
                          $decoded = json_decode($app->primary_interest, true);
                          if (is_array($decoded)) {
                              $primary_interests = $decoded;
                          }
                      }
                      ?>

                                              <div class="mb-2">
                          <strong>Primary Interest:</strong><br>
                          <?php if (!empty($primary_interests)): ?>
                              <?php foreach ($primary_interests as $pi): ?>
                                  <span class="label label-info" style="margin-right:3px;">
                                      <?= html_escape($pi); ?>
                                  </span>
                              <?php endforeach; ?>
                          <?php else: ?>
                              <em>Not specified</em>
                          <?php endif; ?>
                      </div>


                        <div class="mb-2">
                            <strong>Lead Source:</strong><br>
                            <?= html_escape($app->lead_source); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Region:</strong><br>
                            <?= html_escape($app->region); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Application Date:</strong><br>
                            <?= html_escape($app->application_date); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Application IP:</strong><br>
                            <?= html_escape($app->application_ip); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Company Name:</strong><br>
                            <?= html_escape($app->company_name); ?>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5>Representative (KYC)</h5>

                        <div class="mb-2">
                            <strong>First Name:</strong><br>
                            <?= html_escape($app->first_name); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Last Name:</strong><br>
                            <?= html_escape($app->last_name); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Role / Relationship:</strong><br>
                            <?= html_escape($app->role_relationship); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Mobile Number:</strong><br>
                            <?= html_escape($app->contact_phone); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Email Address:</strong><br>
                            <?= html_escape($app->email_address); ?>
                        </div>

                        <div class="mb-2">
                            <strong>SSN / Passport ID:</strong><br>
                            <?= html_escape($app->id_number); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Principal Address:</strong><br>
                            <?= html_escape($app->principal_address); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Date of Birth:</strong><br>
                            <?= html_escape($app->dob); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Home Address:</strong><br>
                            <?= html_escape($app->home_address); ?>
                        </div>

                        <div class="mb-2">
                            <strong>City / State / ZIP:</strong><br>
                            <?= html_escape($app->kyc_city); ?> /
                            <?= html_escape($app->kyc_state); ?> /
                            <?= html_escape($app->kyc_zip); ?>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5>Banking / Payout Info</h5>

                        <div class="mb-2">
                            <strong>Bank Name:</strong><br>
                            <?= html_escape($app->bank_name); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Bank Account #:</strong><br>
                            <?= html_escape($app->bank_account); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Routing / SWIFT / IBAN:</strong><br>
                            <?= html_escape($app->routing_swift_iban); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Account Holder:</strong><br>
                            <?= html_escape($app->account_holder); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Account Type:</strong><br>
                            <?= html_escape($app->account_type); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Bank Location:</strong><br>
                            <?= html_escape($app->bank_location); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Bank Address:</strong><br>
                            <?= html_escape($app->bank_address); ?>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="col-md-6">

                    <div class="form-section">
                        <h5>Business (KYB)</h5>

                        <div class="mb-2">
                            <strong>Legal Business Name:</strong><br>
                            <?= html_escape($app->legal_name); ?>
                        </div>

                        <div class="mb-2">
                            <strong>DBA:</strong><br>
                            <?= html_escape($app->dba); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Business Address:</strong><br>
                            <?= html_escape($app->business_address); ?>
                        </div>

                        <div class="mb-2">
                            <strong>City / State / ZIP:</strong><br>
                            <?= html_escape($app->business_city); ?> /
                            <?= html_escape($app->business_state); ?> /
                            <?= html_escape($app->business_zip); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Business Phone:</strong><br>
                            <?= html_escape($app->business_phone); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Website:</strong><br>
                            <?= html_escape($app->website_url); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Corporate Type:</strong><br>
                            <?= html_escape($app->business_type); ?>
                        </div>

                        <div class="mb-2">
                            <strong>TIN / EIN:</strong><br>
                            <?= html_escape($app->tin_ein); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Date of Incorporation:</strong><br>
                            <?= html_escape($app->incorporation_date); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Company Registration Number:</strong><br>
                            <?= html_escape($app->registration_number); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Ownership Structure:</strong><br>
                            <?= html_escape($app->ownership_structure); ?>
                        </div>

                        <div class="mb-2">
                            <strong>Driver License #:</strong><br>
                            <?= html_escape($app->driving_license); ?>
                        </div>

                        <div class="mb-2">
                            <strong>State of License:</strong><br>
                            <?= html_escape($app->state_of_license); ?>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5>Documentation</h5>

                        <?php if ($app->business_license): ?>
                            <div class="mb-2">
                                <strong>Business License:</strong><br>
                                <a href="<?= base_url('uploads/agent_docs/'.$app->business_license); ?>" target="_blank">
                                    View File
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($app->articles_incorporation): ?>
                            <div class="mb-2">
                                <strong>Articles of Incorporation:</strong><br>
                                <a href="<?= base_url('uploads/agent_docs/'.$app->articles_incorporation); ?>" target="_blank">
                                    View File
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($app->corporate_documentation): ?>
                            <div class="mb-2">
                                <strong>Corporate Documentation:</strong><br>
                                <a href="<?= base_url('uploads/agent_docs/'.$app->corporate_documentation); ?>" target="_blank">
                                    View File
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($app->voided_check): ?>
                            <div class="mb-2">
                                <strong>Voided Check / Bank Letter:</strong><br>
                                <a href="<?= base_url('uploads/agent_docs/'.$app->voided_check); ?>" target="_blank">
                                    View File
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-section">
                        <h5>Internal Notes</h5>
                        <p><?= nl2br(html_escape($app->internal_notes)); ?></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
