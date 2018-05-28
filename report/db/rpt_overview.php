<?php
//======================================
// 功能: URL关键字存在检查
// 参数: $url_key       URL关键字
// 返回: true           存在
// 返回: false          不存在
//======================================
function chk_url_key_exist($url_key)
{
  $db = new DB_REPORT();

  $sql = "SELECT rpt_title FROM rpt_overview WHERE url_key = '{$url_key}'";
  $db->query($sql);
  $rds = $db->recordCount();
  if ($rds == 0)
    return false;
  return true;
}

//======================================
// 函数: 取得指定URL关键字的概要统计报告
// 参数: $url_key       URL关键字
// 返回: 记录
//======================================
function get_rpt_overview_by_url_key($url_key)
{
  $db = new DB_REPORT();
  $sql = "SELECT * FROM rpt_overview WHERE url_key = '{$url_key}'";
  $db->query($sql);
  $row = $db->fetchRow();
  return $row;
}

//======================================
// 函数: 取得概要统计报告所有记录
// 参数: 无
// 返回: 记录数组
//======================================
function get_rpt_overview_all()
{
  $db = new DB_REPORT();
  $sql = "SELECT * FROM rpt_overview ORDER BY rpt_sort";

  $db->query($sql);
  $rows = $db->fetchAll();
  return $rows;
}

//======================================
// 函数: 更新概要统计报告中指定URL关键字的记录
// 参数: $url_key       URL关键字
// 参数: $rpt_count     报告数据
// 返回: true           更新成功
// 返回: false          更新失败
//======================================
function upd_rpt_overview($url_key, $rpt_count)
{
  $db = new DB_REPORT();
  $data['rpt_count'] = $rpt_count;
  $data['rpt_time'] = date('Y-m-d H:i:s');

  $where = "url_key = '{$url_key}'";
  $sql = $db->sqlUpdate("rpt_overview", $data, $where);
  $q_id = $db->query($sql);
  
  if ($q_id == 0)
    return false;
  return true;
}
?>
