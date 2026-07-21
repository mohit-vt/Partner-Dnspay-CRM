<?php 
defined('BASEPATH') or exit('No direct script access allowed');

$select = [
    'date_format(time, \'%Y-%m-%d\') as date',
    'SUM(CASE WHEN type = "fan" THEN value ELSE 0 END) AS fan',
    'SUM(CASE WHEN type = "impression" THEN value ELSE 0 END) AS impression',
    'SUM(CASE WHEN type = "reaction" THEN value ELSE 0 END) AS reaction',
    'SUM(CASE WHEN type = "click" THEN value ELSE 0 END) AS click',
    'SUM(CASE WHEN type = "comment" THEN value ELSE 0 END) AS comment',
    'SUM(CASE WHEN type = "message_count" THEN value ELSE 0 END) AS message_count',
    'SUM(CASE WHEN type = "post" THEN value ELSE 0 END) AS post',
    'SUM(CASE WHEN type = "share" THEN value ELSE 0 END) AS share',
    'SUM(CASE WHEN type = "active_user" THEN value ELSE 0 END) AS active_user',
];

$where = [];
array_push($where, 'AND account_id = "'.$account.'"');

if ($from_date) {
    $from_date = $from_date;
    $from_date = to_sql_date($from_date);
}

if ($to_date) {
    $to_date = $to_date;
    $to_date = to_sql_date($to_date);
}
if ($from_date != '' && $to_date != '') {
    array_push($where, 'AND (date_format(time, \'%Y-%m-%d\') >= "' . $from_date . '" and date_format(time, \'%Y-%m-%d\') <= "' . $to_date . '")');
} elseif ($from_date != '') {
    array_push($where, 'AND (date_format(time, \'%Y-%m-%d\') >= "' . $from_date . '")');
} elseif ($to_date != '') {
    array_push($where, 'AND (date_format(time, \'%Y-%m-%d\') <= "' . $to_date . '")');
}

$aColumns     = $select;
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'sa_analytics';
$join         = [
];
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [], 'GROUP BY date_format(time, \'%Y-%m-%d\')');
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row   = [];
   
    $row[] = _d($aRow['date']);
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'fan') ? 'text-danger' : 'text-success').'">'.number_format($aRow['fan'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['fan'].'" data-type="fan" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'impression') ? 'text-danger' : 'text-success').'">'.number_format($aRow['impression'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['impression'].'" data-type="impression" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'reaction') ? 'text-danger' : 'text-success').'">'.number_format($aRow['reaction'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['reaction'].'" data-type="reaction" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'click') ? 'text-danger' : 'text-success').'">'.number_format($aRow['click'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['click'].'" data-type="click" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'comment') ? 'text-danger' : 'text-success').'">'.number_format($aRow['comment'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['comment'].'" data-type="comment" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'message_count') ? 'text-danger' : 'text-success').'">'.number_format($aRow['message_count'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['message_count'].'" data-type="message_count" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'post') ? 'text-danger' : 'text-success').'">'.number_format($aRow['post'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['post'].'" data-type="post" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'share') ? 'text-danger' : 'text-success').'">'.number_format($aRow['share'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['share'].'" data-type="share" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'active_user') ? 'text-danger' : 'text-success').'">'.number_format($aRow['active_user'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['active_user'].'" data-type="active_user" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');

   
    $output['aaData'][] = $row;
}