<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="no-margin"><b>Application Details</b></h3>
                        <hr>
      
                <table class="table table-bordered">
                   
                    <tr><th>Name</th><td><?= html_escape($lead['name']); ?></td></tr>
                    <tr><th>Email</th><td><?= html_escape($lead['email']); ?></td></tr>
                    <tr><th>Phone</th><td><?= html_escape($lead['phonenumber']); ?></td></tr>
                    <tr><th>Remote IP</th><td><?= html_escape($lead['remote_ip']); ?></td></tr>
                    <tr><th>User Agent</th><td><?= html_escape($lead['user_agent']); ?></td></tr>
                    <tr><th>Page URL</th><td><?= html_escape($lead['page_url']); ?></td></tr>
                    <tr><th>Date Added</th><td><?= _dt($lead['dateadded']); ?></td></tr>
                    <?php foreach ($custom_fields as $field): ?>
                        <tr>
                            <th><?= html_escape($field['name']); ?></th>
                            <td><?= html_escape($field['value']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
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
