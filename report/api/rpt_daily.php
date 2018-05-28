<?php
require_once '../inc/common.php';
require_once '../db/rpt_overview.php';
require_once '../db/rpt_period_url_action.php';

header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");

/*
========================== 网页访问日报统计（最近7日） ==========================
GET参数
  url_key         URL关键字

返回
  rpt_title       报告标题
  daily_sum       最近7日总数
  rpt_unit        报告单位
  rpt_chart       报表代码

说明
*/

php_begin();

// 参数检查
$args = array('url_key');
chk_empty_args('GET', $args);

// URL关键字
$url_key =  get_arg_str('GET', 'url_key', 255);

// 取得概要统计报告所有记录
$row = get_rpt_overview_by_url_key($url_key);
if (empty($row))
  exit_error('120', 'URL关键字不存在');

$rpt_title = $row['rpt_title'];
$rpt_unit = $row['rpt_unit'];

$daily_sum = 0;

// 最近7天-访问次数
$chart = new DBChart('rpt_dayil_url_action_count', 'DB_REPORT', 'line');
// 图表标题
$chart->comment = '访问次数';
// 图表副标题
$chart->add_comment = '最近7天';
// 横轴说明
$chart->xaxes_label = '日期';
// 纵轴说明
$chart->yaxes_label = '访问次数';
// 数据来源表
$chart->from_table = 'rpt_period_url_action';
// 数据条件
$from_time = strtotime('-8 day');
$chart->where = "action_url = '{$url_key}' AND rpt_type = 'day' AND from_time_stamp >= '{$from_time}'";
// 横轴字段
$chart->row_column = 'SUBSTR(rpt_title, 6, 5)';
// 纵轴字段
$chart->col_column = 'action_count';
// 数据集设定
$datasets = array();
// 全体
$datasets[] = array('label'=>'访问次数','where'=>'');
$chart->datasets = $datasets;
// 不显示图表标题
$chart->show_title = false;
// 图表输出处理
$rpt_chart = $chart->output();

// 最近7天-访问ID
$chart = new DBChart('rpt_dayil_url_action_id', 'DB_REPORT', 'line');
// 图表标题
$chart->comment = '访问ID';
// 图表副标题
$chart->add_comment = '最近7天';
// 横轴说明
$chart->xaxes_label = '日期';
// 纵轴说明
$chart->yaxes_label = '访问ID数';
// 数据来源表
$chart->from_table = 'rpt_period_url_action';
// 数据条件
$from_time = strtotime('-8 day');
$chart->where = "action_url = '{$url_key}' AND rpt_type = 'day' AND from_time_stamp >= '{$from_time}'";
// 横轴字段
$chart->row_column = 'SUBSTR(rpt_title, 6, 5)';
// 纵轴字段
$chart->col_column = 'id_count';
// 数据集设定
$datasets = array();
// 全体
$datasets[] = array('label'=>'访问ID','where'=>'');
$chart->datasets = $datasets;
// 不显示图表标题
$chart->show_title = false;
// 图表输出处理
$rpt_chart .= $chart->output();

// 返回数据做成
$rtn_ary = array();
$rtn_ary['errcode'] = '0';
$rtn_ary['errmsg'] = '';
$rtn_ary['rpt_title'] = $rpt_title;
$rtn_ary['daily_sum'] = $daily_sum;
$rtn_ary['rpt_unit'] = $rpt_unit;
$rtn_ary['rpt_chart'] = $rpt_chart;

// 正常返回
$rtn_str = json_encode($rtn_ary);
// 输出内容
php_end($rtn_str);
?>
