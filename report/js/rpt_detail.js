$(function () {
  // 最近七日访问统计展示
  rpt_detail();
  })
var url_key = GetQueryString('url_key');
var  title = "";
function rpt_detail() {
  var api_url = 'rpt_detail.php';
  post_data = {"url_key":url_key};
  CallApi(api_url, post_data, function (response) {
    //返回的每日浏览量数组
    var rows = response.rows;
    //返回的一周浏览量统计
    var week_amount ='最近一周访问总数：' + response.week_amount;
    $('#week_amount').text(week_amount);
    
    //返回的当前报表所有浏览记录
    var all_action = response.all_action;
    //标题
    title = rows['action_url']; 
    $("#title").text(title);
    var data =[rows[6]['action_count'],rows[5]['action_count'],rows[4]['action_count'],rows[3]['action_count'],rows[2]['action_count'],rows[1]['action_count'],rows[0]['action_count']];
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [rows[6]['rpt_title'], rows[5]['rpt_title'], rows[4]['rpt_title'], rows[3]['rpt_title'],rows[2]['rpt_title'], rows[1]['rpt_title'],rows[0]['rpt_title']],
      datasets: [{
      label: title + '近七日访问统计报表',
      borderColor: 'rgb(54,162,235)',
      fill: false,
      borderDash: [5, 5],
      pointRadius: 10,
      data: data,
      }]
    },
    options: {
    responsive: true,
    legend: {
       position: 'bottom',
    },
    hover: {
    mode: 'index'
    },
    scales: {
      xAxes: [{
        display: true,
        scaleLabel: {
          display: true,
          labelString: '日期'
      }
      }],
      yAxes: [{
        display: true,
        scaleLabel: {
          display: true,
          labelString: '访问量'
        }
      }]
    },
      title: {
        display: true,

      }
    }
});


}, function (response) {
  console.log(response.errmsg)
  });
}
