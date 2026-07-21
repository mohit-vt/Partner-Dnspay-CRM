<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="history_send_code_table_rp" class="hide">
    <div class="row">
        <?php if(is_admin()){ ?>
        <div class="col-md-4">
            <div class="form-group">
                <label for="staff_sc"><?php echo _l('staff'); ?></label>
                <select name="staff_sc[]" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
                    <?php foreach($staff as $s){ ?>
                        <option value="<?php echo html_entity_decode($s['staffid']); ?>">
                            <?php echo get_staff_full_name($s['staffid']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php } ?>
        <div class="clearfix"></div>
    </div>

    <table class="table table-history_login scroll-responsive table-bordered table-striped">
        <thead>
            <tr>
                <th><?php echo _l('id'); ?></th>
                <th><?php echo _l('staff'); ?></th>
                <th><?php echo _l('mfa_enabled'); ?></th>
                <th><?php echo _l('secret_key'); ?></th>
                <th><?php echo _l('last_login'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($staff as $s){
                // Fetch send-code logs for this staff
                $logs = $this->db
                    ->where('staff', $s['staffid'])
                    ->order_by('time','DESC')
                    ->get(db_prefix().'mfa_send_code_logs')
                    ->result_array();

                if(empty($logs)){
                    // If no logs, still show staff MFA info
                    echo '<tr>
                            <td>'.(isset($s['staffid']) ? $s['staffid'] : '-').'</td>
                            <td><a href="'.admin_url('staff/profile/'.$s['staffid']).'">'.get_staff_full_name($s['staffid']).'</a></td>
                            <td>'.(isset($s['mfa_google_ath_enable']) && $s['mfa_google_ath_enable'] ? _l('yes') : _l('no')).'</td>
                            <td>'.(isset($s['gg_auth_secret_key']) ? $s['gg_auth_secret_key'] : '-').'</td>
                           <td>'._dt($s['last_login']).'</td>
                         </tr>';
                } else {
                    foreach($logs as $log){
                        $class = ($log['status'] == 'success') ? 'text-success' : '';
                        echo '<tr>
                                <td>'.$log['staffid'].'</td>
                                <td><a href="'.admin_url('staff/profile/'.$s['staffid']).'">'.get_staff_full_name($s['staffid']).'</a></td>
                                <td>'._l('mfa_'.$log['type']).'</td>
                                <td><span class="'.$class.'">'._l('mfa_'.$log['status']).'</span></td>
                                <td>'.$log['mess'].'</td>
                                <td>'.(isset($s['mfa_google_ath_enable']) && $s['mfa_google_ath_enable'] ? _l('yes') : _l('no')).'</td>
                                <td>'.(isset($s['gg_auth_secret_key']) ? $s['gg_auth_secret_key'] : '-').'</td>
                                <td>'._dt($log['time']).'</td>
                             </tr>';
                    }
                }
            }
            ?>
        </tbody>
    </table>
</div>
