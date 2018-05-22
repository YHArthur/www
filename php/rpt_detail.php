<?php
require_once 'inc/common.php';
require_once 'db/rpt_overview.php';
require_once 'db/rpt_period_url_action.php';
require_once 'db/cnt_url_action.php';

date_default_timezone_set('Asia/Shanghai');//设置时区
header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");

php_begin();

$args = array('url_key');
chk_empty_args('GET', $args);
$count_num = array();
$data = array();
$pout = array();
// 提交参数整理
$url_key = get_arg_str('GET', 'url_key');
//取得当天零点的时间戳
$today = strtotime(date('Y-m-d', time()));
$endLastweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
$week_time = $endLastweek - 7*24*60*60;

//获取周浏览数
$week_rows = get_week_overview($url_key,$week_time,$endLastweek);
if($week_rows ==''){
  $count = serch_rpt_detail($url_key,$week_time,$endLastweek);
  $id_count = serch_rpt_detail_id($url_key,$week_time,$endLastweek);
  $data['action_url'] = $url_key;
  $data['rpt_title'] =date("Y-m-d",$today);
  $data['from_time_stamp'] = $week_time;
  $data['to_time_stamp']  = $endLastweek;
  $data['action_count'] = $count;
  $data['rpt_type'] = 'week';
  $data['rpt_time'] = date('Y-m-s h:i:s');
  $creat = creat_rpt_detail($data);
  array_splice($data, 0, count($data));
  $week_rows = get_week_overview($url_key,$week_time,$endLastweek);
}

//获取总浏览量
$all_action = get_all_action($url_key);

$endday = $today + 24*60*60;
$over_time = $today - 7*24*60*60;
while($today){
  //获取每日浏览数
  $rows = get_rpt_overview_detail($url_key, $today,$endday);
  if(count($rows)){
    foreach($rows as $row){
      $count_num['rpt_title'] =substr($row['rpt_title'],5,10);
      $count_num['action_count'] = $row['action_count'];
      $pout[] = $count_num;
    }
  }
  else{
    $count = serch_rpt_detail($url_key, $today,$endday);
    $id_count = serch_rpt_detail_id($url_key,$today,$endday);
    $data['action_url'] = $url_key;
    $data['rpt_title'] =date("Y-m-d",$today);
    $data['from_time_stamp'] = $today;
    $data['to_time_stamp']  = $today + 24*60*60;
    $data['action_count'] = $count;
    $data['rpt_type'] = 'day';
    $data['rpt_time'] = date('Y-m-s h:i:s');
    $creat = creat_rpt_detail($data);
    array_splice($data, 0, count($data));
    continue;
  }
  $today =$today - 24*60*60;
  if($today == $over_time){
    $today = 0;
  }
  $pout["action_url"] = $row['action_url'];
}

//返回数据做成
$rtn_ary = array();
$rtn_ary['errcode'] = '0';
$rtn_ary['errmsg'] = '';
$rtn_ary['week_action'] =$week_rows;
$rtn_ary['all_action'] =$all_action;
$rtn_ary['rows'] = $pout;
$rtn_str = json_encode($rtn_ary);
php_end($rtn_str);

?>
