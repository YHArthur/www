<?php
require_once 'inc/common.php';
require_once 'db/www_email.php';

header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");

/*
========================== 免费订阅 ==========================
POST参数
  email           Email地址
  uuid            UUID

返回
  errcode = 0     请求成功

说明
  风赢科技邮件列表免费订阅规则
  1：一个UUID最多订阅3个邮件地址，超出视为恶意攻击，返回成功即可，不用真实入库
  2：一个IP地址一天最多订阅3个邮件地址，超出视为恶意攻击，返回成功即可，不用真实入库
  3：该邮件地址若数据库中已经存在，更新相关字段，且把订阅设为有效，若原来订阅有效，告知用户无需重复订阅，若原来订阅无效，告诉用户重新订阅成功。
  4：该邮件地址若数据库中不存在，创建，给用户发欢迎邮件，告知用户已经订阅成功。
*/

php_begin();

// 参数检查
$args = array('email', 'uuid');
chk_empty_args('GET', $args);

// 提交参数整理
$email = get_arg_str('GET', 'email');
$uuid = get_arg_str('GET', 'uuid');
$user_ip = get_int_ip();

// 默认返回信息
$msg = '订阅成功';

// 取得同一UUID下邮件地址的数量
$uuid_count = get_email_count_by_uuid($uuid);
if ($uuid_count > 3)
  exit_ok($msg);

// 取得同一IP下近期订阅邮件地址的数量
$ip_count = get_recent_email_count_by_ip($user_ip);
if ($ip_count > 3)
  exit_ok($msg);

// 判断该邮件地址是否已经存在
$rec = get_www_email($email);

// 字段设定
$data = array();
$data['uuid'] = $uuid;
$data['user_ip'] = $user_ip;
$data['is_void'] = 0;

// 邮件地址已经存在
if ($rec) {
  $id = $rec['logid'];
  // 修改用户邮件列表
  $ret = upd_www_email($data, $id);

  $msg = $email . '重新订阅成功';
  $is_void = $rec['is_void'];
  if ($is_void == 0)
    $msg = $email . '已经订阅成功，无需重复订阅';
} else {
  // 字段设定
  $data['email'] = $email;
  // 创建用户邮件列表
  $ret = ins_www_email($data);
}

// 正常返回
exit_ok($msg);
?>
