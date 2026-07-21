<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php try { ?>
<?php $this->load->view('admin/lead_kpi/widgets/stage_distribution'); ?>
<?php } catch (Throwable $e) { ?>
  <div class="alert alert-danger">KPI Tiles error: <?= html_escape($e->getMessage()); ?></div>
<?php } ?>
