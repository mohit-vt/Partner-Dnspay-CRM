<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.status-dropdown {
    color: white;
    font-weight: bold;
    border: none;
    padding: 5px 5px;
    border-radius: 4px;
    width: 100px;
}

.status-dropdown.pending {
    background-color: #ffc107; /* yellow */
}

.status-dropdown.onhold {
    background-color: #ffc107; /* yellow */
}

.status-dropdown.approved {
    background-color: #28a745; /* green */
}

.status-dropdown.declined {
    background-color: #dc3545; /* red */
}
</style>

<?php
// Computed ONCE for the whole page — avoids repeating this query in five places.
// Roles in $admin_type_roles see the full admin view (Agent Name column, Login button, etc.).
// Everyone else is treated as an agent-type user for display/security purposes.
$rolevalue = $this->roles_model->get($current_user->role);
$admin_type_roles = ['platform administrator', 'office manager', 'management'];
$is_admin_type = isset($rolevalue->name) && in_array(strtolower($rolevalue->name), $admin_type_roles);
?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <h4 class="tw-my-0 tw-font-bold tw-text-xl">ISO/Reseller Applications</h4>
            <hr>

                <?php if (!empty($self_agent)): ?>
                <!-- Self-info card: the logged-in agent's own identity, kept separate from
                     the table below (which only lists records they actually submitted). -->
                <div class="panel_s" style="margin-bottom: 15px;">
                    <div class="panel-body" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;">
                        <div>
                            <strong>Your Agent ID:</strong> <?= htmlspecialchars($self_agent['agent_id'] ?? '\u2014') ?>
                            &nbsp;|&nbsp;
                            <strong>Status:</strong> <?= htmlspecialchars($self_agent['user_status'] ?? 'Pending') ?>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-info view-agent-app" data-staffid="<?= $self_agent['staffid'] ?>">
                                View My Application
                            </button>
                            <button class="btn btn-sm btn-primary view-overview" data-id="<?= $self_agent['staffid'] ?>">
                                View Overview
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="panel_s">
                    <div class="panel-body">

                    <hr>
                       <table class="table dt-table table-striped" data-order-col="3" data-order-type="desc">
                        <thead>
                            <tr>
                                <th class="text-left">Agent ID</th>

                                <?php if ($is_admin_type): ?>
                                <th class="text-left">Agent Name</th>
                                <?php endif; ?>

                                <th class="text-left">Email ID</th>
                                <th class="text-left">Created At</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">View</th>

                                <?php if (is_admin()): ?>
                                <th class="text-left">Login</th>
                                <?php endif; ?>

                                <th class="text-left">Overview</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reseller_lists as $reseller_list): ?>
                            <tr>
                                <td class="text-left"><?= $reseller_list['agent_id'] ?? ''; ?></td>

                                <?php if ($is_admin_type): ?>
                                <td>
                                    <?= isset($agent_map[$reseller_list['assigned_to_agent']])
                                        ? htmlspecialchars($agent_map[$reseller_list['assigned_to_agent']])
                                        : ''; ?>
                                </td>
                                <?php endif; ?>

                                <td class="text-left"><?php echo $reseller_list['email']; ?></td>
                                <td class="text-left"><?php echo $reseller_list['created_at']; ?></td>
                                <td>
                                    <select style="height: 30px !important"
                                            class="status-dropdown form-control"
                                            data-id="<?= $reseller_list['reseller_id'] ?>">
                                        <option value="Approved" <?= $reseller_list['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="Pending"  <?= $reseller_list['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="onhold"   <?= $reseller_list['status'] == 'onhold' ? 'selected' : '' ?>>On Hold</option>
                                        <option value="Declined" <?= $reseller_list['status'] == 'Declined' ? 'selected' : '' ?>>Declined</option>
                                    </select>
                                </td>

                                <?php if (!empty($reseller_list['is_agent'])): ?>
                                <td class="text-left">
                                    <button
                                        class="btn btn-sm btn-info view-agent-app"
                                        data-staffid="<?= $reseller_list['staffid']; ?>">
                                        View Application
                                    </button>
                                </td>
                                <?php else: ?>
                                <td></td>
                                <?php endif; ?>

                                <?php if (is_admin()): ?>
                                    <?php if (!empty($reseller_list['is_agent'])): ?>
                                    <td class="text-left">
                                       <a style="color:white"
                                          href="<?= admin_url('reseller/login_as/' . $reseller_list['staffid']); ?>"
                                          class="btn btn-sm btn-success"
                                          target="_blank"
                                          onclick="window.open(this.href, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes'); return false;">
                                          Login
                                       </a>
                                    </td>
                                    <?php else: ?>
                                    <td></td>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <td>
                                    <button class="btn btn-sm btn-primary view-overview" data-id="<?= $reseller_list['reseller_id']; ?>">View</button>
                                    <button class="btn btn-sm btn-success add-overview" data-id="<?= $reseller_list['reseller_id']; ?>">Add</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                       </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Agent Application View Modal -->
<div class="modal fade" id="viewAgentAppModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agent Application</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="agentApplicationBody">
        <p class="text-center">Loading...</p>
      </div>
    </div>
  </div>
</div>

<!-- Agent Create Modal -->
<div class="modal fade" id="addOverviewModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Agent Overview</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="overviewAgentId">
        <textarea id="overviewText" class="form-control" rows="4"></textarea>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveOverview">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Agent View Modal -->
<div class="modal fade" id="viewOverviewModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agent Overview</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="overviewList"></div>
    </div>
  </div>
</div>

<!-- Delete Reason Modal -->
<div class="modal fade" id="deletePartnerModal" tabindex="-1" role="dialog" aria-labelledby="deletePartnerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePartnerModalLabel">Reason for Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deletePartnerId">
        <div class="form-group">
          <label for="deleteReason">Please enter the reason:</label>
          <textarea id="deleteReason" class="form-control" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeletePartner">Delete</button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>

<script>
$(document).on('click', '.view-agent-app', function () {
    var staffid = $(this).data('staffid');

    var csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash(); ?>";

    $('#agentApplicationBody').html('<p class="text-center">Loading...</p>');
    $('#viewAgentAppModal').modal('show');

    $.ajax({
        url: "<?= admin_url('reseller/view_agent_application'); ?>",
        type: "POST",
        data: {
            staffid: staffid,
            [csrfName]: csrfHash
        },
        success: function (html) {
            $('#agentApplicationBody').html(html);
        },
        error: function (xhr) {
            $('#agentApplicationBody').html(
                '<p class="text-danger">Failed to load application.</p>'
            );
            console.error(xhr.responseText);
        }
    });
});

$(document).on('click', '.add-overview', function () {
    $('#overviewAgentId').val($(this).data('id'));
    $('#overviewText').val('');
    $('#addOverviewModal').modal('show');
});

$('#saveOverview').click(function () {
    var agent_id = $('#overviewAgentId').val();
    var overview = $('#overviewText').val();

    if (!overview) return alert('Please enter overview');

    $.post("<?= admin_url('reseller/add_agent_overview'); ?>",
        { agent_id: agent_id, overview: overview },
        function (response) {
            if (response.success) {
                $('#addOverviewModal').modal('hide');
            } else alert(response.message);
        }, 'json'
    );
});

$(document).on('click', '.view-overview', function () {
    var agent_id = $(this).data('id');

    var csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
    var csrfHash = "<?= $this->security->get_csrf_hash(); ?>";

    $.ajax({
        url: "<?= admin_url('reseller/get_agent_overviews'); ?>",
        type: "POST",
        dataType: "json",
        data: {
            agent_id: agent_id,
            [csrfName]: csrfHash
        },
        success: function (data) {
            var html = '';
            if (data.length === 0) {
                html = '<p>No overviews yet.</p>';
            } else {
                data.forEach(function (o) {
                    html += '<p><strong>' + o.firstname + ' ' + o.lastname + ' (' + o.created_at + '):</strong><br>' + o.overview + '</p><hr>';
                });
            }
            $('#overviewList').html(html);
            $('#viewOverviewModal').modal('show');
        },
        error: function (xhr) {
            alert('Failed to fetch overviews.');
            console.error(xhr.responseText);
        }
    });
});

$(document).on('click', '.delete-partner', function () {
    var resellerId = $(this).data('id');

    $('#deletePartnerId').val(resellerId);
    $('#deleteReason').val('');
    $('#deletePartnerModal').modal('show');
});

$('#confirmDeletePartner').click(function () {
    var resellerId = $('#deletePartnerId').val();
    var reason = $('#deleteReason').val();

    if (!reason) {
        alert_float('danger', 'Please provide a reason for deletion');
        return;
    }

    $.ajax({
        url: admin_url + 'reseller/delete_partner',
        type: 'POST',
        data: {
            id: resellerId,
            reason: reason
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                alert_float('success', response.message);
                window.location.reload();
            } else {
                alert_float('danger', response.message);
            }
            $('#deletePartnerModal').modal('hide');
        },
        error: function (xhr) {
            var errorMsg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'An error occurred while deleting the reseller';
            alert_float('danger', errorMsg);
            $('#deletePartnerModal').modal('hide');
        }
    });
});

