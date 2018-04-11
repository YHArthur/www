<?php
require_once 'inc/common.php';
require_once 'db/www_contact.php';

header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");

/*
========================== 联系我们 ==========================
POST参数
  uuid            UUID
  user_name       用户称呼
  user_email      用户Email
  user_suggestion 用户建议

返回
  errcode = 0 请求成功

说明
  风赢科技联系我们规则
  1：一个UUID一天最多提交3次用户建议，超出视为恶意攻击，返回成功即可，不用真实入库
  2：一个IP地址一天最多订阅3个用户建议，超出视为恶意攻击，返回成功即可，不用真实入库
*/

php_begin();

// 参数检查
$args = array('uuid', 'user_name', 'user_email', 'user_suggestion');
chk_empty_args('GET', $args);

// 提交参数整理
$uuid = get_arg_str('GET', 'uuid');
$user_name = get_arg_str('GET', 'user_name');
$user_email = get_arg_str('GET', 'user_email');
$user_suggestion = get_arg_str('GET', 'user_suggestion');
$user_ip = get_int_ip();

// 默认返回信息
$msg = '提交成功';

// 取得同一UUID下近期用户建议的数量
$uuid_count = get_recent_contact_count_by_uuid($uuid);
if ($uuid_count > 3)
  exit_ok($msg);

// 取得同一IP下近期用户建议的数量
$ip_count = get_recent_contact_count_by_ip($user_ip);
if ($ip_count > 3)
  exit_ok($msg);

// 字段设定
$data = array();
$data['uuid'] = $uuid;
$data['user_name'] = $user_name;
$data['user_email'] = $user_email;
$data['user_suggestion'] = $user_suggestion;
$data['user_ip'] = $user_ip;
// 创建用户邮件列表
$ret = ins_www_contact($data);

// 正常返回
exit_ok($msg);
?>
