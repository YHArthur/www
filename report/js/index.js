window.shareData = {
    // 分享标题
    title: "风赢科技数据统计",
    // 分享描述
    desc: "",
    // 分享链接
    link: window.location.href,
    // 分享图标
    imgUrl: 'http://www.fnying.com/staff/wx/img/share.jpg',
    success: function () {},
    cancel: function () {}
};

$(function () {
    // 展示公司概要统计
    show_rpt_index();
    // 更新并展示网站概要统计报表
    rpt_overview_refresh();
    
    // 微信分享处理
    if (/MicroMessenger/i.test(navigator.userAgent)) {
        $.getScript("https://res.wx.qq.com/open/js/jweixin-1.2.0.js", function () {
            // 微信配置启动
            wx_config();
            wx.ready(function() {
                wx.onMenuShareTimeline(shareData);
                wx.onMenuShareAppMessage(shareData);
            });
        });
    }
})

// 更新并展示网站概要统计报表
function rpt_overview_refresh() {
    var api_url = 'report/api/rpt_overview_refresh.php';
    CallApi(api_url, {}, function (response) {rpt_overview()}, function (response){rpt_overview()});
}

// 前置0
function addPreZero(num, size){
    return ('000000000' + num).slice(-1 * size);
}

// 取得当前时间
function getNowDate() {
    var date = new Date();
    var month = addPreZero(date.getMonth() + 1, 2);
    var day = addPreZero(date.getDate(), 2);
    var hours = addPreZero(date.getHours(), 2);
    var minutes = addPreZero(date.getMinutes(), 2);
    var seconds = addPreZero(date.getSeconds(), 2);

    return date.getFullYear() + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
}

// 展示公司概要统计
function show_rpt_index() {
    var time_str = getNowDate();
    $("#time_now").html(time_str);
    var api_url = 'staff/api/rpt_index.php';
    CallApi(api_url, {}, function (response) {
        // 公司生存时间
        var live_months = response.live_months;
        var death_str = '公司还能生存 ' + live_months + ' 个月';
        $("#time_death").html(death_str);
        window.shareData.desc = time_str + ' ' + death_str;
        // 昨日完成行动数
        var day_closed_actions = response.day_closed_actions;
        $("#day_closed_actions").html(day_closed_actions);
        // 本周完成行动数
        var week_closed_actions = response.week_closed_actions;
        $("#week_closed_actions").html(week_closed_actions);
    }, function (response) {
        AlertDialog(response.errmsg)
    });
}

// 展示网站访问概要统计报表
function rpt_overview() {
    var api_url = 'report/api/rpt_overview.php';
    CallApi(api_url, {}, function (response) {
        var rpt_title, rpt_unit, rpt_count, url_key;
        var rows = response.rows;
        if (rows.length > 0) {
            rows.forEach(function(row, index, array) {
                rpt_title = row.rpt_title;
                rpt_count = row.rpt_count;
                rpt_unit  = row.rpt_unit;
                url_key  = row.url_key;
                count_row = '\
                <a class="weui-cell weui-cell_access" href="rpt_daily.php?url_key=' + url_key + '">\
                    <div class="weui-cell__bd">' + rpt_title + '</div>\
                    <div class="weui-cell__ft">' + rpt_count + ' ' + rpt_unit + '</div>\
                </a>\
                ';
                $("#count_rows").append(count_row);
            });
        }
    }, function (response) {
        AlertDialog(response.errmsg)
    });
}
