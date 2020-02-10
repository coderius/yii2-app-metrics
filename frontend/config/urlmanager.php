<?php
return [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => $baseUrl,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [

               '/' => 'site/index',
               'site/<action>' => 'site/<action>',

                'sitemap.xml' => 'sitemap/index',
                'rss.xml' => 'rss/index',

                
            ],
        ];
?>