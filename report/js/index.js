$(function () {
    // 更新网站概要统计报表
    rpt_overview_refresh();
    // 展示网站概要统计报表
    rpt_overview();
})

// 更新网站概要统计报表
function rpt_overview_refresh() {
    var api_url = 'rpt_overview_refresh.php';
    CallApi(api_url, {}, function (response) {}, function (response) {});
}

// 展示网站概要统计报表
function rpt_overview() {
    var api_url = 'rpt_overview.php';
    CallApi(api_url, {}, function (response) {
        var rpt_title, rpt_unit, rpt_count;
        var rows = response.rows;
        if (rows.length > 0) {
            rows.forEach(function(row, index, array) {
                rpt_title = row.rpt_title;
                rpt_count = row.rpt_count;
                rpt_unit  = row.rpt_unit;
                count_row = '\
                <label class="weui-cell weui-check__label" >\
                  <div class="weui-cell__bd">' + rpt_title +'</div>\
                  <div class="weui_cell_primary">' + rpt_count + ' ' + rpt_unit +'</div>\
                </label>\
                ';
                $("#count_rows").append(count_row);
            });
        }
    }, function (response) {
        console.log(response.errmsg)
    });
}   

