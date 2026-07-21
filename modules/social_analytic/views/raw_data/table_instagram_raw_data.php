<?php 
defined('BASEPATH') or exit('No direct script access allowed');

$select = [
    'date_format(time, \'%Y-%m-%d\') as date',
    'SUM(CASE WHEN type = "impression" THEN value ELSE 0 END) AS impression',
    'SUM(CASE WHEN type = "engagement" THEN value ELSE 0 END) AS engagement',
    'SUM(CASE WHEN type = "profile_action" THEN value ELSE 0 END) AS profile_action',
    'SUM(CASE WHEN type = "follower" THEN value ELSE 0 END) AS follower',
    'SUM(CASE WHEN type = "comment" THEN value ELSE 0 END) AS comment',
    'SUM(CASE WHEN type = "post" THEN value ELSE 0 END) AS post',
    'SUM(CASE WHEN type = "active_user" THEN value ELSE 0 END) AS active_user',
    'SUM(CASE WHEN type = "published_stories" THEN value ELSE 0 END) AS published_stories',
    'SUM(CASE WHEN type = "stories_performance" THEN value ELSE 0 END) AS stories_performance',
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
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'impression') ? 'text-danger' : 'text-success').'">'.number_format($aRow['impression'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['impression'].'" data-type="impression" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'engagement') ? 'text-danger' : 'text-success').'">'.number_format($aRow['engagement'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['engagement'].'" data-type="engagement" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'profile_action') ? 'text-danger' : 'text-success').'">'.number_format($aRow['profile_action'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['profile_action'].'" data-type="profile_action" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'follower') ? 'text-danger' : 'text-success').'">'.number_format($aRow['follower'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['follower'].'" data-type="follower" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'comment') ? 'text-danger' : 'text-success').'">'.number_format($aRow['comment'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['comment'].'" data-type="comment" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'post') ? 'text-danger' : 'text-success').'">'.number_format($aRow['post'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['post'].'" data-type="post" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'active_user') ? 'text-danger' : 'text-success').'">'.number_format($aRow['active_user'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['active_user'].'" data-type="active_user" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'published_stories') ? 'text-danger' : 'text-success').'">'.number_format($aRow['published_stories'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['published_stories'].'" data-type="published_stories" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'stories_performance') ? 'text-danger' : 'text-success').'">'.number_format($aRow['stories_performance'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['stories_performance'].'" data-type="stories_performance" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');

    $output['aaData'][] = $row;
}