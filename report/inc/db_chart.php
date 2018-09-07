<?php
class DBChart {

  // 表相关
  public $db = null;                      // 数据库连接
  public $id = '';                        // 图表ID
  public $type = '';                      // 图表类型
  public $from_table = '';                // 数据来源表
  public $where = '';                     // 数据条件

  // 表示相关
  public $show_title = true;              // 是否显示图表标题和副标题
  public $comment = '';                   // 图表标题
  public $add_comment = '';               // 图表副标题

  // 字段相关
  public $row_column = '';                // 横轴(集计)字段
  public $col_column = '';                // 纵轴(统计)字段
  public $xaxes_label = '';               // 横轴说明
  public $yaxes_label = '';               // 纵轴说明
  public $datasets = array();             // 数据集
  public $labels = array();               // 标签集

  // 大小相关
  public $column_size = 12;               // 图表宽度（12栅格）

  // 代码相关
  public $html= '';                       // Html代码
  public $javascript= '';                 // javascript代码

  // 图表用颜色
  protected $colors = array(
    "51,255,00","102,255,00","153,255,00","204,255,00","255,255,00",
    "255,204,00","255,153,00","255,102,00","255,51,00","255,00,00",
    "255,00,51","255,00,102","255,00,153","255,00,204","255,00,255",
    "204,00,255","153,00,255","102,00,255","51,00,255","00,00,255",
    "00,51,255","00,102,255","00,153,255","00,204","00,255,255",
    "00,255,204","00,255,153","00,255,102","00,255,51","00,255,00"
   );

  //======================================
  // 函数: __construct($id, $db, $type)
  // 功能: 构造函数
  // 参数: $id          图表ID
  // 参数: $db          数据库连接类
  // 参数: $type        图表类型
  // 说明: 初始化数据库图表对象
  //======================================
  public function __construct($id, $db, $type)
  {
    // 图表ID
    $this->id = $id;
    // 数据库链接
    $this->db = new $db();
    // 图表类型
    $this->type = $type;
  }

  //======================================
  // 函数: output()
  // 功能: 图表输出处理
  // 说明:
  //======================================
  public function output() {

    // 默认返回值
    $rtn_str = '';
    // 取得图表的HTML代码
    $this->get_html();

    // 取得图表的javascript代码
    switch ($this->type) {
    case 'pie':
      $this->get_pie_javascript();
      break;
    case 'line':
      $this->get_line_javascript();
      break;
    default:
      break;
    }

    $rtn_str = $this->html;
    $rtn_str .= $this->javascript;
    // 返回数据
    return $rtn_str;
  }

  //======================================
  // 函数: get_html()
  // 功能: 取得图表的HTML代码
  // 说明:
  //======================================
  private function get_html()
  {
    $comment = $this->comment;            // 图表标题
    $add_comment = $this->add_comment;    // 图表注释（副标题）
    $column_size = $this->column_size;    // 图表宽度
    $id = $this->id;                      // 图表ID
    $html_str = '';

    if ($this->show_title) {
      $html_str .= <<<EOF

      <h1>
        $comment
        <small class="text-muted">{$add_comment}</small>
      </h1>
EOF;
    }

    $html_str .= <<<EOF

    <div class="col-lg-{$column_size}">
        <canvas id="{$id}"></canvas>
    </div>

EOF;

    $this->html = $html_str;
  }


  //======================================
  // 函数: get_pie_javascript()
  // 功能: 取得饼图的javascript代码
  // 说明:
  //======================================
  private function get_pie_javascript()
  {
    $type = $this->type;                  // 图表类型
    $comment = $this->comment;            // 图表标题
    $add_comment = $this->add_comment;    // 图表注释（副标题）
    $id = $this->id;                      // 图表ID

    $from_table = $this->from_table;      // 数据来源表
    $row_column = $this->row_column;      // 横轴(集计)字段
    $col_column = $this->col_column;      // 纵轴(统计)字段
    $datasets = $this->datasets;          // 数据集
    $labels = $this->labels;              // 标签集

    $db = $this->db;

    // 设置标签
    $label_list = array();
    foreach ($labels as $key=>$value) {
        $label_list[] = '"' . $value . '"';
    }
    $labs = implode(',', $label_list);

    // 设置颜色
    $color_list = array();
    $colors = $this->get_colors(count($label_list));
    foreach ($colors as $color) {
        $color_list[] = '"rgba(' . $color . ',1)"';
    }
    $clrs = implode(',', $color_list);

    // 设置数据集初始值
    $count = count($datasets);
    for($i = 0; $i < $count; $i++) {
      $data_list[$i] = array();
      foreach ($labels as $key=>$value)
        $data_list[$i][$key] = 0;
    }

    // 设置数据集
    for($i = 0; $i < $count; $i++) {
      $data_where = $datasets[$i]['where'];
      $sql = "SELECT {$row_column} AS xval, {$col_column} AS yval FROM {$from_table}";
      $sql .= ($data_where ? " WHERE " . $data_where : "");
      $sql .= " GROUP BY xval";
      // var_dump($sql);
      $db->query($sql);
      $rows = $db->fetchAll();

      foreach ($rows as $row)
      {
        $xval = $row['xval'];
        $data_list[$i][$xval] = $row['yval'];
      }

      $datasets[$i]['data'] = implode(',', $data_list[$i]);
    }

    $js_str = <<<EOF

    <script>

    (function(){
        var ctx = document.getElementById("{$id}").getContext('2d');

        var config = {
            type: '{$type}',
            data: {
                datasets: [

EOF;
    for($i = 0; $i < $count; $i++)
    {
      $js_str .= '                  {' . "\n";
      $js_str .= '                    backgroundColor : [' . $clrs . ']' . ",\n";
      $js_str .= '                    data: [' . $datasets[$i]['data'] . ']' . "\n";
      if ($i != $count - 1) {
        $js_str .= '                  },' . "\n";
      } else {
        $js_str .= '                  }' . "\n";
      }
    }

    $js_str .= <<<EOF
                ],
                labels: [{$labs}]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: '{$comment} - {$add_comment}'
                }
            }
        };

        var MyChart = new Chart(ctx, config);

    })();