$(document).ready(function () {
    $('#select_all').on('change', function () {
        $('.client_checkbox').prop('checked', this.checked);
    });

    $(document).on('change', '.client_checkbox', function () {
        $('#select_all').prop('checked', $('.client_checkbox:checked').length === $('.client_checkbox').length);
    });

    $('#delete_selected').on('click', function () {
        var selectedIds = $('.client_checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert('Please select at least one record.');
            return;
        }

        if (confirm('Are you sure you want to delete the selected records?')) {
            var csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
            var csrfHash = "<?= $this->security->get_csrf_hash(); ?>";

            $.ajax({
                url: '<?= admin_url('reseller/delete_multiple'); ?>',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function (response) {
                    alert(response.message);
                    if (response.success) location.reload();
                },
                error: function (xhr) {
                    alert('Unexpected error: ' + xhr.responseText);
                }
            });
        }
    });

    function updateStatusColor(selectElement, status) {
        selectElement.removeClass('pending approved declined onhold');
        selectElement.addClass(status.toLowerCase());
    }

    $('.status-dropdown').each(function () {
        updateStatusColor($(this), $(this).val());
    }).on('change', function () {
        const $this = $(this);
        const id = $this.data('id');
        const status = $this.val();
        updateStatusColor($this, status);

        $.ajax({
            type: "POST",
            url: "<?= admin_url('reseller/update_resellers_status'); ?>",
            data: { id: id, status: status },
            success: function (data) {
                console.log('Status update response:', data);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', error);
            }
        });
    });

    <?php if (!$is_admin_type): ?>
        // Devtools/right-click blocking applies to agent-type users, not admin/management roles.
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
        });
        document.onkeydown = function (e) {
            if (
                e.keyCode === 123 || // F12
                (e.ctrlKey && e.shiftKey && [73, 74].includes(e.keyCode)) || // Ctrl+Shift+I/J
                (e.ctrlKey && e.keyCode === 85) // Ctrl+U
            ) return false;
        };
    <?php endif; ?>
});
</script>
