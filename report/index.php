<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
  <title>风赢科技数据统计平台</title>
  <link rel="stylesheet" href="css/weui.css">
  <link rel="stylesheet" href="css/index.css">
</head>
<body>

  <div class="page__hd">
    <h1 class="page__title">统计概要</h1>
    <p class="page__desc">
      <span id="time_now"></span>
      <br>
      <span id="time_death"></span>
    </p>
  </div>

  <div class="weui-cells__title">完成行动数量</div>
  <div id="action_rows" class="weui-cells">
    <a class="weui-cell weui-cell_access" href="../staff/wx/action_daily_report.php?day=1">
      <div class="weui-cell__bd">昨日完成行动总数</div>
      <div class="weui-cell__ft"><span id="day_closed_actions"></span> 件</div>
    </a>

    <a class="weui-cell weui-cell_access" href="../staff/wx/action_weekly_report.php?week=0">
      <div class="weui-cell__bd">本周完成行动总数</div>
      <div class="weui-cell__ft"><span id="week_closed_actions"></span> 件</div>
    </a>
  </div>

  <div class="weui-cells__title">最近24小时访问</div>
  <div id="count_rows" class="weui-cells"></div>

  <div class="weui-msg__extra-area"><a href="../staff/h5_menu.php">风赢科技</a></div>

  <script src="https://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
  <script src="js/common.js"></script>
  <script src="../staff/wx/js/wx.js"></script>
  <script src="js/index.js"></script>

</body>
</html>
