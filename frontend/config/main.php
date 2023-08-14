<?php
use common\helpers\UtilHelper;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'frontend\bootstrap\SetUp',
    ],
    'on beforeAction'     => function ( $event ) {
        if ( !Yii::$app->user->isGuest ){
            $identityClass = Yii::$app->user->identityClass;
            $role = $identityClass::findOne( Yii::$app->user->id )->oneAuthAssignment->item_name;
            Yii::$app->params[ 'userRole' ] = $role;

            Yii::$container->set( 'kartik\daterange\DateRangePicker', [
                'containerTemplate' => '
                    <div class="kv-drp-dropdown">
                        <span class="left-ind">{pickerIcon}</span>
                        <input type="text" readonly class="form-control range-value" value="{value}">
                        <span class="right-ind kv-clear" style="" title="' . Yii::t( 'buttons', 'Clear' ) . '">&times;</span>
                        <span class="right-ind"><b class="caret"></b></span>
                    </div>
                    {input}
                ',
            ] );

            UtilHelper::checkAndSetCustomLogo();

        } else {
            Yii::$app->params[ 'userRole' ] = null;
        }

    },
    'controllerNamespace' => 'frontend\controllers',
    'modules'             => [
        'rbac'     => [
            'class'     => 'yii2mod\rbac\Module',
            'as access' => [
                'class' => yii2mod\rbac\filters\AccessControl::class,
            ],
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module',
            // other module settings
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-user',
            'baseUrl' => $baseUrl,
            'cookieValidationKey' => 'sdifdbfshbsnstyrfedwety,mnbvcdsfe',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-site',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // 'view' => [
        //     'theme' => [
        //         'class' => yii\base\Theme::className(),
        //         'basePath' => '@app/themes/orange'    // путь в дир-ию темы
        //     ],
        //     'on ' . \yii\base\View::EVENT_BEFORE_RENDER => function($e){
        //         \Yii::$app->view->registerLinkTag([
        //             'rel' => 'icon',
        //             'type' => 'image/png',
        //             'href' => \yii\helpers\Url::to(['/favicon.ico'])
        //         ]);
        //     }
              
        // ],
        'assetManager' => [
            'assetMap' => [
                'yii2-dynamic-form.js'     => '/js/dynamicform/yii2-dynamic-form.js', //так как тут мои правки
                'yii2-dynamic-form.min.js' => '/js/dynamicform/yii2-dynamic-form.js', //так как тут мои правки
            ],
        ],
        'urlManager' => array_merge([
                'class'                        => 'codemix\localeurls\UrlManager',
                'languages'                    => [ 'en', 'ru' ],
                //'languages'                    => ['en'],
                'enableDefaultLanguageUrlCode' => false,
                'enableLanguagePersistence'    => false,
                'enableLanguageDetection'      => false, //!!!

                'enablePrettyUrl' => true,
                'showScriptName'  => false,
                'rules'           => [
                    ''   => 'site/index', //mainpage
                    'cp' => 'site/cp', //cp
                ],
            ],
            require __DIR__ . '/urlmanager.php'
        ),


        'i18n' => [
            'translations' => [
                '*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    //'sourceLanguage' => 'ru',
                    'fileMap'  => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
