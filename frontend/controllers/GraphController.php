<?php

namespace backend\controllers;

use Yii;
use backend\controllers\base\BaseProtectedController;
use frontend\helpers\GraphHelper;
use common\helpers\UserBanHelper;
use common\models\TeamleadMarkers;
use common\models\user\User;
use yii\db\Exception;
use yii2mod\rbac\filters\AccessControl;

/**
 * GraphController
 */
class GraphController extends BaseProtectedController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['leads-by-markers', 'geos-by-buyers', 'geos-by-one-buyer'],
                        'allow' => true,
                        'roles' => [
                            'ROLE_METRIC_DEVELOPER',
                            'ROLE_METRIC_WEBMASTER',
                        ],
                    ],
                    [
                        'actions' => [ 'leads-by-markers' ],
                        'allow'   => true,
                        'roles'   => [ '?' ],
                    ],
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
     * Generate GEOS by BUYERS graph
     * @param string $date_start - format 'Y-m-d'
     * @param string $date_end
     * @return string
     * @throws Exception
     */
    public function actionGeosByBuyers( $date_start, $date_end )
    {
        $markersCondition = '';
        if ( Yii::$app->params[ 'userRole' ] == User::ROLE_METRIC_DEVELOPER ){
            $markersCondition = ' AND 1=0 ';
            $tmModel = TeamleadMarkers::findOne( [ 'id_teamlead' => Yii::$app->user->id ] );
            $markers = $tmModel ? $tmModel->properMarkers()->markers : [];

            if ( !empty( $markers ) ){
                $markersCondition = ' AND `l`.`marker` IN (' . implode( ',', array_map( function ( $item ) {
                        return '\'' . $item . '\'';
                    }, $markers ) ) . ') ';
            }
        } elseif ( Yii::$app->params[ 'userRole' ] == User::ROLE_METRIC_WEBMASTER ) {
            $markersCondition = ' AND `l`.`marker` = "' . Yii::$app->user->identity->marker . '" ';
        }

        $sql = "
                SELECT `l`.`geo` AS `name`, count(*) AS `cnt`
                FROM `tb_lead` `l`
                JOIN `user` `u` ON trim(`l`.`marker`) = `u`.`marker`
                WHERE `l`.`flag_trash` = 0 
                  " . $markersCondition . "
                  AND DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(`l`.`created_at`),'+00:00','" . Yii::$app->params[ 'timezoneOffset' ] . "'),'%Y-%m-%d') BETWEEN :date_start AND :date_end
                GROUP BY `l`.`geo`
                ORDER BY `cnt` DESC
            ";


        $data = [];
        $rows = Yii::$app->db->createCommand( $sql )
            ->bindValue( ':date_start', $date_start )
            ->bindValue( ':date_end', $date_end )
            ->queryAll();

        foreach ( $rows as $row ) {
            $data[] = [ (string)$row[ 'name' ], (int)$row[ 'cnt' ] ];
        }

        return $this->renderAjax( 'geosByBuyers', [
            'data_geos' => $data,
            'dateStart' => $date_start,
            'dateEnd'   => $date_end,
            'marker'    => '',
        ] );
    }



    /**
     * Generate GEOS by ONE BUYER graph
     * @param int $id_buyer
     * @param string $date_start - format 'Y-m-d'
     * @param string $date_end
     * @return string
     * @throws Exception
     */
    public function actionGeosByOneBuyer( $id_buyer, $date_start, $date_end )
    {
        $modelBuyer = User::findOne( $id_buyer );
        if ( null !== $modelBuyer && !empty( $modelBuyer->marker ) ){
            $sql = "
                    SELECT `l`.`geo` AS `name`, count(*) AS `cnt`
                    FROM `tb_lead` `l`
                    WHERE  
                      `l`.`marker` = :marker
                      AND `l`.`flag_trash` = 0  
                      AND DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(`l`.`created_at`),'+00:00','" . Yii::$app->params[ 'timezoneOffset' ] . "'),'%Y-%m-%d') BETWEEN :date_start AND :date_end
                    GROUP BY `l`.`geo`
                    ORDER BY `cnt` DESC
                ";
        }

        $data = [];
        $rows = Yii::$app->db->createCommand( $sql )
            ->bindValue( ':marker', $modelBuyer->marker )
            ->bindValue( ':date_start', $date_start )
            ->bindValue( ':date_end', $date_end )
            ->queryAll();

        foreach ( $rows as $row ) {
            $data[] = [ (string)$row[ 'name' ], (int)$row[ 'cnt' ] ];
        }

        return $this->renderAjax( 'geosByBuyers', [
            'data_geos' => $data,
            'dateStart' => $date_start,
            'dateEnd'   => $date_end,
            'marker'    => $modelBuyer->marker,
        ] );
    }

    /**
     * Generate LEAS by MARKERS graph
     * @param string $date_start - format 'Y-m-d'
     * @param string $date_end
     * @param $action
     * @return string
     * @throws Exception
     */
    public function actionLeadsByMarkers($date_start, $date_end, $action )
    {
        $typeCondition = '';

        return $this->renderAjax( 'leadsByMarkers', [
            'action'     => $action ?? '',
            'data_leads' => GraphHelper::getLeadByMarkersData( $typeCondition, $date_start, $date_end ),
            'dateFrom'   => $date_start,
            'dateTo'     => $date_end,
            'hint'       => GraphHelper::getLeadByMarkersHint( $typeCondition, $date_start, $date_end ),
        ] );
    }
    
   
}
