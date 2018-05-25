<?php
//======================================
// 函数: 取得符合关键字的近期网址访问记录的数量
// 参数: $url_key       URL关键字
// 返回: 符合条件的记录数量
//======================================
function get_recent_url_action_by_key($url_key)
{
  // 最近24小时
  $recent_time = time() - 24 * 60 * 60;
  $db = new DB_WWW();

  $sql = "SELECT count(logid) as logid_count FROM cnt_url_action WHERE action_url like '%{$url_key}%' AND action_time > $recent_time";
  $logid_count = $db->getField($sql, 'logid_count');
  // if (is_null($logid_count)) $logid_count = 0;
  return $logid_count;
}

//======================================
// 函数: 创建网址访问记录
// 参数: $data          信息数组
// 返回: id             新的记录ID
// 返回: 0              创建失败
//======================================
function ins_cnt_url_action($data)
{
  // 提交时间
  $data['action_time'] = time();

  $db = new DB_WWW();

  $sql = $db->sqlInsert("cnt_url_action", $data);
  $q_id = $db->query($sql);

  if ($q_id == 0)
    return 0;
  return $db->insertID();
}


//======================================
// 函数: 获取总访问量
// 参数: $url_key          url关键字
// 返回: logid_count       总访问数
// //======================================
function get_all_action($url_key)
{
  $db = new DB_WWW();

  $sql = "SELECT count(logid) as logid_count  FROM cnt_url_action WHERE action_url like '%{$url_key}%'";
  $logid_count = $db->getField($sql, 'logid_count');
  return $logid_count;
}

//======================================
// 函数: 查询$url_key相似的数据统计
// 参数: $url_key           访问网址url_key
//       $begin_time        查询起始时间
//       $end_time          查询结束时间
//返回： 数组                总访问量以及总访问id(去重)
//         $url_count       总访问量(去重)
//         $id_count        总访问id量(去重)
//======================================

function serch_rpt_detail($url_key, $begin_time,$end_time)
{
  $db = new DB_WWW();
  $sql_url = "SELECT count(DISTINCT action_url) as url_count FROM cnt_url_action WHERE action_url like '%{$url_key}%' AND action_time >= '{$begin_time}' AND action_time <= '{$end_time}'";
  $sql_id = "SELECT count(DISTINCT action_id) as id_count FROM cnt_url_action WHERE action_url like '%{$url_key}%' AND action_time >= '{$begin_time}' AND action_time <= '{$end_time}'";
  $url_count = $db->getField($sql_url, 'url_count');
  $id_count = $db->getField($sql_id, 'id_count');
  return array($url_count,$id_count);
}

?>
