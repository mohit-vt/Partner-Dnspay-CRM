<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$CI->load->model('lead_kpi_model');

$from = isset($lead_kpi_from) ? $lead_kpi_from : date('Y-m-d', strtotime('monday this week'));
$to   = isset($lead_kpi_to) ? $lead_kpi_to : date('Y-m-d', strtotime('sunday this week'));


$kpis = $CI->lead_kpi_model->weekly_kpis($from, $to);
?>

<div class="panel_s">
  <div class="panel-body">
    <h4 class="tw-mb-3">Data Quality / Compliance</h4>

    <ul class="tw-pl-4 tw-mb-0">
      <li>Unassigned leads: <strong><?= (int)$kpis['unassigned']; ?></strong></li>

      <li>
        Missing Next Step:
        <strong>
          <?php if (is_null($kpis['missing_next_step'])) : ?>
            Not Configured
          <?php else : ?>
            <?= (int)$kpis['missing_next_step']; ?>
          <?php endif; ?>
        </strong>
      </li>

      <li>Missing Value (lead_value = 0): <strong><?= (int)$kpis['missing_value']; ?></strong></li>
    </ul>

    <?php if (is_null($kpis['missing_next_step'])) : ?>
      <hr>
      <p class="text-muted tw-mb-0">
        Next Step custom field not found. Create a Lead custom field named <strong>Next Step</strong> to enable this KPI.
      </p>
    <?php endif; ?>
  </div>
</div>
