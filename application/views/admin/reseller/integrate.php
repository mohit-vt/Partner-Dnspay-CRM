<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="no-margin"><b>Pre ISO/Partner Office</b></h3>
                        <hr>
   <iframe width="900" height="850" src="https://partner.dnspay.com/forms/wtl/6a7a9fd52ade7cbf078d70b26e5dc183" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
  window.addEventListener('load', function() {
    // Get the iframe by its ID or selector
    var iframe = document.querySelector('iframe'); // Adjust selector if needed
    if (iframe) {
      // Send the full URL to the iframe
      iframe.contentWindow.postMessage(window.location.href, '*');
    }
  });
</script>
    <script>
    $("#custom_form").on("submit", function(e) {
      e.preventDefault();
        $.ajax({
        url: "<?= site_url('custom_forms/submit_form'); ?>",
        type: "POST",
        data: $(this).serialize(),
        success: function(response) {
            let res = JSON.parse(response);
            alert(res.message);
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