    </script>

EOF;

    $this->javascript = $js_str;
  }

  //======================================
  // 函数: get_line_javascript()
  // 功能: 取得折线图表的javascript代码
  // 说明:
  //======================================
  private function get_line_javascript()
  {
    $type = $this->type;                  // 图表类型
    $comment = $this->comment;            // 图表标题
    $add_comment = $this->add_comment;    // 图表注释（副标题）
    $id = $this->id;                      // 图表ID
    $xaxes_label = $this->xaxes_label;    // 横轴说明
    $yaxes_label = $this->yaxes_label;    // 纵轴说明

    $from_table = $this->from_table;      // 数据来源表
    $where = $this->where;                // 数据条件
    $row_column = $this->row_column;      // 横轴字段
    $col_column = $this->col_column;      // 纵轴字段
    $datasets = $this->datasets;          // 数据集

    $db = $this->db;

    // 横坐标标签取得
    $sql = "SELECT {$row_column} AS xval FROM {$from_table}";
    $sql .= " WHERE " . $where;
    $sql .= " GROUP BY xval";

    $db->query($sql);
    $rows = $db->fetchAll();

    // 设置labels
    $lbs = array();
    $data_list = array();
    $count = count($datasets);
    foreach ($rows as $row)
    {
      $item = $row['xval'];
      $data_list[$item] = array();
      for($i = 0; $i < $count; $i++)
        $data_list[$item][$i] = 0;
      $lbs[] = $item;
    }

    $labels = json_encode($lbs);

    // 设置数据集
    for($i = 0; $i < $count; $i++) {
      $data_where = $datasets[$i]['where'];
      $sql = "SELECT {$row_column} AS xval, {$col_column} AS yval FROM {$from_table}";
      $sql .= " WHERE " . $where;
      $sql .= ($data_where ? " AND " . $data_where : "");
      $sql .= " GROUP BY xval";
      $db->query($sql);
      $rows = $db->fetchAll();

      foreach ($rows as $row)
      {
        $xval = $row['xval'];
        $data_list[$xval][$i] = $row['yval'];
      }

      $dataset = array();
      foreach ($data_list as $data)
      {
        $dataset[] = $data[$i];
      }
      $datasets[$i]['data'] = implode(',', $dataset);
    }


    $js_str = <<<EOF

    <script>

    (function(){
        var ctx = document.getElementById("{$id}").getContext('2d');

        var config = {
            type: '{$type}',
            data: {
                labels: {$labels},
                datasets: [

EOF;

    // 取得数据需要的颜色数组
    $colors = $this->get_colors($count);
    for($i = 0; $i < $count; $i++)
    {
      $js_str .= '                  {' . "\n";
      $js_str .= '                    label: "' . $datasets[$i]['label'] . '",' . "\n";
      $js_str .= '                    fill: false,' . "\n";
      $js_str .= '                    backgroundColor : "rgba(' . $colors[$i] . ',0.4)",' . "\n";
      $js_str .= '                    borderColor : "rgba(' . $colors[$i] . ',1)",' . "\n";
      $js_str .= '                    pointBorderColor: "rgba(' . $colors[$i] . ',1)",' . "\n";
      $js_str .= '                    pointHoverBackgroundColor: "rgba(' . $colors[$i] . ',1)",' . "\n";
      $js_str .= '                    data: [' . $datasets[$i]['data'] . ']' . "\n";
      if ($i != $count - 1) {
        $js_str .= '                  },' . "\n";
      } else {
        $js_str .= '                  }' . "\n";
      }
    }

    $js_str .= <<<EOF

                ]
            },
            options: {
                responsive: true,
                tooltips: {
                    mode: 'label',
                },
                legend: {
                    position: 'bottom',
                },
                hover: {
                    mode: 'dataset'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '{$xaxes_label}'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '{$yaxes_label}'
                        }
                    }]
                },
                title: {
                    display: true,
                    text: '{$add_comment} - {$comment}'
                }
            }
        };

        var MyChart = new Chart(ctx, config);

    })();

    </script>

EOF;

    $this->javascript = $js_str;
  }

  // 取得若干（$num）图标用颜色
  private function get_colors($num) {
    $rtn_colors = array();
    $base = floor(count($this->colors) / ($num + 1));
    for ($i = 1; $i <= $num; $i++) {
      $rtn_colors[$i-1] = $this->get_rgb($base * $i);
    }
    return $rtn_colors;
  }

  // 取得颜色的RGB值（返回30安全色中的一种颜色）
  private function get_rgb($idx) {
    return $this->colors[$idx - 1];
  }

  // 取得某颜色的高亮
  private function get_highlight($color) {
    $hidex = array_keys($this->colors, $color);
    $num = $hidex[0] + 2;
    if ($num > 29)
      $num -= 29;
    return $this->get_rgb($num + 1);
  }

}
?>
