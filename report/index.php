<?php

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no" />
  <meta name="description" content="">
  <meta name="author" content="">

  <title>风赢科技-统计报表</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/index.css">
</head>

<body>

  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menubar" aria-expanded="true" aria-controls="navbar" onclick="javascript:scroll(0,0)">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">统计报表</a>
      </div>
      <div id="h-navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><?php echo $staffname?></a></li>
          <li><a href="logout.php">退出</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!--左侧导航-->
      <div class="col-sm-3 col-md-2 sidebar panel-group" id="menubar">
            <ul class="nav nav-sidebar">
              <li><a href="javascript:;" onclick="menu_click('statistic','www')">风赢科技官网</a></li>
              <li><a href="javascript:;" onclick="menu_click('statistic','hivebanks')">蜂巢项目官网</a></li>
              <li><a href="javascript:;" onclick="menu_click('statistic','weixin')">微信公众平台</a></li>
            </ul>
      </div>

      <!--右侧内容-->
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <!--状态-->
        <div id="main_status">
        </div>
      </div>

    </div>

  </div>

  <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
  <script src="http://libs.baidu.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="http://cdn.bootcss.com/Chart.js/2.1.6/Chart.bundle.min.js"></script>
  <script src="js/index.js"></script>

  <script type="text/javascript">

  $(function () {
    $('.collapsed').click();
  });
  
  </script>
  
</body>
</html>
