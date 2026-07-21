<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
            <div class="panel-body">
                  <h4>Module Activated</h4>
                  <hr class="hr-panel-heading">
                  The module is activated and ready to use.
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<!-- <script type="text/javascript">
   appValidateForm($('#verify-form'), {
        purchase_key: 'required',
        username: 'required'
    }, manage_verify_form);

   function manage_verify_form(form) {
      var data = $(form).serialize();
      var url = form.action;
      $("#submit").prop('disabled', true).prepend('<i class="fa fa-spinner fa-pulse"></i> ');
      $.post(url, data).done(function(response) {
         var response = $.parseJSON(response);
         if(!response.status){
            alert_float("danger",response.message);
         }
         if(response.status){
            alert_float("success","Activating....");
            window.location.href = response.original_url;
         }
         $("#submit").prop('disabled', false).find('i').remove();
      });
   }
</script> -->