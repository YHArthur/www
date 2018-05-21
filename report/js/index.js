$(function () {
    // 更新并展示网站概要统计报表
    rpt_overview_refresh();
})

// 更新并展示网站概要统计报表
function rpt_overview_refresh() {
    var api_url = 'rpt_overview_refresh.php';
    CallApi(api_url, {}, function (response) {rpt_overview()}, function (response){rpt_overview()});
}

// 展示网站概要统计报表
function rpt_overview() {
    var api_url = 'rpt_overview.php';
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
                <label class="weui-cell weui-check__label" >\
                  <div class="weui-cell__bd"><a style="text-decoration:none;color:black" href="http://test.fnying.com/report/rpt_detail.php?url_key='+url_key+'">' + rpt_title +'</a></div>\
                  <div class="weui_cell_primary">' + rpt_count + ' ' + rpt_unit +'</div>\
                  <div class="weui-cell__ft"></div>\
                </label>\
                ';
                $("#count_rows").append(count_row);
            });
        }
    }, function (response) {
        console.log(response.errmsg)
    });
}

