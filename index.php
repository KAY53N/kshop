<?php
header('Content-type:text/html; charset=utf-8');
define('THINK_PATH', './TP/');
define('APP_NAME', 'Shop');
define('APP_PATH', './Shop/');
define('RUNTIME_PATH', './BBAFE593_CACHE/');
define('CACHE_RUNTIME', false);
define('STRIP_RUNTIME_SPACE', false);
require(THINK_PATH.'ThinkPHP.php');

App::run(); 