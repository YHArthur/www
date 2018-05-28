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
        var rpt_title, rpt_unit, rpt_count, url_key;
        var rows = response.rows;
        if (rows.length > 0) {
            rows.forEach(function(row, index, array) {
                rpt_title = row.rpt_title;
                rpt_count = row.rpt_count;
                rpt_unit  = row.rpt_unit;
                url_key  = row.url_key;
                count_row = '\
                <a class="weui-cell weui-cell_access" href="rpt_detail.php?url_key=' + url_key + '">\
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
