<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'flushInterval' => 1,            
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                /*
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                */
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'info', 'trace'],
                    'except' => [
                        'yii\web\Session::open',
                        'yii\db\Command::query',//SQL-Ausgabe in Logs
                        'yii\db\Connection::open',
                        'yii\web\UrlManager::parseRequest',//welche Route gefunden wird
                        'yii\web\UrlRule::parseRequest',//welche URLmanager-Rule gefunden wird
                        'yii\base\Controller::runAction'.//welche action gefunden wird
                        'yii\base\Controller::runAction',
                        'yii\base\Controller::runAction',
                        'yii\base\Action::runWithParams',
                        'yii\base\InlineAction::runWithParams',//welche action ausgeführt wird
                        'yii\base\Module::getModule',
                        'yii\web\Application::handleRequest',
                        'yii\base\Application::bootstrap',
                        'yii\base\View::renderFile',
                    ],
                    'exportInterval' => 1,
                    'logFile' => '@runtime/logs/yiiharburg.log',
                    'logVars' => [],
                    //'logVars' => ['_SERVER'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
