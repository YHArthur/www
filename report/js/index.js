$(function () {
    // 展示公司死亡时间
    show_death_limit();
    // 更新并展示网站概要统计报表
    rpt_overview_refresh();
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

// 展示公司死亡时间
function show_death_limit() {
    var html_str = getNowDate();
    var api_url = 'staff/api/death_limit.php';
    CallApi(api_url, {}, function (response) {
        var live_months = response.live_months;
        html_str += '<br>公司还能生存 ' + live_months + ' 个月';
        $(".page__desc").html(html_str);
    }, function (response) {
        $(".page__desc").html(html_str);
        AlertDialog(response.errmsg)
    });
}

// 展示网站概要统计报表
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
