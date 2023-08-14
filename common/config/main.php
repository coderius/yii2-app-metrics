<?php

use yii\web\Request;

$baseUrl = str_replace('/frontend/web', '', (new Request())->getBaseUrl());
$baseUrl = str_replace('/backend/web', '', $baseUrl);

return [
    'aliases' => require(__DIR__.'/aliases.php'),
    'name' => 'Masters site',
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Kiev',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'myRequest' => [
            'class' => 'common\components\web\Request', //мой компонент
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\user\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-user',
                'httpOnly' => true],
        ],
        'authManager' => [
            'class'        => 'yii\rbac\DbManager',
            'defaultRoles' => [ 'guest', 'user' ],
        ],
        'cache'       => [
            'class' => 'yii\caching\FileCache',
        ],
        // перевод
        'i18n'        => [
            'translations' => [
                '*'            => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'basePath'       => '@common/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'ru-RU',
                ],
                'yii2mod.rbac' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/rbac/messages',
                ],
            ],
        ],
        //формат даты
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru-RU', //язык русский
            // 'defaultTimeZone' => 'UTC', //точка отсчета
            'defaultTimeZone'   => 'Europe/Kiev',
            'timeZone'          => 'Europe/Kiev',
//            'timeZone' => Yii::$app->user->isGuest ? 'UTC' : Yii::$app->user->identity->timezone,

            //'dateFormat' => 'd MMMM yyyy',//как месяц
            'dateFormat' => 'dd.MM.yyyy', // как число'decimalSeparator'  => '.',
            'thousandSeparator' => '',
            'currencyCode'      => 'USD',

        ],

        'geoip'       => [ 'class' => 'coderius\geoIp\GeoIP' ],
        

        //для ссылок в админки во фронт и на оборот
        'urlManagerFrontend' => require(dirname(dirname(__DIR__)).'/frontend/config/urlmanager.php'),
        'urlManagerBackend' => require(dirname(dirname(__DIR__)).'/backend/config/urlmanager.php'),

    ],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
        //  'allowedIPs' => ['127.0.0.1', '::1']
        ],
    ],
    'bootstrap' => [
        'common\components\events\EventBootstrap',
    ],
];
