<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

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
body.login_reseller {
  background: #fff8f0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0;
  font-family: 'Inter', 'Segoe UI', sans-serif;
}
.authentication-form-wrapper { max-width: 1200px; width: 100%; height: 90vh; display: flex; border-radius: 1rem; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
.login-form-side { width: 40%; display: flex; justify-content: center; align-items: center; padding: 2rem; background: transparent; }
.glass-card { width: 100%; max-width: 400px; padding: 2rem; background: rgba(255, 255, 255, 0.8); border-radius: 1rem; backdrop-filter: blur(15px); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); }
.login-image-side { width: 60%; position: relative; overflow: hidden; }
.login-image-side img { width: 100%; height: 100%; object-fit: cover; }
input.form-control { width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ccc; transition: 0.3s; }
input.form-control:focus { border-color: #90D5FF; box-shadow: 0 0 0 2px rgba(255, 184, 112, 0.3); outline: none; }
button.btn-primary { background-color: #90D5FF; color: white;border: none; border-radius: 0.5rem; transition: background 0.3s; font-weight: 600; }
button.btn-primary:hover { background-color: #90D5FF; }
@media (max-width: 768px) {
  .authentication-form-wrapper { flex-direction: column; height: auto; }
  .login-form-side, .login-image-side { width: 100%; height: auto; }
  .glass-card { margin: 2rem 0; }
}
</style>
<body class="login_reseller">

<div class="authentication-form-wrapper">

    <!-- LEFT SIDE: FORM with GLASS STYLE -->
    <div class="login-form-side">
        <div class="glass-card">
          <br>
           <div class="tw-text-center tw-mb-6">
                   <img src="https://dashboard.edata24.com/uploads/company/company-default-logo.png" class="img-responsive" alt="Merchant Services, Global Payments and Platforms Provider Powered by Business Media Group" style="max-width:280px;">
            </div>

            <h1 class="tw-text-3xl tw-font-bold tw-text-gray-800 tw-mb-2">Login</h1>
            <p class="tw-text-gray-600 tw-mb-6">Access your reseller dashboard</p>

            <?php $this->load->view('authentication/includes/alerts'); ?>
           <?= form_open(site_url('reseller/authentication/login')); ?>
            <?= validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

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
                <input type="checkbox" name="remember" id="remember" class="tw-form-checkbox tw-text-[#ffb870]">
                <label for="remember" class="tw-ml-2 tw-text-sm">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary tw-w-full tw-py-2"><?= _l('admin_auth_login_button'); ?></button>

    <div class="tw-text-center tw-mt-4">
      <a href="<?= admin_url('authentication/forgot_password'); ?>" class="tw-text-sm tw-text-[#90D5FF] hover:tw-underline">
        <?= _l('admin_auth_login_fp'); ?>
      </a>
    </div>
             <div class="chat-links text-center mt-2">
              <a href="https://dashboard.edata24.com/terms-and-conditions" target="_blank">
                Terms
              </a> |
              <a href="https://dashboard.edata24.com/privacy-policy" target="_blank">
                Privacy
              </a> |
              <a href="https://dashboard.edata24.com/help" target="_blank">
                Support
              </a>

            </div>
            <br>
            <footer class="bg-body-tertiary text-center text-lg-start">
        
          <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        <strong>Copyright © 2011-2025 BMG. All rights reserved.</strong>       
          </div>
       
        </footer>
            <?= form_close(); ?>
        </div>
    </div>

    <!-- RIGHT SIDE: FULLSCREEN BACKGROUND with OVERLAY TEXT -->
    <div class="login-image-side tw-relative">
        <img src="<?= base_url('uploads/company/login_page.jpg'); ?>" alt="Login Visual" class="tw-w-full tw-h-full tw-object-cover tw-absolute tw-inset-0 tw-blur-sm">
 
    </div>

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
