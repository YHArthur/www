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
    <h1 class="page__title" id="title"></h1>
    <div class="page__desc" id="week_amount"></div>
  </div>
  
  <div class="weui-cells__title">统计折线图</div>
  
  <div class="weui-flex">
    <div><div style="width:20px;height=100%" class="placeholder"></div></div>
    <div class="weui-flex__item">
      <div id="count_rows" class="weui-cells">
        <canvas id="myChart" width="80%" height="80%"></canvas>
      </div>
    </div>
    <div><div style="width:20px;height=100%" class="placeholder"></div></div>
  </div> 
  
  <div class="weui-msg__extra-area">©2018 风赢科技</div>

  <script src="https://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
  <script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
  <script src="js/common.js"></script>
  <script src="js/wx.js"></script>
  <script src="js/rpt_detail.js"></script>
 
</body>
</html>
