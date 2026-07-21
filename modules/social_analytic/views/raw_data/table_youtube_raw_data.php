<?php 
defined('BASEPATH') or exit('No direct script access allowed');

$select = [
    'date_format(time, \'%Y-%m-%d\') as date',
    'SUM(CASE WHEN type = "subscriber" THEN value ELSE 0 END) AS subscriber',
    'SUM(CASE WHEN type = "view" THEN value ELSE 0 END) AS view',
    'SUM(CASE WHEN type = "like" THEN value ELSE 0 END) AS like_total',
    'SUM(CASE WHEN type = "dislike" THEN value ELSE 0 END) AS dislike',
    'SUM(CASE WHEN type = "share" THEN value ELSE 0 END) AS share',
    'SUM(CASE WHEN type = "comment" THEN value ELSE 0 END) AS comment',
    'SUM(CASE WHEN type = "video" THEN value ELSE 0 END) AS video',
    'SUM(CASE WHEN type = "estimated_minutes_watched" THEN value ELSE 0 END) AS estimated_minutes_watched',
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
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'subscriber') ? 'text-danger' : 'text-success').'">'.number_format($aRow['subscriber'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['subscriber'].'" data-type="subscriber" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'view') ? 'text-danger' : 'text-success').'">'.number_format($aRow['view'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['view'].'" data-type="view" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'like') ? 'text-danger' : 'text-success').'">'.number_format($aRow['like_total'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['like_total'].'" data-type="like" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'dislike') ? 'text-danger' : 'text-success').'">'.number_format($aRow['dislike'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['dislike'].'" data-type="dislike" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'share') ? 'text-danger' : 'text-success').'">'.number_format($aRow['share'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['share'].'" data-type="share" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'comment') ? 'text-danger' : 'text-success').'">'.number_format($aRow['comment'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['comment'].'" data-type="comment" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'video') ? 'text-danger' : 'text-success').'">'.number_format($aRow['video'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['video'].'" data-type="video" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    $row[] = '<label class="'.(check_raw_data_import($account, $aRow['date'], 'estimated_minutes_watched') ? 'text-danger' : 'text-success').'">'.number_format($aRow['estimated_minutes_watched'] ?? 0) .'</label>'.( $lock == 0 ? '<a href="#" onclick="edit_data(this); return false;" data-value="'.$aRow['estimated_minutes_watched'].'" data-type="estimated_minutes_watched" data-date="'._d($aRow['date']).'" class="btn btn-icon mbot15 pull-right"><i class="fa fa-edit"></i></a>' : '');
    
    $output['aaData'][] = $row;
}