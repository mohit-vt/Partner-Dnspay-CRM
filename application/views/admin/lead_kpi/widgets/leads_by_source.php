<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$CI->load->model('lead_kpi_model');

$from = isset($lead_kpi_from) ? $lead_kpi_from : date('Y-m-d', strtotime('monday this week'));
$to   = isset($lead_kpi_to) ? $lead_kpi_to : date('Y-m-d', strtotime('sunday this week'));


$rows = $CI->lead_kpi_model->leads_by_source($from, $to);
?>

<div class="panel_s">
  <div class="panel-body">
    <h4 class="tw-mb-3">Leads by Source</h4>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Source</th>
          <th class="text-right">Leads</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)) : ?>
          <tr><td colspan="2" class="text-muted">No data for selected range.</td></tr>
        <?php else : ?>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td><?= html_escape($r['source_name'] ?? 'Unknown'); ?></td>
              <td class="text-right"><?= (int)$r['total']; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

  </div>
</div>
