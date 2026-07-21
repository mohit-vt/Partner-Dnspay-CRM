<div class="modal fade " id="sketchboard-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-mg">
        <div class="modal-content data">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add new</h4>
            </div>
            <div class="modal-body" id="sketchboard_model_body">
               

            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveSketchboard(); return false;">Save</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade " id="edit-sketchboard-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <?php echo form_open(admin_url('sketchboard/update_sketchboard')); ?>

        <div class="modal-dialog modal-mg">
            <div class="modal-content data">
                <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="bord_id" name="id" class="form-control">
                    <?php echo render_input('name', 'title', '', 'text',['required'=>'required']); ?> 
                    <?php echo render_textarea('description', 'description',''); ?>
                    <div class="form-group">
                        <label for="is_public_edit"><?php echo _l('Project'); ?></label><br />
                        <select name="project_id" id="edit_project_id" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""><?php echo _l('Select Project'); ?></option>
                        <?php  
                            foreach ($projects as $key => $project) { ?>
                                <option value="<?=$project['id'];?>"><?=$project['name'];?></option>

                        <?php  }?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_public_edit"><?php echo _l('is_public_edit'); ?></label><br />
                        <div class="radio radio-primary radio-inline">
                            <input type="radio" name="is_public_edit" value="1" id="edit_is_public_edit">
                            <label for="edit_is_public_edit">Yes</label>
                        </div>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio" name="is_public_edit" value="0" id="edit_is_public_edit_no">
                            <label for="edit_is_public_edit_no">No</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="subnit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>