<?php defined('BASEPATH') or exit('No direct script access allowed'); 
?>
<?php 
  $file_header = array();
$file_header[] = _l('Time');
$file_header[] = _l('Type');
$file_header[] = _l('Value');

$file_header[] = _l('gender');
$file_header[] = _l('age');
$file_header[] = _l('language');
$file_header[] = _l('country');
$file_header[] = _l('reaction');
$file_header[] = _l('post_type');
$file_header[] = _l('stories_performance');
$file_header[] = _l('engagement');
$file_header[] = _l('subscriber');
$file_header[] = _l('device');
$file_header[] = _l('status');
 ?>

<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div id ="dowload_file_sample">
            
            
            </div>

            <?php if(!isset($simulate)) { ?>
            <ul>
              <li class="text-danger">1. <?php echo _l('file_xlsx_banking'); ?></li>
              <li class="text-danger">3. <?php echo _l('file_xlsx_format'); ?></li>
            </ul>
            <div class="table-responsive no-dt">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <?php
                      $total_fields = 0;
                      
                      for($i=0;$i<count($file_header);$i++){
                          ?>
                          <th class="bold">
                          <?php if($i == 1 || $i == 2 || $i == 0){ ?>
                            <span class="text-danger">*</span> 
                          <?php } ?>
                            <?php echo new_html_entity_decode($file_header[$i]) ?> </th>
                          <?php 
                          ?>
                          
                          <?php

                          $total_fields++;
                      }

                    ?>

                    </tr>
                  </thead>
                  <tbody>
                    <?php for($i = 0; $i<1;$i++){
                      echo '<tr>';
                      for($x = 0; $x<count($file_header);$x++){
                        echo '<td>- </td>';
                      }
                      echo '</tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <hr>

              <?php } ?>
            
            <div class="row">
              <div class="col-md-4">
               <?php echo form_open_multipart(admin_url('social_analytic/import_xlsx_banking'),array('id'=>'import_form')) ;?>
                    <?php echo form_hidden('leads_import','true'); ?>
                    <?php echo render_select('account_id',$accounts,array('id','name'),'account', $account_id); ?>
                    <?php echo render_input('file_csv','choose_excel_file','','file'); ?> 

                    <div class="form-group">
                      <button id="uploadfile" type="button" class="btn btn-info import" onclick="return uploadfilecsv();" ><?php echo _l('import'); ?></button>
                    </div>
                  <?php echo form_close(); ?>
              </div>
              <div class="col-md-8">
                <div class="form-group" id="file_upload_response">
                  
                </div>
                
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- box loading -->
<div id="box-loading"></div>
<?php init_tail(); ?>

<?php require 'modules/social_analytic/assets/js/analytics/import_analytics_js.php';?>
</body>
</html>