<?php

namespace frontend\controllers;

use common\helpers\UserBanHelper;
use common\models\User;
use common\models\UserGroup;
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

        return parent::beforeAction( $action );
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $view = 'index';
        if ( Yii::$app->params[ 'userRole' ] == User::ROLE_BUYER ){
            $view = 'indexBuyer';
        }
        if ( Yii::$app->params[ 'userRole' ] == User::ROLE_TEAMLEAD ){
            $view = 'indexTeamlead';
        }
        if ( in_array( Yii::$app->params[ 'userRole' ], [ User::ROLE_CHIEF, User::ROLE_CHIEFTEAM, User::ROLE_CHIEFGAMB ] ) ){
            return $this->redirect( [ '/site/index' ] );
        }
        $dateToday = (new DateTime( 'NOW', new DateTimeZone( Yii::$app->params[ 'timezoneOffset' ] ) ))->format( 'Y-m-d' );

        return $this->render( $view, [
            'dateFrom' => $dateToday,
            'dateTo'   => $dateToday,
            'notShow'  => true,
        ] );
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdateOfficeTable()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // $officeTp = Yii::$app->request->post('officeTp');
        $dateFrom = Yii::$app->request->post( 'from_date' );
        $dateTo = Yii::$app->request->post( 'to_date' );
        $date = Yii::$app->request->post( 'date' );
        $type = Yii::$app->request->post( 'type' );
        $isChief = Yii::$app->request->post( 'isChief' ) ?? false;
        $isTeamlead = Yii::$app->request->post( 'isTeamlead' ) ?? false;
        $marker = Yii::$app->request->post( 'marker' ) ?? null;

        $dateToday = Yii::$app->formatter->asDatetime( 'NOW', 'yyyy-MM-dd' );

        return $this->renderAjax( '_officeTableData', [
            //'officeTp' => $officeTp ?? 'range',
            'dateFrom'   => $dateFrom ?? $dateToday,
            'dateTo'     => $dateTo ?? $dateToday,
            'type'       => $type,
            'sort'       => Yii::$app->request->post( 'sort' ),
            'date'       => $date ?? $dateToday . ' - ' . $dateToday,
            'isChief'    => $isChief,
            'isTeamlead' => $isTeamlead,
            'marker'     => $marker,
        ] );
    }
}