<?php

$CI          = & get_instance();
$custom_fields = get_table_custom_fields('whiteboard');

$CI->db->query("SET sql_mode = ''");
$aColumns = [
    'id',
    db_prefix() . 'sketchboard.name',
    db_prefix() . 'sketchboard.description',
    db_prefix() . 'sketchboard.date_created',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'sketchboard';
$join = [];
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

$where = [
    'AND project_id=' . $project_id,
];
// Add blank where all filter can be stored
$filter = [];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 3) {
    $CI->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] !== 'id') {
            if ($aColumns[$i] == db_prefix() . 'sketchboard.name') {
                $_data = '<a href="' . admin_url('sketchboard/view/' . $aRow['id']) . '" >' . $_data . '</a>';
            } elseif ($aColumns[$i] == db_prefix() . 'sketchboard.description' || $aColumns[$i] == 'description') {
                $_data = $_data;
            }elseif ($aColumns[$i] == db_prefix() . 'sketchboard.date_created' || $aColumns[$i] == 'date_created') {
                $_data = _dt($_data);
            }else{
                $_data = $_data;
            }
            $row[] = $_data;
        }
        
    }
    ob_start();
    ?>

    <?php
    $progress = ob_get_contents();
    ob_end_clean();
    $row[]              = $progress;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
