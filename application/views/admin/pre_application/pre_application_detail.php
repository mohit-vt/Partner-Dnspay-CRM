<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>

.modal-content {
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: auto;
}

.modal-content h4 {
  font-weight: 700;
  color: #212529;
}

.modal-content p {
  color: #555;
}

.flex-container {
  display: flex;
  flex-wrap: nowrap;
  background-color: white; /* ✅ White background */
  gap: 10px;
}

#notesHistory {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 10px;
}

#notesHistory::-webkit-scrollbar {
    width: 6px;
}

#notesHistory::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 10px;
}

.flex-container > div {
  background-color: #f1f1f1;
  width: 800px;
  margin: 10px;
  text-align: center;
  line-height: 75px;
  font-size: 30px;
  display: 'center',
}    
.card {
    border-radius: 15px;
    transition: 0.3s;
    padding: 20px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

.card:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    border-width: 2px;
    border-color: #0ca678;
}

.col-md-12.card-wrapper {
    margin-bottom: 20px;
}

.card-footer {
    background-color: white;

}

.card-footer .btn {
    min-width: 70px;
    font-size: 0.8rem;
    padding: 6px 12px;
}

.card-title {
    font-weight: 600;
    font-size: 1.2rem;
}

.card-footer .btn, .card-footer select, .card-footer button {
    min-width: 100px;
    text-align: center;
}


</style>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <h4 class="tw-my-0 tw-font-bold tw-text-xl">Merchants</h4>
            <hr>
                <div class="panel_s">
                    <div class="panel-body">

<!-- Cards -->
<div class="row">
    <?php foreach ($applications as $report): ?>
        <div class="col-md-12 card-wrapper">
            <div class="card mb-3 shadow-sm border-start border-5" style="border-color: #0ca678;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div style="float:right"> 
                            <h5 class="mb-0"><strong>Created on</strong><b> <?= htmlspecialchars(date('M j, Y \a\t g:i:s A', strtotime($report['created_at']) ?: $report['created_at'])) ?></b></h5>
                           
                        </div>
                        <div>
                            <h4 class="mb-0"><b><?= htmlspecialchars($report['business_name'] ?: $report['company_name']) ?></b></h4>
                            <small class="text-muted"><?= htmlspecialchars($report['bank_name']) ?></small>
                        </div>
                        <br>
                        <div class="text-end">
                            
                            <span class="badge bg-light text-dark"><?= $report['merchant_status'] ?></span>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <p class="mb-1"><i class="fa fa-phone"></i> <?= $report['cell_number'] ?></p>
                            <p class="mb-1"><i class="fa fa-envelope me-1"></i> <?= $report['personal_email_address'] ?: '<span class="text-muted">not set</span>' ?></p>
                            <i class="fa fa-globe me-1"></i>
                                <a href="<?= $report['business_website_address'] ?>" target="_blank"><?= $report['business_website_address'] ?></a>
                            </p>
                            <div class="flex-container">
    <div>     
        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="dropdown">
          <a style="color:white" href="<?php echo admin_url('pre_application/view/' . $report['id']); ?>" > <span class="badge bg-primary me-1">0/0</span> Forms</a>
                      
                    </button>     

    </div>
    <div>
    <button class="btn btn-light border dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        <i class="fa fa-cogs me-1"></i> Options
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Auto 1</a></li>
                    </ul>   
  </div>
  <div>
<?php if ($report['esign_sent']): ?>
    <button class="btn btn-secondary w-100" disabled>
        <i class="fa fa-check-circle me-1"></i> Request Sent
    </button>
<?php else: ?>
    <button class="btn btn-success w-100 open-esign-modal" data-id="<?= $report['id']; ?>">
        <i class="fa fa-check me-1"></i> eSign sent
    </button>
<?php endif; ?>


  </div>  
  <div>         
    <button class="btn btn-light border dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        <i class="fa fa-cogs me-1"></i> Automation
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Auto 1</a></li>
                    </ul>   
  </div>
  <!-- <div>
              <button class="btn btn-light border dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        <i class="fa fa-envelope me-1"></i> Send
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Send Email</a></li>
                    </ul>
  </div> -->
