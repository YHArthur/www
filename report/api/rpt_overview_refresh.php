<?php
require_once '../inc/common.php';
require_once '../db/rpt_overview.php';
require_once '../db/cnt_url_action.php';

/*
========================== 网页访问概要统计刷新 ==========================
参数
  无

返回
  刷新数据，不管对错，不返回任何东西

说明
  将rpt_overview表中所有记录读出，按照URL关键字（url_key）统计cnt_url_action表符合条件记录的条数，
  并更新rpt_overview表中对应记录的报告数据（rpt_count）和报告时间（rpt_time）。
*/
php_begin();

// 取得概要统计报告所有记录
$rows = get_rpt_overview_all();
// 有记录存在
if ($rows) {
  foreach ($rows as $row) {
    $url_key = $row['url_key'];                   // URL关键字
    $time_from = time() - 24 * 60 * 60;           // 开始时间戳
    $time_to = time();                            // 结束时间戳
    
    // 取得指定URL关键字在指定时间段内的访问次数和访问ID数量
    list($action_count, $id_count) = get_url_action_duration_count($url_key, $time_from, $time_to);
    // 更新概要统计报告中指定报告标题的记录
    upd_rpt_overview($url_key, $action_count);
  }
}

exit();
?>
