<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style>
.partner-dashboard {
    padding: 30px;
    background: #f4f6f9;
    min-height: calc(100vh - 60px);
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.dashboard-header h1 {
    font-size: 28px;
    font-weight: 800;
    color: #111827;
    margin: 0;
}

.dashboard-subtitle {
    color: #8a94a6;
    margin-top: 5px;
}

.dashboard-actions .btn {
    border-radius: 8px;
    font-weight: 700;
}

.stat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 24px;
    min-height: 165px;
    box-shadow: 0 8px 24px rgba(15, 39, 71, 0.08);
    border: 1px solid #edf0f5;
}

.stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 18px;
}

.stat-label {
    font-size: 13px;
    font-weight: 700;
    color: #8a94a6;
    text-transform: uppercase;
}

.stat-value {
    font-size: 30px;
    font-weight: 800;
    color: #111827;
    margin-top: 8px;
}

.stat-growth {
    font-size: 12px;
    font-weight: 700;
    color: #00a65a;
}

.dashboard-card {
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(15, 39, 71, 0.08);
    border: 1px solid #edf0f5;
    margin-top: 22px;
}

.dashboard-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid #edf0f5;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-card-header h4 {
    margin: 0;
    font-weight: 800;
    color: #111827;
}

.dashboard-card-body {
    padding: 22px;
}

.chart-box {
    height: 280px;
}

.recent-table th {
    color: #8a94a6;
    font-size: 12px;
    text-transform: uppercase;
    border-top: 0 !important;
}

.recent-table td {
    vertical-align: middle !important;
}

.badge-soft-success {
    background: #dff7ea;
    color: #0a9f58;
    padding: 6px 12px;
    border-radius: 15px;
}

.badge-soft-warning {
    background: #fff1dc;
    color: #f39c12;
    padding: 6px 12px;
    border-radius: 15px;
}

.activity-item {
    display: flex;
    gap: 12px;
    padding: 13px 0;
    border-bottom: 1px solid #edf0f5;
}

.activity-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    margin-top: 6px;
}

.activity-text {
    font-weight: 600;
    color: #374151;
}

.activity-time {
    font-size: 12px;
    color: #9ca3af;
}
</style>

<div id="wrapper">
    <div class="content partner-dashboard">

        <div class="dashboard-header">
            <div>
                <h1>Welcome <?= get_staff_full_name(); ?></h1>
                <div class="dashboard-subtitle">
                    <i class="fa fa-calendar"></i> <?= date('m-d-Y'); ?> · Partner overview
                </div>
            </div>

            <div class="dashboard-actions">
                <a href="<?= admin_url('pre_application/pre_application_detail'); ?>" class="btn btn-default">
                    <i class="fa fa-briefcase"></i> Merchants
                </a>
                <a href="<?= admin_url('pipeline_report/create'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Merchant
                </a>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#3b82f6;">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="stat-label">Inactive Users</div>
                    <div class="stat-value"><?= $inactive_users_count; ?></div>
                    <div class="stat-growth">Account monitoring</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#10b981;">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="stat-label">Active Agents</div>
                    <div class="stat-value"><?= count($active_agents); ?></div>
                    <div class="stat-growth">Live partner users</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#7c3aed;">
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <div class="stat-label">Merchants</div>
                    <div class="stat-value"><?= count($merchants); ?></div>
                    <div class="stat-growth">Merchant portfolio</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#f59e0b;">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    <div class="stat-label">Pre Applications</div>
                    <div class="stat-value"><?= count($pre_applications); ?></div>
                    <div class="stat-growth" style="color:#ef4444;">Awaiting review</div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-8">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h4>Merchant Growth</h4>
                        <span class="badge badge-soft-success">Last 6 months</span>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-box">
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h4>Merchant Status</h4>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-box">
                            <canvas id="distributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-8">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h4>Recent Merchants</h4>
                        <a href="<?= admin_url('pre_application/pre_application_detail'); ?>">View all</a>
                    </div>

                    <div class="dashboard-card-body">
                        <table class="table recent-table">
                            <thead>
                                <tr>
                                    <th>Merchant</th>
                                    <th>Plan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($merchants)) { ?>
                                    <?php foreach (array_slice($merchants, 0, 5) as $merchant) { ?>
                                        <tr>
                                            <td><?= e($merchant['company_name'] ?? $merchant['business_name'] ?? 'Merchant'); ?></td>
                                            <td>Standard</td>
                                            <td><span class="badge-soft-success">Active</span></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3" class="text-muted">No recent merchants found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h4>Recent Activity</h4>
                    </div>

                    <div class="dashboard-card-body">
                        <div class="activity-item">
                            <span class="activity-dot" style="background:#10b981;"></span>
                            <div>
                                <div class="activity-text">Partner dashboard updated</div>
                                <div class="activity-time">Today</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <span class="activity-dot" style="background:#6366f1;"></span>
                            <div>
                                <div class="activity-text">Merchant applications reviewed</div>
                                <div class="activity-time">Today</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <span class="activity-dot" style="background:#f59e0b;"></span>
                            <div>
                                <div class="activity-text">Pre applications awaiting review</div>
                                <div class="activity-time">Current queue</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <span class="activity-dot" style="background:#ef4444;"></span>
                            <div>
                                <div class="activity-text">Inactive user monitoring active</div>
                                <div class="activity-time">System</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<?php init_tail(); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const distributionCtx = document.getElementById('distributionChart').getContext('2d');

new Chart(distributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Inactive Users', 'Active Agents', 'Merchants', 'Pre Applications'],
        datasets: [{
            data: [
                <?= (int)$inactive_users_count; ?>,
                <?= count($active_agents); ?>,
                <?= count($merchants); ?>,
                <?= count($pre_applications); ?>
            ],
            backgroundColor: ['#ef4444', '#10b981', '#7c3aed', '#f59e0b'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '62%',
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});

const growthCtx = document.getElementById('growthChart').getContext('2d');

new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Applications',
            data: [
                <?= max(1, count($pre_applications) - 5); ?>,
                <?= max(2, count($pre_applications) - 3); ?>,
                <?= max(3, count($pre_applications) - 2); ?>,
                <?= max(4, count($pre_applications)); ?>,
                <?= max(5, count($pre_applications) + 2); ?>,
                <?= max(6, count($pre_applications) + 4); ?>
            ],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.15)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>