<div> 
    <button class="btn btn-light border w-100 open-notes-modal" 
            data-id="<?= $report['id']; ?>" 
            data-name="<?= $report['business_name'] ?: $report['company_name']; ?>">
        <i class="fa fa-sticky-note me-1"></i> Notes
    </button>

    
</div>
<!-- Notes Modal -->




  <!-- <div>
    <button class="btn btn-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        New
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">New Task</a></li>
                    </ul>
  </div> -->
  <!-- <div>
       <button class="btn btn-light border w-100">
                    <i class="fa fa-undo"></i>
                </button>
  </div> -->
</div> 

</div>


                        <div class="col-md-3">
                            
                            <p class="mb-1"><strong>Account:</strong> <?= $report['account_number'] ?></p>
                            <p class="mb-1"><strong>Assigned:</strong>
                                <?php if (!empty($report['assign_employee'])): ?>
                                     <?= html_escape($report['assign_employee']); ?>
                                <?php else: ?>
                                    <span class="text-muted">Unassigned</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-4">
                            
                            <p class="mb-1"><strong>Comments:</strong> <?= $report['comments'] ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md"> <!-- ensures center align + medium size -->
    <div class="modal-content shadow rounded">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="notesModalLabel" style="text-align:center">Notes</h5>
        
      </div>
      <form id="noteForm">
        <div class="modal-body">
          <input type="hidden" name="app_id" id="noteAppId">
          <div class="mb-3">
            <textarea name="note" id="noteText" class="form-control" rows="2" placeholder="Type your note..." required></textarea>
          </div> <br>
          <div id="notesHistory" style="max-height: 300px; overflow-y: auto;" class="bg-light p-2 rounded">
            <p class="text-muted text-center">No notes yet.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Send Note</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="esignConfirmModal" tabindex="-1" aria-labelledby="esignConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" >
    <div class="modal-content text-center p-4" style="padding: 15px;">
      <h4 class="fw-bold mb-3" style="font-size: 1.35rem;">Request a Signature</h4>
      <p class="mb-4" style="font-size: 0.75rem;"> Are you sure you want to send the eSign request for this application?</p>
      <div class="d-flex justify-content-center gap-3">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmEsignSend" class="btn btn-success px-4">Yes, Send</button>
      </div>
    </div>
  </div>
</div>


<?php init_tail(); ?>
<script>
$(document).ready(function () {
    const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    $('.open-notes-modal').on('click', function () {
        const appId = $(this).data('id');
        const appName = $(this).data('name');
        $('#noteAppId').val(appId);
        $('#modalAppName').text(appName);

        // Clear previous data
        $('#noteText').val('');
        $('#notesHistory').html('<p>Loading notes...</p>');

        // Load notes with CSRF token
        $.ajax({
            url: '<?= admin_url("pre_application/get_notes"); ?>',
            type: 'POST',
            data: {
                [csrfName]: csrfHash,
                app_id: appId
            },
            success: function (response) {
                $('#notesHistory').html(response);
            }
        });

        $('#notesModal').modal('show');
    });

    $('#noteForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: csrfHash });

        $.post('<?= admin_url("pre_application/add_note"); ?>', formData, function (res) {
            $('#noteText').val('');
            $('.open-notes-modal[data-id="' + $('#noteAppId').val() + '"]').click(); // reload modal
        });
    });
});

let currentEsignId = null;

$('.open-esign-modal').on('click', function () {
    currentEsignId = $(this).data('id');
    $('#esignConfirmModal').modal('show');
});

$('#confirmEsignSend').on('click', function () {
    $('#esignConfirmModal').modal('hide');

    // Optional: Add AJAX to log eSign request in backend
    $.post('<?= admin_url("pre_application/send_esign"); ?>', {
        app_id: currentEsignId,
        '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
    }, function (response) {
        alert('eSign request sent successfully.');
    });

    // UI Feedback: Optionally append a table or change button state
    const button = $('.open-esign-modal[data-id="' + currentEsignId + '"]');
    button.removeClass('btn-success').addClass('btn-secondary').prop('disabled', true);
    button.html('<i class="fa fa-check-circle me-1"></i> Sent');
});

</script>