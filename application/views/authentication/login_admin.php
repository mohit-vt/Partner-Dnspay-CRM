<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>
<style>
      .modal-box {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  z-index: 50;
  max-width: 400px;
  width: 90%;
  padding: 1.5rem;
  border-radius: 1rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  display: none;
}
.modal-content {
  text-align: center;
}
.modal-close-btn {
  margin-top: 1rem;
  background-color: #90D5FF;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
}

/* Same styles as admin login */
body.login_admin {
  background: #fff8f0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0;
  font-family: 'Inter', 'Segoe UI', sans-serif;
}
body.login_admin {
  background: #e0f2ff;
  min-height: 100vh;
  margin: 0;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
}

.authentication-form-wrapper {
  width: 100%;
  max-width: 400px;
  padding: 2rem;
  background: rgba(255, 255, 255, 0.85);
  border-radius: 1rem;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(8px);
}

input.form-control {
  width: 100%;
  padding: 0.75rem;
  margin-top: 0.25rem;
  border-radius: 0.5rem;
  border: 1px solid #ccc;
  transition: 0.3s ease-in-out;
}

input.form-control:focus {
  border-color: #90D5FF;
  box-shadow: 0 0 0 2px rgba(144, 213, 255, 0.4);
  outline: none;
}

button.btn-primary {
  background-color: #90D5FF;
  color: #fff;
  border: none;
  font-weight: 600;
  border-radius: 0.5rem;
  transition: background 0.3s ease;
}

button.btn-primary:hover {
  background-color: #68c4fc;
}

@media (max-width: 480px) {
  .authentication-form-wrapper {
    margin: 1rem;
    padding: 1.5rem;
  }
}
</style>

<body class="login_admin">
  <div class="authentication-form-wrapper">
    <div class="tw-text-center tw-mb-6">
<img src="<?php echo get_user_company_logo() . '?v=20251218'; ?>"
     class="img-responsive"
     alt="AmpliTeams CRM">

    </div>

    <h1 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-2" style="text-align:center"><?= _l('admin_auth_login_heading'); ?></h1>
    <p class="tw-text-gray-600 tw-mb-6" style="text-align:center"><?= _l('welcome_back_sign_in'); ?></p>

    <?php $this->load->view('authentication/includes/alerts'); ?>
    <?= form_open($this->uri->uri_string()); ?>
    <?= validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
    <?php hooks()->do_action('after_admin_login_form_start'); ?>

    <div class="tw-mb-4">
      <label class="tw-block tw-text-sm tw-font-medium"><?= _l('admin_auth_login_email'); ?></label>
      <input type="email" name="email" class="form-control" autofocus>
    </div>

    <div class="tw-mb-4">
      <label class="tw-block tw-text-sm tw-font-medium"><?= _l('admin_auth_login_password'); ?></label>
      <input type="password" name="password" class="form-control">
    </div>

    <?php if (show_recaptcha()) { ?>
    <div class="g-recaptcha tw-my-4" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>
    <?php } ?>

    <div class="tw-flex tw-items-center tw-mb-4">
      <input type="checkbox" name="remember" id="remember" class="tw-form-checkbox tw-text-[#90D5FF]">
      <label for="remember" class="tw-ml-2 tw-text-sm"><?= _l('admin_auth_login_remember_me'); ?></label>
    </div>

    <button type="submit" class="btn btn-primary tw-w-full tw-py-2"><?= _l('admin_auth_login_button'); ?></button>

    <div class="tw-text-center tw-mt-4">
      <a href="<?= admin_url('authentication/forgot_password'); ?>" class="tw-text-sm tw-text-[#90D5FF] hover:tw-underline">
        <?= _l('admin_auth_login_fp'); ?>
      </a>
    </div>
<div class="chat-links text-center mt-2">
  <a href="https://dashboard.ampliteams.com/terms-and-conditions" target="_blank">
    Terms
  </a> |
  <a href="https://dashboard.ampliteams.com/privacy-policy" target="_blank">
    Privacy
  </a> |
  <a href="https://dashboard.ampliteams.com/help" target="_blank">
    Support
  </a>

            </div>
    <?php hooks()->do_action('before_admin_login_form_close'); ?>
                <br>
            <footer class="bg-body-tertiary text-center text-lg-start">
        
          <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        <strong>Copyright © 2011-2026 EDATA. All rights reserved.</strong>       
          </div>
       
        </footer>
    <?= form_close(); ?>
  </div>



</body>
</html>
<script>
function openModal(id) {
    document.getElementById('modalOverlay').classList.remove('tw-hidden');
    document.getElementById(id).style.display = 'block';
}

function closeModal() {
    document.getElementById('modalOverlay').classList.add('tw-hidden');
    document.getElementById('termsModal').style.display = 'none';
    document.getElementById('supportModal').style.display = 'none';
    document.getElementById('privacyModal').style.display = 'none';
}
</script>
