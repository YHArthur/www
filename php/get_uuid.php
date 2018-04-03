<?php
require_once 'inc/common.php';

header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");

/*
========================== 获取用户UUID ==========================
输入参数
  无

返回
  uuid            UUID
  errcode = 0     请求成功

说明
  获取用户唯一识别标识
*/

php_begin();

// 取得唯一标示符GUID
$uuid = get_guid();

// 返回数据作成
$rtn_ary = array();
$rtn_ary['errcode'] = '0';
$rtn_ary['errmsg'] = '';
$rtn_ary['uuid'] = $uuid;

$rtn_str = json_encode($rtn_ary);
// 输出内容
php_end($rtn_str);
?>
