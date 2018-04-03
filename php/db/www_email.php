<?php
//======================================
// 函数: 用户邮件列表查询
// 参数: $email         Email地址
// 返回: 符合条件的记录数组
//======================================
function get_www_email($email)
{
  $db = new DB_WWW();

  $sql = "SELECT * FROM www_email WHERE email = '{$email}'";
  $db->query($sql);
  $row = $db->fetchRow();
  return $row;
}

//======================================
// 函数: 取得同一UUID下邮件地址的数量
// 参数: $uuid          用户ID
// 返回: 符合条件的记录数量
//======================================
function get_email_count_by_uuid($uuid)
{
  $db = new DB_WWW();

  $sql = "SELECT count(logid) as logid_count FROM www_email WHERE uuid = '{$uuid}' AND is_void = 0";
  $logid_count = $db->getField($sql, 'logid_count');
  // if (is_null($logid_count)) $logid_count = 0;
  return $logid_count;
}

//======================================
// 函数: 取得同一IP下近期订阅邮件地址的数量
// 参数: $user_ip       用户IP地址
// 返回: 符合条件的记录数量
//======================================
function get_recent_email_count_by_ip($user_ip)
{
  // 最近2小时
  $recent_time = time() - 2 * 60 * 60;
  $db = new DB_WWW();

  $sql = "SELECT count(logid) as logid_count FROM www_email WHERE user_ip = $user_ip AND utime > $recent_time";
  $logid_count = $db->getField($sql, 'logid_count');
  // if (is_null($logid_count)) $logid_count = 0;
  return $logid_count;
}

//======================================
// 函数: 创建用户邮件列表
// 参数: $data          信息数组
// 返回: true           创建成功
// 返回: false          创建失败
//======================================
function ins_www_email($data)
{
  // 更新时间
  $data['utime'] = time();
  // 创建时间
  $data['ctime'] = date('Y-m-d H:i:s');

  $db = new DB_WWW();

  $sql = $db->sqlInsert("www_email", $data);
  $q_id = $db->query($sql);

  if ($q_id == 0)
    return false;
  return true;
}

//======================================
// 函数: 更新用户邮件列表
// 参数: $data          信息数组
// 参数: $logid         记录ID
// 返回: true           更新成功
// 返回: false          更新失败
//======================================
function upd_www_email($data, $logid)
{
  // 更新时间
  $data['utime'] = time();

  $db = new DB_WWW();

  $where = "logid = {$logid}";
  $sql = $db->sqlUpdate("www_email", $data, $where);
  $q_id = $db->query($sql);

  if ($q_id == 0)
    return false;
  return true;
}
?>
