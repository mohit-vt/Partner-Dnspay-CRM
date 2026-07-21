<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s">
  <div class="panel-body">
    <h4>Top container is rendering ✅</h4>
    <p>From: <?= html_escape($GLOBALS['lead_kpi_from'] ?? 'not set'); ?></p>
    <p>To: <?= html_escape($GLOBALS['lead_kpi_to'] ?? 'not set'); ?></p>
  </div>
</div>
