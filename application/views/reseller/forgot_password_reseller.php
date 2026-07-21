<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<style>
body.login_reseller_forgot {
  background: #fff8f0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0;
  font-family: 'Inter', 'Segoe UI', sans-serif;
}
.glass-card {
  width: 100%;
  max-width: 400px;
  padding: 2rem;
  background: rgba(255, 255, 255, 0.9);
  border-radius: 1rem;
  backdrop-filter: blur(15px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}
button.btn-primary {
  background-color: #90D5FF;
  color: white;
  border: none;
  border-radius: 0.5rem;
  transition: background 0.3s;
  font-weight: 600;
  width: 100%;
  padding: 0.75rem;
}
button.btn-primary:hover {
  background-color: #7bc8f7;
}
</style>

<body class="login_reseller_forgot">

  <div class="glass-card">
    <div class="text-center tw-mb-6">
      <?= get_dark_company_logo(); ?>
    </div>

    <h1 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-2 text-center">
      <?= _l('admin_auth_forgot_password_heading'); ?>
    </h1>
    <p class="tw-text-gray-600 tw-mb-6 text-center">
      Enter your email to reset your reseller account password
    </p>

    <?= form_open($this->uri->uri_string()); ?>
      <?= validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
      <?php $this->load->view('authentication/includes/alerts'); ?>

      <div class="tw-mb-4">
        <label class="tw-block tw-text-sm tw-font-medium"><?= _l('admin_auth_login_email'); ?></label>
        <input type="email" name="email" value="<?= isset($email) ? html_escape($email) : set_value('email'); ?>" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">
        <?= _l('admin_auth_forgot_password_button'); ?>
      </button>
    <?= form_close(); ?>

    <div class="tw-text-center tw-mt-4">
      <a href="<?= site_url('reseller'); ?>" class="tw-text-sm tw-text-[#ffb870] hover:tw-underline">
        ← Back to Login
      </a>
    </div>
  </div>

</body>
</html>
