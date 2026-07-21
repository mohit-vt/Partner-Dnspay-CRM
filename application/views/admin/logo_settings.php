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

            <?php $custom_logo = get_option('admin_logo_custom'); ?>
            <div class="mb-4">
              <h4>Current Logo</h4>
              <?php if ($custom_logo): ?>
                <img src="<?= base_url($custom_logo); ?>" class="img-thumbnail" style="max-height: 80px;">
                <br><br>
                <a href="<?= admin_url('logo_settings/reset_logo'); ?>" class="btn btn-warning">
                  <i class="fa fa-refresh"></i> Reset to Default Logo & Favicon
                </a>
              <?php else: ?>
                <p class="text-muted"><em>No custom logo uploaded.</em></p>
              <?php endif; ?>
            </div>

            <hr>

            <form method="POST" enctype="multipart/form-data" action="<?= admin_url('logo_settings/upload_logo'); ?>">
              <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

              <div class="form-group">
                <label for="logo_upload"><strong>Upload New Logo</strong> <small class="text-muted">(Also used as favicon)</small></label>
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



