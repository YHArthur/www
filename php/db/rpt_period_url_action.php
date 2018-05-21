<?php
//======================================
// 函数: 获取$url_key相似的每日日统计报表
// 参数: $url_key           访问网址url
// 返回: 每日统计数据
//======================================
function get_rpt_overview_detail($url_key,$today)
{
  $endtoday = $today + 24*60*60;
  $db = new DB_WWW();
  $sql = "SELECT * FROM rpt_period_url_action WHERE action_url  like '%{$url_key}%' AND from_time_stamp >= '{$today}' AND to_time_stamp <='{$endtoday}'";

  $db->query($sql);
  $rows = $db->fetchAll();
  return $rows;
}

// ======================================
// 函数: 获取$url_key相似的当前周统计报表
// 参数: $url_key           访问网址url
// 返回: 一周统计数据
//======================================
function get_week_overview($url_key)
{
  $endLastweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
  $week_time = $endLastweek - 7*24*60*60;
  $db = new DB_WWW();
  $sql = "SELECT * FROM rpt_period_url_action WHERE action_url  like '%{$url_key}%' AND from_time_stamp >= '{$week_time}' AND to_time_stamp <='{$endLastweek}'";
  $db->query($sql);
  $rows = $db->fetchAll();
  return $rows;
}

//======================================
// 函数: 创建网址访问统计记录
// 参数: $data          信息数组
//======================================
function creat_rpt_detail($data)
{
  // 提交时间
  $db = new DB_WWW();
  $sql = $db->sqlInsert("rpt_period_url_action", $data);
  $q_id = $db->query($sql);
  if ($q_id == 0)
    return 0;
  return $db->insertID();
}

?>