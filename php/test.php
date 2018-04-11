<?php
require_once 'inc/common.php';

header("cache-control:no-cache,must-revalidate");

// 访问IP
$action_ip = ip2long("0.0.0.1");
var_dump($action_ip);
?>
