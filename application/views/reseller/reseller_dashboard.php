<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="d-flex flex-column min-vh-100">
    <div class="content flex-grow-1">
        <div class="container my-4" style="width: 1000px !important;">

<!-- Header Row -->
<div class="mb-4">

    <!-- centered title -->
    <h4 class="mb-3 text-center">
        Affiliate Pre-Application Link
    </h4>
    <?php 
        $reseller_id = get_staff()->agent_id; 
        $affiliate_link = site_url('pre_application/create?rid=' . $reseller_id);
    ?>

    <!-- hard-center the input group -->
    <div class="d-flex justify-content-center">
        <div class="input-group" style="max-width:450px; width:100%; margin:10px auto;">
           <input type="text"
       id="affiliateLink"
       class="form-control text-center"
       value="<?= e($affiliate_link); ?>"
       readonly>

<button type="button"
        id="copyAffiliateBtn"
        class="btn btn-primary"
        style="max-width:450px; width:100%; margin:5px auto;">
    <i class="fa fa-copy"></i> Copy Link
</button>
          <script>
(function () {
    var btn = document.getElementById('copyAffiliateBtn');

    if (!btn) return;

    btn.onclick = function () {
        var input = document.getElementById('affiliateLink');

        if (!input) {
            alert('Affiliate link input not found.');
            return false;
        }

        var link = input.value;

        var temp = document.createElement('textarea');
        temp.value = link;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);

        alert('Copied: ' + link);
        return false;
    };
})();
</script>
        </div>
    </div>

</div>


      <!-- Stat Cards Row -->
<div class="row text-center justify-content-center g-3 mb-3">
    <?php
    $cards = [
        ['label' => 'Inactive Users',               'count' => $inactive_users_count,   'color' => '#FF3131'],
        ['label' => 'Active Agents',                'count' => $active_agents_count,    'color' => '#22DD22'],
        ['label' => 'Merchant Applications', 'count' => $pipeline_report_count,  'color' => '#0D98BA'],
        ['label' => 'Sub-Agents',                   'count' => $sub_agents_count,       'color' => '#FDDA0D'],
    ];

    foreach ($cards as $card) :
        $is_custom_color = str_starts_with($card['color'], '#');
    ?>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm h-100 border-0"
                 style="<?= $is_custom_color ? "background-color: {$card['color']};padding:2px; color:#fff;" : "" ?>">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h6 class="mb-1 fw-semibold text-uppercase"><?= $card['label'] ?></h6>
                    <p class="display-6 fw-bold mb-0"><?= $card['count'] ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
          <br>
          <!-- Charts -->
            <div class="row justify-content-center">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header text-center fw-semibold">Distribution Overview</div>
                        <div class="card-body">
                            <canvas id="distributionChart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header text-center fw-semibold">Growth (Last 30 Days)</div>
                        <div class="card-body">
                            <canvas id="growthChart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
          <br>
  <div class="row justify-content-center">
    <div class="col-12">
        <div class="card shadow-sm h-100">
            <div class="card-header text-center fw-semibold">Pipeline Status Funnel</div>
            <div class="card-body">
                <canvas id="statusFunnelChart" style="max-height: 320px;"></canvas>
            </div>
        </div>
    </div>
</div>
          
          <br>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card shadow-sm h-100">
            <div class="card-header text-center fw-semibold">Recent Pipeline Applications</div>
            <div class="card-body">
                <?php if (!empty($recent_pipeline)): ?>
                    <div class="list-group">
                        <?php foreach($recent_pipeline as $p): ?>
                            <a href="<?= site_url('reseller/pipeline_report/view/'.$p['id']) ?>"
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <strong><?= htmlspecialchars($p['company_name'] ?? $p['dba'] ?? 'Unnamed') ?></strong>
                                    <small class="text-muted"><?= date('d M Y', strtotime($p['created_at'])) ?></small>
                                </div>
                                <small>Status: <?= htmlspecialchars($p['merchant_status'] ?? 'Pending') ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">No recent applications found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
 </div>
    </div>

    <!-- Single Footer -->
    <footer class="bg-body-tertiary text-center mt-auto py-3 border-top">
        <div class="container">
            © 2025, Designed and Developed by 
            <a href="https://edatapay.com/" class="fw-semibold text-decoration-none">eData Development Group</a>
        </div>
    </footer>
</div>
<?php init_tail(); ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart JS
const distributionCtx = document.getElementById('distributionChart').getContext('2d');
new Chart(distributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Inactive Users', 'Active Agents', 'Merchant Applications', 'Sub-Agents'],
        datasets: [{
            label: 'Distribution',
            data: [
                <?= $inactive_users_count ?>,
                <?= $active_agents_count ?>,
                <?= $pipeline_report_count ?>,
                <?= $sub_agents_count ?>
            ],
            backgroundColor: ['#FF3131', '#22DD22', '#0D98BA', '#FDDA0D'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});


const growthCtx = document.getElementById('growthChart').getContext('2d');
new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($pipeline_growth_30['labels']) ?>,
        datasets: [{
            label: 'New Pipeline Applications (Last 30 Days)',
            data: <?= json_encode($pipeline_growth_30['data']) ?>,
            tension: 0.35,
            fill: true,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
    }
});
  
const statusCtx = document.getElementById('statusFunnelChart').getContext('2d');
new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($pipeline_status_stats)) ?>,
        datasets: [{
            label: 'Applications by Status',
            data: <?= json_encode(array_values($pipeline_status_stats)) ?>,
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { beginAtZero: true }
        }
    }
});
</script>
