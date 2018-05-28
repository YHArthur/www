<?php
//======================================
// 函数: 取得最近一次URL关键字的统计时间戳
// 参数: $url_key       URL关键字
// 返回: 时间戳
//======================================
function get_daily_rpt_url_action_last_time($url_key)
{
  $db = new DB_REPORT();
  $sql = "SELECT from_time_stamp FROM rpt_period_url_action WHERE action_url ='{$url_key}' AND rpt_type = 'day' ORDER BY from_time_stamp DESC LIMIT 1";
  $from_time_stamp = $db->getField($sql, 'from_time_stamp');
  return $from_time_stamp;
}

//======================================
// 函数: 获取指定URL关键字的日报统计数据
// 参数: $url_key       URL关键字
// 参数: $time_from     开始时间戳
// 参数: $time_to       结束时间戳
// 返回: 记录数组
//======================================
function get_daily_rpt_url_action($url_key, $time_from, $time_to)
{
  $db = new DB_REPORT();
  $sql = "SELECT * FROM rpt_period_url_action";
  $sql .= " WHERE action_url = '{$url_key}'";
  $sql .= " AND rpt_type = 'day'";
  $sql .= " AND from_time_stamp >= '{$time_from}'";
  $sql .= " AND to_time_stamp <='{$time_to}'";
  $sql .= " ORDER BY from_time_stamp";
  $db->query($sql);
  $rows = $db->fetchRow();
  return $rows;
}

//======================================
// 函数: 创建期间网址访问统计报表
// 参数: $data          信息数组
// 返回: true           创建成功
// 返回: false          创建失败
//======================================
function ins_rpt_period_url_action($data)
{
  // 提交时间
  $data['rpt_time'] = date('Y-m-d H:i:s');

  $db = new DB_REPORT();
  $sql = $db->sqlInsert("rpt_period_url_action", $data);
  $q_id = $db->query($sql);
  if ($q_id == 0)
    return false;
  return true;
}

?>
