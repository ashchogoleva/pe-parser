<?php
defined('YII_DEBUG') or define('YII_DEBUG', isset($_ENV['YII_DEBUG']) ? $_ENV['YII_DEBUG'] : true);
if (YII_DEBUG) {
    phpinfo();
}
