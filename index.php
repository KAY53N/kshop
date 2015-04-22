<?php
define('THINK_PATH', './ThinkPHP/');
define('APP_NAME', 'Shop');
define('APP_PATH', './Shop/');
define('STRIP_RUNTIME_SPACE', true);
define('CACHE_RUNTIME', false);

require(THINK_PATH.'ThinkPHP.php');
App::run();
?>
 