<?php
//======================================
// 函数: 取得同一UUID下近期用户建议的数量
// 参数: $uuid          用户ID
// 返回: 符合条件的记录数量
//======================================
function get_recent_contact_count_by_uuid($uuid)
{
  $db = new DB_WWW();

  $sql = "SELECT count(logid) as logid_count FROM www_contact WHERE uuid = '{$uuid}'";
  $logid_count = $db->getField($sql, 'logid_count');
  // if (is_null($logid_count)) $logid_count = 0;
  return $logid_count;
}

//======================================
// 函数: 取得同一IP下近期用户建议的数量
// 参数: $user_ip       用户IP地址
// 返回: 符合条件的记录数量
//======================================
function get_recent_contact_count_by_ip($user_ip)
{
  // 最近2小时
  $recent_time = time() - 2 * 60 * 60;
  $db = new DB_WWW();

  $sql = "SELECT count(logid) as logid_count FROM www_contact WHERE user_ip = $user_ip AND utime > $recent_time";
  $logid_count = $db->getField($sql, 'logid_count');
  // if (is_null($logid_count)) $logid_count = 0;
  return $logid_count;
}

//======================================
// 函数: 创建用户建议
// 参数: $data          信息数组
// 返回: id             新的记录ID
// 返回: 0              创建失败
//======================================
function ins_www_contact($data)
{
  // 提交时间
  $data['ctime'] = date('Y-m-d H:i:s');

  $db = new DB_WWW();

  $sql = $db->sqlInsert("www_contact", $data);
  $q_id = $db->query($sql);

  if ($q_id == 0)
    return 0;
  return $db->insertID();
}

?>
