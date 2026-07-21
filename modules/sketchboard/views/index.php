<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="col-md-12 px-1">
            <div class="tw-mb-2 sm:tw-mb-4">
                <div class="_buttons">
                <?php if(has_permission('sketchboard', get_staff_user_id(), 'create')){ ?>
                    <a  class="btn btn-primary pull-left display-block new-proposal-btn" onclick="addsketchboard(); return false;">
                        <i class="fa-regular fa-plus tw-mr-1"></i><?php echo _l('new_sketchboard') ?> 
                    </a>
                <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body">
                            <table class="table dt-table" data-order-col="0" data-order-type="asc">
                                <thead>
                                    <th>#</th>
                                    <th><?php echo _l('title'); ?></th>
                                    <th><?php echo _l('description'); ?></th>
                                    <th><?php echo _l('created_at'); ?></th>
                                </thead>
                                <tbody>

                                    <?php $no = 1; foreach ($records as $bord) { ?>
                                    <tr>
                                    <td><?=$no?></td>
                                    <td>
                                        <a href="<?= admin_url('sketchboard/view/').$bord['id']; ?>" class="display-block main-tasks-table-href-name"><?=$bord['name'];?></a>

                                        <div class="row-options">
                                            <?php if(!empty($bord['hash'])){ ?>
                                            <a href="javascript:void(0);" class="copy_link"  data-link="<?= site_url('sketchboard/link/view/'.$bord['hash']);?>" data-desc="<?=$bord['description'];?>">Copy share link </a>
                                            <span class="tw-text-neutral-300"> | </span>

                                                <?php } if(has_permission('sketchboard', '', 'edit')){ ?>
                                            <a href="#" class="editboard" data-project_id="<?=$bord['project_id'];?>"  data-name="<?=$bord['name'];?>"  data-edited="<?=$bord['is_public_edit'];?>" data-id="<?=$bord['id'];?>" data-desc="<?=$bord['description'];?>">Edit </a>
                                            <?php } ?>
                                            <span class="tw-text-neutral-300"> | </span>
                                            <?php if(has_permission('sketchboard', '', 'delete')){ ?>
                                            <a href="<?= admin_url('sketchboard/delete/').$bord['id']; ?>" class="text-danger _delete task-delete">Delete </a>
                                            <?php } ?>
                                            </div>
                                        </td>
                                        <td><?php echo $bord['description']; ?></td>
                                        <td><?php echo _dt($bord['date_created']); ?></td>
                                       
                                    </tr>
                                    <?php $no++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php $this->load->view('create_edit_modal') ?>
<?php init_tail(); ?>
<script>

    var sketchboard_html = `
     <?php  echo render_input('name', 'title', '', 'text',['required'=>'required']); 
            echo render_textarea('description', 'description','');
            $selected = ''; ?>
              <div class="form-group">
                <label for="is_public_edit"><?php echo _l('Project'); ?></label><br />
               <select name="project_id" id="project_id" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                    <option value=""><?php echo _l('Select Project'); ?></option>
                    <?php  
                    foreach ($projects as $key => $project) { ?>

                        <option value="<?=$project['id'];?>"><?=$project['name'];?></option>

                <?php     }
                ?>
                </select>
                </div>
                <div class="form-group">
                    <label for="is_public_edit"><?php echo _l('is_public_edit'); ?></label><br />
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" name="is_public_edit" value="1" id="is_public_edit_yes">
                        <label for="is_public_edit_yes">Yes</label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" name="is_public_edit" value="0" id="is_public_edit_no" checked>
                        <label for="is_public_edit_no">No</label>
                    </div>
                </div>`;

                                

    function addsketchboard(){
        $("#sketchboard_model_body").html(sketchboard_html);
        $('select').selectpicker();
        $("#sketchboard-modal").modal('show');
    }
    $(document).on('click','.editboard',function(){
        var name = $(this).attr('data-name');
        var bord_id = $(this).attr('data-id');
        var desc = $(this).attr('data-desc');
        var project_id = $(this).attr('data-project_id');
        var edit = $(this).attr('data-edited');
       
        $("#edit-sketchboard-modal").find('#bord_id').val(bord_id);
        $("#edit-sketchboard-modal").find('#name').val(name);
        $("#edit-sketchboard-modal").find('#description').val(desc);
        if(edit == 1){
            $("#edit-sketchboard-modal").find('#edit_is_public_edit').trigger('click');

        }else{
            $("#edit-sketchboard-modal").find('#edit_is_public_edit_no').trigger('click');
        }
            
        $('#edit_project_id').selectpicker('val', project_id);
        $("#edit-sketchboard-modal").modal('show');
    });

   
    function saveSketchboard() {
        var name = $('input[name="name"]').val();
        var description = $('textarea[name="description"]').val();
        var is_edit = $('input[name="is_public_edit"]:checked').val();
        var project_id = $('#project_id').val();
        
        
        if (name === '') {
            alert_float('danger', 'Title is required.');
            return;
        }
        $.ajax({
            url: '<?= admin_url('sketchboard/save_sketchboard'); ?>', // Replace with your actual URL
            type: 'POST',
            data: {
                name: name,
                description: description,
                project_id:project_id,
                is_public_edit:is_edit
            },
            success: function(response) {
                var response = JSON.parse(response);
                
                if (response.success) {
                    alert_float('success', response.message);
                    $('#sketchboard-modal').modal('hide');
                    setTimeout(() => {
                        location.href = '<?= admin_url('sketchboard/view/'); ?>'+response.insert_id; 
                    }, 300);
                } else {
                    alert_float('danger', response.message);
                }
            }
        });
    }



    $('.copy_link').on('click', function() {
        const link = $(this).data('link');
        const fullLink = link; 
        
        navigator.clipboard.writeText(fullLink)
            .then(() => {
                alert_float('success', 'Link copied to clipboard!');
            })
            .catch(err => {
                alert_float('danger', 'Failed to copy the link.');

            });
    });



</script>
</body>
</html>