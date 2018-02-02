<?php

// 设置脚本不超时
define('GUARD', true);

header('Content-Type: application/json; charset=utf-8');
exit(json_encode(include 'config.php'));