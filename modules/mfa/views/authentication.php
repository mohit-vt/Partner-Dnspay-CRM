<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('includes/head.php'); ?>
<body class="authentication two-factor-authentication-code">
 <div class="container">
  <div class="row">
   <div class="col-md-4 col-md-offset-4 authentication-form-wrapper">
   <div class="company-logo">
     <?php echo get_company_logo(); ?>
   </div>
   <div class="mtop40 authentication-form">
    <h1><?php echo _l('admin_two_factor_auth_heading'); ?>
      <br /><small><?php echo _l('multi_factor_authentication'); ?></small>
    </h1>
   

<?php if ($show_qr && !empty($qr_url)): ?>
    <div class="text-center mtop20">
        <p>Scan this QR code with Google Authenticator:</p>
        <img src="<?php echo $qr_url; ?>" alt="QR Code" style="max-width:200px;">
        <p class="mtop10"><strong>Or</strong> manually enter this key:</p>
        <code><?php echo html_escape($staff->gg_auth_secret_key); ?></code>
        <hr>
    </div>
<?php endif; ?>

<?php echo form_open(admin_url('mfa/mfa_auth/mfa_check_auth/'.$staff->staffid)); ?>
<?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
    <?php $this->load->view('authentication/includes/alerts'); ?>
    <?php echo render_input('code','two_factor_authentication_code'); ?>
    <div class="form-group">
      <button type="submit" class="btn btn-info btn-block"><?php echo _l('confirm'); ?></button>
    </div>
<?php echo form_close(); ?>

  </div>
</div>
</div>
</div>
</body>
</html>
