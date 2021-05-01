<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    // set target language
    'language' => 'de-DE',
    // set source language
    'sourceLanguage' => 'en-EN',    
    'components' => [
        'errorHandler' => [
            'errorAction' => 'page/error',
        ],
        'db' => $db,
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'viewPath' => '@app/mail',
            'htmlLayout' => '@app/mail/layouts/html',
            'textLayout' => false,
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
        'assetManager' => [
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'scss' => ['css', 'L:\Tools\Ruby26-x64\bin\sass {from} {to}']
                ],
            ],            
            'bundles' => [
                //'yii\web\JqueryAsset' => false,
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
                    ]
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/'=>'page/index',
                '<controller:\w+>/<action>/<p>/<p2>/<p3>/<p4>'=>'<controller>/<action>',
                '<controller:\w+>/<action>/<p>/<p2>/<p3>'=>'<controller>/<action>',
                '<controller:\w+>/<action>/<p>/<p2>'=>'<controller>/<action>',
                '<controller:\w+>/<action>/<p>'=>'<controller>/<action>',
                '<controller:\w+>/<action>'=>'<controller>/<action>',
                '<controller:\w+>/<p:\d+>'=>'<controller>/view',
                //'<controller:\w+>'=>'<controller>/index',
                '<p>'=>'page/index',
            ],
        ],
        'i18n' => [
            'translations' => [
                'errors*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'errors' => 'errors.php',
                    ],
                ],
            ],
        ], 
        'formatter' => [ 
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd.MM.Y',
            'datetimeFormat' => 'dd.MM.Y HH:mm:ss',
            'timeFormat' => 'HH:mm:ss', 
            'locale' => 'de-DE',
            'defaultTimeZone' => 'Europe/Berlin',
        ],
         'user' => [
            'identityClass' => 'app\models\user\User',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params' => $params,
];
