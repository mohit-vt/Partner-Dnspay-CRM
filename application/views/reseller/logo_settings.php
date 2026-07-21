<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel_s">
          <div class="panel-heading">
            <h3 class="panel-title">
              <strong><i class="fa fa-image"></i> Logo Settings</strong>
            </h3>
          </div>
          <div class="panel-body">

            <div class="mb-4">
              <h4>Current Logo</h4>
              <img src="<?= base_url($logo_to_show); ?>" class="img-thumbnail" style="max-height: 80px;">
              <br><br>

              <?php if ($custom_logo): ?>
                <p class="text-success"><em>You are using a custom logo.</em></p>
                <a href="<?= base_url('reseller/logo_settings/reset_logo'); ?>" class="btn btn-warning">
                  <i class="fa fa-refresh"></i> Reset to Default Logo
                </a>
              <?php else: ?>
                <p class="text-muted"><em>You are using the system default logo.</em></p>
              <?php endif; ?>
            </div>

            <hr>

            <form method="POST" enctype="multipart/form-data" action="<?= base_url('reseller/logo_settings/upload_logo'); ?>">
              <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

              <div class="form-group">
                <label for="logo_upload"><strong>Upload New Logo</strong> 
                  <small class="text-muted">(Only changes logo for your account)</small>
                </label>
                <input type="file" name="logo_upload" class="form-control" accept="image/*" required>
              </div>

              <button type="submit" class="btn btn-success">
                <i class="fa fa-upload"></i> Upload
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

<?php if ($this->session->flashdata('logo_reset_trigger_reload') 
         || $this->session->flashdata('logo_set_trigger_reload')): ?>
<script>
  // Force reload to refresh favicon/logo
  window.location.reload(true);
</script>
<?php endif; ?>
