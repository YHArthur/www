<?php

//======================================
// 函数: 获取$url_key相似的七日内日统计报表
// 参数: $url_key           访问网址url
// 返回: 七日内统计数据
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
// 函数: 获取总访问量
// 参数: $url_key           访问网址url
// 返回: 总访数组
// //======================================
function get_all_action($url_key)
{
  $db = new DB_WWW();

  $sql = "SELECT * FROM cnt_url_action WHERE action_url like '%{$url_key}%'";
  $db  -> query($sql);
  $rows = $db->fetchAll();
  return $rows;
}


//======================================
// 函数: 查询$url_key相似的数据统计
// 参数: $url_key           访问网址url_key
//======================================

function serch_rpt_detail($url_key, $today)
{
  $endtime = $today + 24 *60*60;
  $db = new DB_WWW();

  $sql = "SELECT * FROM cnt_url_action WHERE action_url like '%{$url_key}%' AND action_time >= '{$today}' AND action_time <= '{$endtime}'";
  $db  -> query($sql);
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
//======================================
// 函数: 取得概要统计报告所有记录
// 参数: 无
// 返回: 记录数组
//======================================
function get_rpt_overview_all()
{
  $db = new DB_WWW();
  $sql = "SELECT * FROM rpt_overview ORDER BY rpt_sort";

  $db->query($sql);
  $rows = $db->fetchAll();
  return $rows;
}

//======================================
// 函数: 更新概要统计报告中指定报告标题的记录
// 参数: $rpt_title     报告标题
// 参数: $rpt_count     报告数据
// 返回: true           更新成功
// 返回: false          更新失败
//======================================
function upd_rpt_overview($rpt_title, $rpt_count)
{
  $db = new DB_WWW();
  $data['rpt_count'] = $rpt_count;
  $data['rpt_time'] = date('Y-m-d H:i:s');

  $where = "rpt_title = '{$rpt_title}'";
  $sql = $db->sqlUpdate("rpt_overview", $data, $where);
  $q_id = $db->query($sql);
  
  if ($q_id == 0)
    return false;
  return true;
}
?>
