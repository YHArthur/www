<?php
// 更新投票项目一览表总票数
function update_vote_project($pj_id) {
  // 调用API获取投票项目一览表总票数
  $db = new DB_STATISTIC();
  $apidata = Api::get_vote_project($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $vote_sum = json_decode($rtn_array);
    if ($vote_sum) {
      $sql = "UPDATE vote_project SET vote_sum = " . intval($vote_sum * 10) / 10 . " WHERE pj_id = {$pj_id}";
      $db->query($sql);
    }
  }
}

// 更新总览表
function update_vote_overview($pj_id) {
  // 调用API获取投票项目总览数据
  $db = new DB_STATISTIC();
  $apidata = Api::get_vote_overview($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array);
    if ($rows) {
      foreach ($rows as $key => $value) {
        $sql = "UPDATE vote_overview SET vote_sum = " . intval($value * 10) / 10 . " WHERE column_name = '" . $key . "' AND pj_id = {$pj_id}";
        $db->query($sql);
      }
    }
  }
}

// 更新总览表增长情况
function update_vote_overview_rise($pj_id, $bef_pj_id) {
  $db = new DB_STATISTIC();
  $sql = "SELECT column_name, vote_sum FROM vote_overview WHERE pj_id = {$pj_id}";
  $db->query($sql);
  $rows = $db->fetchAll();
  foreach ($rows as $row) {
    // 取得上次数量
    $sql = "SELECT vote_sum FROM vote_overview WHERE column_name = '" . $row['column_name'] . "' AND pj_id = {$bef_pj_id}";
    $bef_vote_sum = $db->getField($sql, 'vote_sum');
    if (is_null($bef_vote_sum))
      continue;
    $rise = intval(($row['vote_sum'] - $bef_vote_sum) * 100 / $bef_vote_sum);
    $sql = "UPDATE vote_overview SET rise_nm = {$rise} WHERE column_name = '" . $row['column_name'] . "' AND pj_id = {$pj_id}";
    $db->query($sql);
  }
}

// 更新投票券统计
function update_vote_ticket_count($pj_id) {
  // 调用API获取投票券统计数据
  $db = new DB_STATISTIC();
  $apidata = Api::get_vote_ticket_count($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array, true);
    if ($rows) {
      foreach ($rows as $key => $value) {
        $sql = "UPDATE ticket_count SET use_nm = {$value}, unuse_nm = total_nm - {$value} WHERE t_type = '" . $key . "' AND pj_id = {$pj_id}";
        $db->query($sql);
      }
    }
  }
}

// 更新电子券统计
function update_vote_e_ticket_count($pj_id) {
  // 调用API获取电子券统计数据
  $db = new DB_STATISTIC();
  $apidata = Api::get_vote_e_ticket_count($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array, true);
    if ($rows) {
      foreach ($rows as $row) {
        $sell_count = $row['sell_count'];
        $sell_sum = $row['sell_sum'];
        if (empty($sell_count)) $sell_count = 0;
        if (empty($sell_sum)) $sell_sum = 0;
        $sql = "UPDATE e_ticket_count SET sell_count = " . $sell_count . ", sell_sum = " . $sell_sum . " WHERE e_type = '" . $row['type_name'] . "' AND pj_id = {$pj_id}";
        $db->query($sql);
      }
    }
  }
}

// 更新用户统计
function update_vote_user_count($pj_id) {
  // 调用API获取投票项目用户统计数据
  $db = new DB_STATISTIC();
  $apidata = Api::get_vote_user_count($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array, true);
    if ($rows) {
      foreach ($rows as $row) {
        $user_sum = $row['user_sum'];
        if (is_null($user_sum)) $user_sum = 0;
        $sql = "UPDATE user_count SET user_count = " . $row['user_count'] . ", user_sum = " . $user_sum . " WHERE u_type = '" . $row['u_type'] . "' AND pj_id = {$pj_id}";
        $db->query($sql);
      }
    }
  }
}

// 更新B50金曲榜
function update_vote_song_rank($pj_id, $board_nm) {
  // 调用API获取投票项目金曲榜
  $db = new DB_STATISTIC();
  $option_type = 1;
  $apidata = Api::get_vote_song_rank($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array, true);
    if ($rows) {
      // 删除原有金曲榜数据
      $sql = "DELETE FROM vote_rank WHERE option_type = {$option_type} AND pj_id = {$pj_id}";
      $db->query($sql);
      $rank = 0;
      $insert_sql = array();
      foreach ($rows as $row) {
        $rank ++;
        // 排行榜SQL插入语句设置
        $option_name = addslashes($row['option_name']);
        $vote_sum = $row['vote_sum'];
        $user_sum = $row['user_sum'];
        $item_data = addslashes($row['item_data']);
        $onboard = 1;
        if ($rank > $board_nm)
          $onboard = 0;
        $insert_sql[] = "({$pj_id},{$option_type},'{$option_name}',{$rank},{$vote_sum},{$user_sum},'{$item_data}',{$onboard})";
      }
      $sql = "INSERT INTO vote_rank(pj_id, option_type, option_name, total_rank, vote_sum, user_sum, item_data, onboard) VALUES ";
      $sql .= implode(',', $insert_sql);
      // var_dump($sql);
      $db->query($sql);
    }
  }
}

