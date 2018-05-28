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
    <h1 class="page__title" id="title">日报-<span id='url_key'></span></h1>
    <p class="page__desc">最近7天访问：<span id='daily_sum'></span></p>
  </div>
  <div class="weui-flex">
    <div class="placeholder">&nbsp;</div>
    <div class="weui-flex__item">
      <div id="rpt_chart"><canvas id="myChart" width="100%" height="80%"></canvas></div>
    </div>
    <div class="placeholder">&nbsp;</div>
  </div>

  <div class="weui-msg__extra-area">©2018 风赢科技</div>

  <script src="https://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
  <script src="js/Chart.bundle.min.js"></script>
  <script src="js/common.js"></script>
  <script src="js/rpt_daily.js"></script>

</body>
</html>

