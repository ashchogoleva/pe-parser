<?php

defined('YII_DEBUG') or define('YII_DEBUG', isset($_ENV['YII_DEBUG']) ? $_ENV['YII_DEBUG'] : true);
defined('YII_ENV') or define('YII_ENV', isset($_ENV['YII_ENV']) ? $_ENV['YII_ENV'] : 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new \app\components\WebApplication($config))->run();