// 更新风尚排行榜
function update_vote_show_rank($pj_id, $board_nm) {
  // 调用API获取风尚排行榜
  $db = new DB_STATISTIC();
  $option_type = 1;
  $apidata = Api::get_vote_show_rank($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array, true);
    if ($rows) {
      // 删除原有风尚排行榜
      $sql = "DELETE FROM vote_rank WHERE option_type = {$option_type} AND pj_id = {$pj_id}";
      $db->query($sql);
      $rank = 0;
      $insert_sql = array();
      foreach ($rows as $row) {
        $rank ++;
        // 排行榜SQL插入语句设置
        $option_name = addslashes($row['option_name']);
        $vote_sum = $row['vote_sum'];
        $user_sum = $row['user_sum'];
        $item_data = addslashes($row['item_data']);
        $onboard = 1;
        if ($rank > $board_nm)
          $onboard = 0;
        $insert_sql[] = "({$pj_id},{$option_type},'{$option_name}',{$rank},{$vote_sum},{$user_sum},'{$item_data}',{$onboard})";
      }
      $sql = "INSERT INTO vote_rank(pj_id, option_type, option_name, total_rank, vote_sum, user_sum, item_data, onboard) VALUES ";
      $sql .= implode(',', $insert_sql);
      // var_dump($sql);
      $db->query($sql);
    }
  }
}

// 更新成员榜
function update_vote_member_rank($pj_id, $board_nm) {
  // 调用API获取投票项目成员榜
  $db = new DB_STATISTIC();
  $option_type = 0;
  $apidata = Api::get_vote_member_rank($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array, true);
    if ($rows) {
      // 删除原有成员榜数据
      $sql = "DELETE FROM vote_member_rank WHERE pj_id = {$pj_id}";
      $db->query($sql);
      $rank = 0;
      $insert_sql = array();
      foreach ($rows as $row) {
        $rank ++;
        // 排行榜SQL插入语句设置
        $sid = $row['sid'];
        $gid = $row['gid'];
        $tid = $row['tid'];
        $sname = $row['sname'];
        $vote_sum = $row['vote_sum'];
        $user_sum = $row['user_sum'];
        $onboard = 1;
        if ($rank > $board_nm)
          $onboard = 0;
        $insert_sql[] = "({$pj_id},{$sid},{$gid},{$tid},'{$sname}',{$rank},{$vote_sum},{$user_sum},{$onboard})";
      }
      $sql = "INSERT INTO vote_member_rank(pj_id, sid, gid, tid, sname, total_rank, vote_sum, user_sum, onboard) VALUES ";
      $sql .= implode(',', $insert_sql);
      // var_dump($sql);
      $db->query($sql);
    }
  }
}

// 更新队歌排行榜
function update_vote_team_song_rank($pj_id, $board_nm) {
  // 调用API获取投票项目组合榜
  $db = new DB_STATISTIC();
  $option_type = 2;
  $apidata = Api::get_vote_team_song_rank($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array, true);
    if ($rows) {
      // 删除原有组合榜数据
      $sql = "DELETE FROM vote_rank WHERE option_type = {$option_type} AND pj_id = {$pj_id}";
      $db->query($sql);
      $rank = 0;
      $insert_sql = array();
      foreach ($rows as $row) {
        $rank ++;
        // 排行榜SQL插入语句设置
        $option_name = addslashes($row['option_name']);
        $vote_sum = $row['vote_sum'];
        $user_sum = $row['user_sum'];
        $item_data = addslashes($row['item_data']);
        $onboard = 1;
        if ($rank > $board_nm)
          $onboard = 0;
        $insert_sql[] = "({$pj_id},{$option_type},'{$option_name}',{$rank},{$vote_sum},{$user_sum},'{$item_data}',{$onboard})";
      }
      $sql = "INSERT INTO vote_rank(pj_id, option_type, option_name, total_rank, vote_sum, user_sum, item_data, onboard) VALUES ";
      $sql .= implode(',', $insert_sql);
      // var_dump($sql);
      $db->query($sql);
    }
  }
}

// 更新组合榜
function update_group_rank($pj_id, $board_nm) {
  // 调用API获取投票项目组合榜
  $db = new DB_STATISTIC();
  $option_type = 3;
  $apidata = Api::get_vote_group_rank($pj_id);
  if ($apidata->IsSuccess()) {
    $rtn_array = $apidata->GetData();
    $rows = json_decode($rtn_array, true);
    if ($rows) {
      // 删除原有组合榜数据
      $sql = "DELETE FROM vote_rank WHERE option_type = {$option_type} AND pj_id = {$pj_id}";
      $db->query($sql);
      $rank = 0;
      $insert_sql = array();
      foreach ($rows as $row) {
        $rank ++;
        // 排行榜SQL插入语句设置
        $option_name = addslashes($row['option_name']);
        $vote_sum = $row['vote_sum'];
        $user_sum = $row['user_sum'];
        $item_data = addslashes($row['item_data']);
        $onboard = 1;
        if ($rank > $board_nm)
          $onboard = 0;
        $insert_sql[] = "({$pj_id},{$option_type},'{$option_name}',{$rank},{$vote_sum},{$user_sum},'{$item_data}',{$onboard})";
      }
      $sql = "INSERT INTO vote_rank(pj_id, option_type, option_name, total_rank, vote_sum, user_sum, item_data, onboard) VALUES ";
      $sql .= implode(',', $insert_sql);
      // var_dump($sql);
      $db->query($sql);
    }
  }
}
?>
