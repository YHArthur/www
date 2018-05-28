<?php
//======================================
// 函数: 取得最早一次指定URL关键字的访问时间戳
// 参数: $url_key       URL关键字
// 返回: 时间戳
//======================================
function get_url_action_first_time($url_key)
{
  $db = new DB_REPORT();

  $sql = "SELECT action_time FROM cnt_url_action WHERE action_url like '%{$url_key}%' ORDER BY action_time LIMIT 1";
  $action_time = $db->getField($sql, 'action_time');
  return $action_time;
}

//======================================
// 函数: 取得指定URL关键字在指定时间段内的访问次数和访问ID数量
// 参数: $url_key           URL关键字
// 参数: $time_from         开始时间戳
// 参数: $time_to           结束时间戳
// 返回: 数组
// 返回: action_count       访问次数
// 返回: id_count           访问ID数量
//======================================
function get_url_action_duration_count($url_key, $time_from, $time_to)
{
  $db = new DB_REPORT();
  $sql = "SELECT COUNT(logid) AS action_count,";
  $sql .= " COUNT(DISTINCT action_id) AS id_count";
  $sql .= " FROM cnt_url_action";
  $sql .= " WHERE action_url like '%{$url_key}%'";
  $sql .= " AND action_time >= '{$time_from}'";
  $sql .= " AND action_time <= '{$time_to}'";
  
  $db->query($sql);
  $row = $db->fetchRow();
  $action_count = $row['action_count'];
  $id_count = $row['id_count'];
  return array($action_count, $id_count);
}

//======================================
// 函数: 创建网址访问记录
// 参数: $data          信息数组
// 返回: id             新的记录ID
// 返回: 0              创建失败
//======================================
function ins_cnt_url_action($data)
{
  // 提交时间
  $data['action_time'] = time();

  $db = new DB_REPORT();

  $sql = $db->sqlInsert("cnt_url_action", $data);
  $q_id = $db->query($sql);

  if ($q_id == 0)
    return 0;
  return $db->insertID();
}

?>

