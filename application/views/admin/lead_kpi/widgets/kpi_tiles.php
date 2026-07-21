<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$CI->load->model('lead_kpi_model');

$from = isset($lead_kpi_from) ? $lead_kpi_from : date('Y-m-d', strtotime('monday this week'));
$to   = isset($lead_kpi_to) ? $lead_kpi_to : date('Y-m-d', strtotime('sunday this week'));

$kpis = $CI->lead_kpi_model->weekly_kpis($from, $to);
?>

<div class="row">
  <div class="col-md-3">
    <div class="widget">
      <div class="panel_s">
        <div class="panel-body">
          <h3 class="tw-mb-1"><?= (int)$kpis['total_leads']; ?></h3>
          <p class="tw-mb-0">Total Leads (Status 7)</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="widget">
      <div class="panel_s">
        <div class="panel-body">
          <h3 class="tw-mb-1"><?= (int)$kpis['total_meetings']; ?></h3>
          <p class="tw-mb-0">Meetings (Status 8)</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="widget">
      <div class="panel_s">
        <div class="panel-body">
          <h3 class="tw-mb-1"><?= number_format((float)$kpis['conversion_pct'], 2); ?>%</h3>
          <p class="tw-mb-0">Lead → Meeting</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="widget">
      <div class="panel_s">
        <div class="panel-body">
          <h3 class="tw-mb-1"><?= number_format((float)$kpis['pipeline_value'], 2); ?></h3>
          <p class="tw-mb-0">Pipeline Value (8–11)</p>
        </div>
      </div>
    </div>
  </div>
</div>
