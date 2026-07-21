<?php 
defined('BASEPATH') or exit('No direct script access allowed');

$select = [
    'date_format(time, \'%Y-%m-%d\') as date',
    'SUM(CASE WHEN type = "follower" THEN value ELSE 0 END) AS follower',
    'SUM(CASE WHEN type = "following" THEN value ELSE 0 END) AS following',
    'SUM(CASE WHEN type = "video" THEN value ELSE 0 END) AS video',
    'SUM(CASE WHEN type = "video_view" THEN value ELSE 0 END) AS video_view',
    'SUM(CASE WHEN type = "engagement" THEN value ELSE 0 END) AS engagement',
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
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'follower') ? 'text-danger' : 'text-success').'">'.number_format($aRow['follower'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['follower'].'" data-type="follower" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'following') ? 'text-danger' : 'text-success').'">'.number_format($aRow['following'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['following'].'" data-type="following" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'video') ? 'text-danger' : 'text-success').'">'.number_format($aRow['video'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['video'].'" data-type="video" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'video_view') ? 'text-danger' : 'text-success').'">'.number_format($aRow['video_view'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['video_view'].'" data-type="video_view" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'engagement') ? 'text-danger' : 'text-success').'">'.number_format($aRow['engagement'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['engagement'].'" data-type="engagement" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    
    $output['aaData'][] = $row;
}