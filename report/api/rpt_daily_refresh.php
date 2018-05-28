<?php
require_once '../inc/common.php';
require_once '../db/rpt_overview.php';
require_once '../db/cnt_url_action.php';
require_once '../db/rpt_period_url_action.php';

/*
========================== 网页访问日报统计刷新 ==========================
GET参数
  url_key         URL关键字

返回
  刷新数据，不管对错，不返回任何东西

说明
  判断该URL关键字是否在rpt_overview表中存在，不存在不处理
  存在从rpt_period_url_action表中取出最近一次日报数据和当前时间比较，
  若是新的日报数据需要生成则生成，一直到处理完毕。
*/

php_begin();

// URL关键字
$url_key = get_arg_str('GET','url_key', 512);

// URL关键字为空退出
if (empty($url_key))
  exit();

// URL关键字不存在退出
if (!chk_url_key_exist($url_key))
  exit();

// 取得最近一次URL关键字的统计时间戳
$count_from_time = get_daily_rpt_url_action_last_time($url_key);
if (empty($last_day)) {
  // 取得最早一次URL关键字的访问时间戳
  $first_time = get_url_action_first_time($url_key);
  $count_from_time  =strtotime(date("Y-m-d", $first_time));
}

// 统计起始日期为空退出
if (empty($count_from_time))
  exit();

// 统计结束时间戳
$count_to_time = strtotime(date("Y-m-d", strtotime("-1 day")));

var_dump(date("Y-m-d  H:i:s", $count_from_time));
var_dump(date("Y-m-d  H:i:s", $count_to_time));

exit();
?>
