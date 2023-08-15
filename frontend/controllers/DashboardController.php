<?php

namespace frontend\controllers;

use common\helpers\UserBanHelper;
use common\models\user\User;
use DateTime;
use DateTimeZone;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii2mod\rbac\filters\AccessControl;

/**
 * Class DashboardController
 * @package backend\controllers
 */
class DashboardController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                [
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => [
                        'ROLE_METRIC_CLIENT',
                    ],
                ],

                [
                    'actions' => ['admin-index'],
                    'allow' => true,
                    'roles' => [
                        'ROLE_METRIC_ADMIN',
                    ],
                ],

                [
                    'actions' => ['own-index'],
                    'allow' => true,
                    'roles' => [
                        'ROLE_METRIC_OWNER',
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function beforeAction( $action )
    {
        if ( UserBanHelper::checkUser() ){

            $this->redirect( [ '/site/banned-user' ] );
            Yii::$app->end();
        }

        if( in_array($action , ['index', 'admin-index', 'own-index'])){
            if ( in_array( Yii::$app->params[ 'userRole' ], [User::ROLE_METRIC_DEVELOPER, User::ROLE_METRIC_WEBMASTER] ) ){
                return $this->redirect( [ '/site/index' ] );
            }
        }

        return parent::beforeAction( $action );
    }


    // TODO Part --------------------
    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $view = 'index';
        
        $dateToday = (new DateTime( 'NOW', new DateTimeZone( Yii::$app->params[ 'timezoneOffset' ] ) ))->format( 'Y-m-d' );

        return $this->render( $view, [
            'dateFrom' => $dateToday,
            'dateTo'   => $dateToday,
            'notShow'  => true,
        ] );
    }


    /**
     * @return string
     * @throws \Exception
     */
    public function actionAdminIndex()
    {
        $view = 'admin-index';
        
        $dateToday = (new DateTime( 'NOW', new DateTimeZone( Yii::$app->params[ 'timezoneOffset' ] ) ))->format( 'Y-m-d' );

        return $this->render( $view, [
            'dateFrom' => $dateToday,
            'dateTo'   => $dateToday,
            'notShow'  => true,
        ] );
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionOwnIndex()
    {
        $view = 'own-index';
        
        $dateToday = (new DateTime( 'NOW', new DateTimeZone( Yii::$app->params[ 'timezoneOffset' ] ) ))->format( 'Y-m-d' );

        return $this->render( $view, [
            'dateFrom' => $dateToday,
            'dateTo'   => $dateToday,
            'notShow'  => true,
        ] );
    }
}