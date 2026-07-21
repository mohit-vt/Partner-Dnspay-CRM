<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo _l('deleted_record_details'); ?> - ID: <?php echo $deletion['pipeline_report_id']; ?>
                        </h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Deletion Information</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Deleted By:</th>
                                        <td><?php echo $deletion['deleted_by_name']; ?> (ID: <?php echo $deletion['deleted_by']; ?>)</td>
                                    </tr>
                                    <tr>
                                        <th>Role:</th>
                                        <td><?php echo $deletion['deleted_by_role']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Deleted At:</th>
                                        <td><?php echo _dt($deletion['deleted_at']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>IP Address:</th>
                                        <td><?php echo $deletion['ip_address']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Reason:</th>
                                        <td><?php echo nl2br(htmlspecialchars($deletion['reason'])); ?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h4>Original Record Data</h4>
                                <?php if($original_record): ?>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Company Name:</th>
                                            <td><?php echo $original_record['company_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Contact Name:</th>
                                            <td><?php echo $original_record['first_name'] . ' ' . $original_record['last_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td><?php echo $original_record['email_address']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td><?php echo $original_record['contact_phone']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td><?php echo $original_record['merchant_status']; ?></td>
                                        </tr>
                                        <!-- Add more fields as needed -->
                                    </table>
                                <?php else: ?>
                                    <div class="alert alert-warning">Original record data not available</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <a href="<?php echo admin_url('pipeline_report/deleted_records'); ?>" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Back to Deleted Records
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>