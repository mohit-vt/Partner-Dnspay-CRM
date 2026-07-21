<?php
defined('BASEPATH') or exit('No direct script access allowed');
 defined('BASEPATH') or exit('No direct script access allowed'); 
$isGridView = 0;

?>

<div class="panel-body">

 <div class="row" id="sketchboard-table">
<div class="col-md-12">
 <?php render_datatable(array(
                                _l('title'),
                                _l('description'),
                                _l('created_at'),
                                _l('')
                            ),'sketchboard', array('customizable-table'),
                              array(
                                  'id'=>'table-sketchboard',
                                  'data-last-order-identifier'=>'sketchboard',
                                  'data-default-order'=>get_table_last_order('sketchboard'),
                              )); ?>
                        <?php ?>
                        </div>

</div>
                        </div>
                        <?php init_tail(); ?>
 <script>
    var _lnth = 12;
$(function(){
    var id = $('input[name=project_id]').val();
    var TblServerParams = {
        "project_id": id,
    };

        var tAPI = initDataTable('.table-sketchboard', admin_url+'sketchboard/sketchboard_table?project_id='+id, [2, 3], [2, 3], TblServerParams);
        console.log(tAPI);
        $.each(TblServerParams, function(i, obj) {
            $('select' + obj).on('change', function() {
                $('table.table-sketchboard').DataTable().ajax.reload()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });

  
});
</script>