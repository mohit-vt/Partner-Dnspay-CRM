<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>
<style>
    .tw-pt-24 {
    padding-top: 6rem !important;
}

.tw-max-w-md {
    max-width: 28rem !important;
}
.tw-mx-auto {
    margin-left: auto !important;
    margin-right: auto !important;
}
.tw-z-20 {
    z-index: 20 !important;
}
.tw-relative {
    position: relative !important;
}

body>* {
    font-size: 14px;
}
body.authentication.set-password {
  background-color: #f5f6f8;
  font-family: "Inter", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  color: #2d2d2d;
  margin: 0;
  padding: 0;
}

.authentication-form-wrapper {
  max-width: 460px;
  margin: 0 auto;
  padding-top: 80px;
}

.company-logo {
    padding: 25px 130px;
    display: block;
}

h1 {
  font-size: 1.75rem;
  font-weight: 600;
  color: #1a1a1a;
  text-align: center;
  margin-bottom: 20px;
}

.login_admin h3 {
    font-family: inherit;
    font-weight: 500;
    line-height: 1.1;
    color: #475569;
  text-align: center;
}

.login_admin p {
  font-size: 0.95rem;
   color: #475569;
  line-height: 1.5;
  margin: 0;
  text-align: center;
}

.tw-bg-white {
  background-color: #fff !important;
  border-radius: 12px;
  border: 1px solid #ddd;
  box-shadow: 0 1px 6px rgba(0, 0, 0, 0.08);
  padding: 40px 49px !important;
}

input[type="password"] {
  width: 91.5%;
  padding: 12px 14px;
  border-radius: 6px;
  border: 1px solid #ccc;
  margin-bottom: 16px;
  font-size: 0.95rem;
  transition: all 0.2s ease-in-out;
}

input[type="password"]:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
}

button.btn.btn-primary {
  width: 100%;
  background-color: #0f172a;
  color: #fff;
  font-weight: 600;
  padding: 12px 0;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  transition: background-color 0.2s ease-in-out;
}

button.btn.btn-primary:hover {
  background-color: #1e293b;
}

/* Center content vertically on shorter screens */
@media (max-height: 700px) {
  .authentication-form-wrapper {
    padding-top: 40px;
  }
}

</style>

<body class="tw-bg-neutral-100 authentication set-password">
    <div class="tw-max-w-md tw-mx-auto tw-pt-24 authentication-form-wrapper tw-relative tw-z-20">
        <div class="company-logo text-center">
            <?= get_dark_company_logo(); ?>
        </div>

        <h1 class="tw-text-2xl tw-text-neutral-800 text-center tw-font-semibold tw-mb-5">
            <?= _l('admin_auth_set_password_heading'); ?>
        </h1>
      
      	<div class="login_admin">
          <div class="text-center">
              <h3>Your account has been disabled for security reasons.</h3>
              <p>Please reset your password to regain access.<br>
              A password reset link has been sent to your registered email.</p>
           
          </div>
      </div>

        <div
            class="tw-bg-white tw-mx-2 sm:tw-mx-6 tw-py-8 tw-px-6 sm:tw-px-8 tw-shadow-sm tw-rounded-lg tw-border tw-border-solid tw-border-neutral-600/20">
          <?= form_open($this->uri->uri_string()); ?>
            <?= validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
            <?php $this->load->view('authentication/includes/alerts'); ?>
            <?= render_input('password', 'admin_auth_set_password', '', 'password'); ?>
            <?= render_input('passwordr', 'admin_auth_set_password_repeat', '', 'password'); ?>
            <button type="submit" class="btn btn-primary btn-block tw-font-semibold tw-py-2">
                <?= _l('admin_auth_set_password_heading'); ?>
            </button>
            <?= form_close(); ?>
        </div>
    </div>
</body>

</html>
