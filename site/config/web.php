<?php

use yii\helpers\ArrayHelper;

$params = [];
$params = ArrayHelper::merge($params, require(__DIR__ . '/params.php'));

if (!file_exists(__DIR__ . '/params-local.php')) {
    user_error(
        "Please, copy file `config/params-local.example.php` to `config/params-local.php` and update setting if need.",
        E_USER_ERROR
    );
    die();
}

$params = ArrayHelper::merge($params, require(__DIR__ . '/params-local.php'));
$params = ArrayHelper::merge($params, []);

$config = [
    'id'         => 'web',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'components' => [
        'request'    => [
            'cookieValidationKey' => 'rZf3pnrKNkTMNrN_cTH3jjB6GKLaQHtr',
        ],
        'cache'      => [
            'class' => 'yii\caching\FileCache',
        ],
        'log'        => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
        ],
        'db'         => require(__DIR__ . '/db.php'),
    ],
    'params'     => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    //$config['bootstrap'][]      = 'debug';
    //$config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class'      => \yii\gii\Module::className(),
        'allowedIPs' => ['192.168.*.*']
    ];
}

return $config;
