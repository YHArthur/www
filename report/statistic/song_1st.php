<?php
require_once '../inc/common.php';

header("cache-control:no-cache,must-revalidate");
header("Content-Type:text/html;charset=utf-8");

php_begin();

// 第一届金曲大赏
$pj_id = 2;

$db = new DB_STATISTIC();
// 项目名称
$sql = "SELECT * FROM vote_project WHERE pj_id = {$pj_id}";
$db->query($sql);
$row = $db->fetchRow();

$project_str = '<h1>' . $row['pj_name'] . '<br><small>' . date('y年m月d日', strtotime($row['pj_from_time'])) . '-' . date('y年m月d日', strtotime($row['pj_to_time'])) . '</small></h1>';

// 总览
$sql = "SELECT * FROM vote_overview WHERE pj_id = {$pj_id} ORDER BY sort_no";
$db->query($sql);
$rows = $db->fetchAll();
$overview_str = "<dl class='dl-horizontal'>";

foreach ($rows as $row)
{
  $column_name = $row['column_name'];
  $vote_sum = intval($row['vote_sum']);
  $overview_str .= "<dt>$column_name</<dt><dd>$vote_sum</dd>";
}
$overview_str .= "</dl>";

// 投票券统计
$sql = "SELECT * FROM ticket_count WHERE pj_id = {$pj_id} ORDER BY vote_nm";
$db->query($sql);
$rows = $db->fetchAll();
$ticket_str = "<dl class='dl-horizontal'>";

foreach ($rows as $row)
{
  $t_type = $row['t_type'];
  $total_nm = intval($row['total_nm']);
  $use_nm = intval($row['use_nm']);
  $guest_nm = intval($row['guest_nm']);
  $unuse_nm = intval($row['unuse_nm']);
  $ticket_str .= "<dt>$t_type</<dt><dd>总券数：$total_nm 激活券数：$use_nm 未使用券数：$unuse_nm</dd>";
}
$ticket_str .= "</dl>";

// 排行榜统计
$sql = "SELECT * FROM vote_rank WHERE pj_id = {$pj_id} ORDER BY total_rank";
$db->query($sql);
$rows = $db->fetchAll();
$rank_str = "<dl class='dl-horizontal'>";

$board_flg = true;

foreach ($rows as $row)
{
  $option_name = $row['option_name'];
  $total_rank = intval($row['total_rank']);
  $vote_sum = intval($row['vote_sum']);
  $user_sum = intval($row['user_sum']);
  $onboard = intval($row['onboard']);
  
  if ($board_flg && $onboard == 0) {
    $rank_str .= "<h2>----未入围----</h2><hr>";
    $board_flg = false;
  }
  $rank_str .= "<dt>$total_rank ：$option_name</<dt><dd> 得票数：$vote_sum 参与人数：$user_sum</dd>";
}
$rank_str .= "</dl>";

$rtn_str = '';
$rtn_str .= <<<EOF
    
    $project_str
    
    <div id="stat_list">
      <ul class="nav nav-tabs">
        <li class="active"><a class="btn btn-info" href="#overview_tab" data-toggle="tab">总览</a></li>
        <li><a class="btn btn-info" href="#ticket_tab" data-toggle="tab">投票券</a></li>
        <!--<li><a class="btn btn-info" href="#user_tab" data-toggle="tab">用户统计</a></li>
        <li><a class="btn btn-info" href="#trend_tab" data-toggle="tab">投票趋势</a></li>-->
        <li><a class="btn btn-info" href="#rank_tab" data-toggle="tab">排行榜</a></li>
      </ul>
      
      <div class="tab-content" style="padding-top: 10px; font-size:15px;">
        <div class="tab-pane active" id="overview_tab">
          $overview_str
        </div>
        
        <div class="tab-pane" id="ticket_tab">
          $ticket_str
        </div>
        
        <!--<div class="tab-pane" id="user_tab">
        </div>
        
        <div class="tab-pane" id="trend_tab">
        </div>-->
        
        <div class="tab-pane" id="rank_tab">
          $rank_str
        </div>
        
      </div>
      
    </div>
    
EOF;

/*    
$data = array();
$data['title'] = "          万圣节投票排行榜\n";
$data['thead'] = "                <th>排名</th><th>成员姓名</th><th>队伍</th><th>得票数</th>\n";



$data['tbody'] = "";
$i = 0;
foreach ($rows as $row)
{
  $i += 1;
  $sname = $row['sname'];
  $steam = $row['steam'];
  $votes_sum = $row['votes_sum'];
  if (empty($votes_sum))
    $votes_sum = 0;
  $votes_sum = intval($votes_sum / 100);
  
  $data['tbody'] .= "                <tr><td>$i</td><td>$sname</td><td>$steam</td><td>$votes_sum</td></tr>\n";
}
*/

// 输出内容
php_end($rtn_str);

?>
