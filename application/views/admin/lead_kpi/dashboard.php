<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <?php $this->load->view('admin/includes/alerts'); ?>

      <!-- Page title (Perfex style) -->
      <div class="col-md-12">
        <h4 class="tw-mb-3"><?= html_escape($title ?? 'Weekly Lead KPI'); ?></h4>
      </div>

      <!-- Filter bar -->
      <div class="col-md-12 mtop10">
        <div class="panel_s">
          <div class="panel-body">
            <form method="get" class="row">
              <div class="col-md-3">
                <label>From</label>
                <input type="date" class="form-control" name="from" value="<?= html_escape($from); ?>">
              </div>

              <div class="col-md-3">
                <label>To</label>
                <input type="date" class="form-control" name="to" value="<?= html_escape($to); ?>">
              </div>

              <div class="col-md-6" style="padding-top:25px;">
                <button class="btn btn-primary">Apply</button>

                <a class="btn btn-default" href="<?= admin_url('lead_kpi'); ?>">This Week</a>

                <a class="btn btn-default"
                   href="<?= admin_url('lead_kpi?from='.date('Y-m-d',strtotime('monday last week')).'&to='.date('Y-m-d',strtotime('sunday last week'))); ?>">
                  Last Week
                </a>
              </div>
            </form>

            <!-- Optional debug line (SAFE placement) -->
            <?php if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') : ?>
              <hr>
              <p class="text-muted tw-mb-0">
                Rendering KPI dashboard for: <strong><?= html_escape($from); ?></strong> → <strong><?= html_escape($to); ?></strong>
              </p>
            <?php endif; ?>

          </div>
        </div>
      </div>

      <!-- KPI content (containers) -->
      <div class="col-md-6">
        <?php $this->load->view('admin/lead_kpi/containers/middle_left_6'); ?>
      </div>
      <div class="col-md-6">
        <?php $this->load->view('admin/lead_kpi/containers/middle_right_6'); ?>
      </div>

      <div class="col-md-12">
        <?php $this->load->view('admin/lead_kpi/containers/left_8'); ?>
      </div>
    

      <div class="clearfix"></div>

      <div class="col-md-4">
        <?php $this->load->view('admin/lead_kpi/containers/bottom_left_4'); ?>
      </div>
      <div class="col-md-4">
        <?php $this->load->view('admin/lead_kpi/containers/bottom_middle_4'); ?>
      </div>
      <div class="col-md-4">
        <?php $this->load->view('admin/lead_kpi/containers/bottom_right_4'); ?>
      </div>

    </div>
  </div>
</div>

<?php init_tail(); ?>
</body>
</html>
