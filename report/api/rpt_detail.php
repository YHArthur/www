<?php
require_once '../inc/common.php';
require_once '../db/rpt_overview.php';
require_once '../db/rpt_period_url_action.php';
require_once '../db/cnt_url_action.php';

header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");

/*
========================== 网页访问信息获取 ==========================
参数
    $url_key              访问url关键字

返回
    获取到的网页访问信息
      week_amount                 周访问数
      rows                        每日的访问数据(存在获取，不存在创建)
        rpt_title                 报表标题
        action_count              访问次数
      count_action                所有符合条件的访问总数
              
说明
    查询rpt_period_url_action数据表，如果有数据则进行数据读取(参数为$url_key,$begin_time,$end_time)
    不存在则查询cnt_url_action数据表进行数据查询，通过creat_rpt_detail函数进行数据写入(rpt_period_url_action)。
*/

php_begin();

$args = array('url_key');
chk_empty_args('GET', $args);
$count_num = array();
$data = array();
$pout = array();
// 提交参数整理
$url_key = get_arg_str('GET', 'url_key');
//取得前一天零点的时间戳
$today = strtotime(date('Y-m-d', time())) - 24*60*60;
$endday = $today + 24*60*60;
$over_time = $today - 7*24*60*60;
$week_amount = 0;
//获取总浏览量
$count_action = get_all_action($url_key);

while($today){
  //循环获取每日浏览数
  $rows = get_rpt_overview_detail($url_key, $today,$endday);
  if(!empty($rows)){
      $count_num['rpt_title'] =substr($rows['rpt_title'],5,5);
      $count_num['action_count'] = $rows['action_count'];
      $week_amount += $rows['action_count'];
      $pout[] = $count_num;
    
  }
  else{
    //创建日访问数据记录
    $count = serch_rpt_detail($url_key, $today,$endday);
    list($url_count,$id_count) = $count;
    $data['action_url'] = $url_key;
    $data['rpt_title'] =date("Y-m-d",$today);
    $data['from_time_stamp'] = $today;
    $data['to_time_stamp']  = $endday;
    $data['action_count'] = $url_count;
    $data['rpt_type'] = 'day';
    $data['id_count'] = $id_count;
    $data['rpt_time'] = date('Y-m-d h:i:s');
    $creat = creat_rpt_detail($data);
    array_splice($data, 0, count($data));
    continue;
  }
  $today =$today - 24*60*60;
  $endday = $today +24*60*60;
  if($today == $over_time){
    $today = 0;
  }
  $pout["action_url"] = $rows['action_url'];
}
//返回数据做成
$rtn_ary = array();
$rtn_ary['errcode'] = '0';
$rtn_ary['errmsg'] = '';
$rtn_ary['week_amount'] = $week_amount;
$rtn_ary['all_action'] =$count_action;
$rtn_ary['rows'] = $pout;
$rtn_str = json_encode($rtn_ary);
php_end($rtn_str);

?>
