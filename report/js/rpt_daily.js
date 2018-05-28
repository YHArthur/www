$(function () {
    var url_key = GetQueryString('url_key');
    // 更新并展示网站日报表
    rpt_daily_refresh(url_key);
})

// 更新并展示网站日报表
function rpt_daily_refresh(url_key) {
    var api_url = 'rpt_daily_refresh.php?url_key=' + url_key;
    CallApi(api_url, {}, function (response) {rpt_daily(url_key)}, function (response){rpt_daily(url_key)});
}

// 展示网站日报表
function rpt_daily(url_key) {
    var api_url = 'rpt_daily.php?url_key=' + url_key;
    CallApi(api_url, {}, function (response) {
        $("#url_key").html(url_key);
        $("#rpt_title").html(response.rpt_title);
        $("#daily_sum").html(response.daily_sum);
        $("#rpt_unit").html(response.rpt_unit);
        $("#rpt_chart").html(response.rpt_chart);
    }, function (response) {
        AlertDialog(response.errmsg)
    });
}
