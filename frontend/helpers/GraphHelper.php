<?php


namespace frontend\helpers;


use common\models\TeamleadMarkers;
use common\models\UserGroup;
use Yii;

class GraphHelper
{
    /**
     * @param $typeCondition
     * @param $date_start
     * @param $date_end
     * @param $flag_repeated
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getLeadByMarkersData( $typeCondition, $date_start, $date_end, $flag_repeated = false )
    {
        $markersCondition = '';
        if ( Yii::$app->user->can( 'dashboard-teamlead' ) ){

            $markersCondition = ' AND 1=0 ';
            $tmModel = TeamleadMarkers::findOne( [ 'id_teamlead' => Yii::$app->user->id ] );
            $markers = $tmModel ? $tmModel->properMarkers()->markers : [];
            if ( !empty( $markers ) ){
                $markersCondition = ' AND `l`.`marker` IN (' . implode( ',', array_map( function ( $item ) {
                        return '\'' . $item . '\'';
                    }, $markers ) ) . ') ';
            }
        } elseif ( Yii::$app->user->can( 'dashboard-buyer' ) ) {
            $markersCondition = ' AND `l`.`marker` = "' . Yii::$app->user->identity->marker . '" ';
        }

        if ( $flag_repeated ){
            $repeatedCondition = ' AND `l`.`flag_repeat` = 1 ';
        } else {
            $repeatedCondition = ' AND `l`.`flag_repeat` = 0 ';
        }

        $sql = "
                SELECT `l`.`marker` AS `name`, count(*) AS `cnt`, `ug`.`tp`
                FROM `tb_lead` `l`
                JOIN `user` `u` ON trim(`l`.`marker`) = `u`.`marker`
				JOIN `tb_user_group` `ug` ON `u`.`id_group` = `ug`.`id`
                WHERE  	
                    `l`.`flag_trash` = 0  
                    " . $typeCondition . "
                    " . $markersCondition . "
                    " . $repeatedCondition . "
                    AND DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(`l`.`created_at`),'+00:00','" . Yii::$app->params[ 'timezoneOffset' ] . "'),'%Y-%m-%d') BETWEEN :date_start AND :date_end
                GROUP BY `l`.`marker`, `ug`.`tp`
                ORDER BY `cnt` DESC
            ";

        $data = [];
        $rows = Yii::$app->db->createCommand( $sql )
            ->bindValue( ':date_start', $date_start )
            ->bindValue( ':date_end', $date_end )
            ->queryAll();


        foreach ( $rows as $row ) {
            $data[] = [ 'y' => (int)$row[ 'cnt' ], 'name' => (string)$row[ 'name' ], 'color' => $row[ 'tp' ] == UserGroup::TP_EXTERNAL ? '#ff0000' : null ];
        }
        
        return $data;
    }

    /**
     * @param $typeCondition
     * @param $date_start
     * @param $date_end
     * @param $flag_repeated
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getLeadByMarkersHint( $typeCondition, $date_start, $date_end, $flag_repeated = false )
    {
        $markersCondition = '';
        if ( Yii::$app->user->can( 'dashboard-teamlead' ) ){
            $markersCondition = ' AND 1=0 ';
            $tmModel = TeamleadMarkers::findOne( [ 'id_teamlead' => Yii::$app->user->id ] );
            $markers = $tmModel ? $tmModel->properMarkers()->markers : [];

            if ( !empty( $markers ) ){
                $markersCondition = ' AND `l`.`marker` IN (' . implode( ',', array_map( function ( $item ) {
                        return '\'' . $item . '\'';
                    }, $markers ) ) . ') ';
            }
        } elseif ( Yii::$app->user->can( 'dashboard-buyer' ) ) {
            $markersCondition = ' AND `l`.`marker` = "' . Yii::$app->user->identity->marker . '" ';
        }

        if ( $flag_repeated ){
            $repeatedCondition = ' AND `l`.`flag_repeat` = 1 ';
        } else {
            $repeatedCondition = ' AND `l`.`flag_repeat` = 0 ';
        }

        $hint_sql = "
            SELECT `l`.`marker`, `l`.`geo`, count(*) AS `cnt`
            FROM `tb_lead` `l`
            JOIN `user` `u` ON trim(`l`.`marker`) = `u`.`marker`
            JOIN `tb_user_group` `ug` ON `u`.`id_group` = `ug`.`id`
            WHERE `flag_trash` = 0 
                " . $typeCondition . "
                " . $markersCondition . "
                " . $repeatedCondition . "
                AND DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(`l`.`created_at`),'+00:00','" . Yii::$app->params[ 'timezoneOffset' ] . "'),'%Y-%m-%d') 
                BETWEEN :date_start AND :date_end
            GROUP BY `l`.`marker`, `l`.`geo` 
        ";

        $hintData = [];
        $hint_rows = Yii::$app->db->createCommand( $hint_sql )
            ->bindValue( ':date_start', $date_start )
            ->bindValue( ':date_end', $date_end )
            ->queryAll();

        foreach ( $hint_rows as $hint_row ) {
            if ( !isset( $hintData[ $hint_row[ 'marker' ] ] ) ){
                $hintData[ $hint_row[ 'marker' ] ] = $hint_row[ 'geo' ] . ': ' . $hint_row[ 'cnt' ];
            } else {
                $hintData[ $hint_row[ 'marker' ] ] .= ' ' . $hint_row[ 'geo' ] . ': ' . $hint_row[ 'cnt' ];
            }
        }

        return $hintData;
    }

    /**
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function geosByDeals()
    {
        return Yii::$app->db->createCommand( "
            SELECT `id_deal`, GROUP_CONCAT(`name` SEPARATOR ';') AS `geos`, SUM(`price`) AS `price`
            FROM (
                SELECT `dg`.`id_deal`, `g`.`name`, (`dg`.`cost_purchase` * `dg`.`cnt`) AS `price`
                FROM `tb_deal_geo` `dg`
                JOIN `tb_deal` `d` ON `dg`.`id_deal` = `d`.`id`
                JOIN `tb_office_geo` `g` ON `dg`.`id_office_geo` = `g`.`id`
                WHERE `d`.`status` = 0
            ) a
            GROUP BY `id_deal`" )->queryAll();
    }

    /**
     * @param array $arrIdsOffices
     * @param string $date_start
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function leadsByDeals( array $arrIdsOffices, string $date_start )
    {
        //получаем проданные лиды (точнее их количество) ПОДСЧЕТ ПО СДЕЛКАМ, начитая с отчетного периода для выбранных офисов
        $sqlLeadsByDeals = "
            SELECT
                `l`.`id_deal`, 
                -- DATE_FORMAT( CONVERT_TZ( FROM_UNIXTIME( `l`.`sent_at` ), '+00:00', '" . Yii::$app->params[ 'timezoneOffset' ] . "' ), '%Y-%m-%d' ) AS `dt`,
                count(*) AS `cnt`,
                SUM(`l`.`cost_purchase`) AS `sum_purchases`
            FROM
                `tb_lead` `l`
            WHERE
                `l`.`flag_trash` = 0 
                AND DATE_FORMAT( CONVERT_TZ( FROM_UNIXTIME( `l`.`sent_at` ), '+00:00', '" . Yii::$app->params[ 'timezoneOffset' ] . "' ), '%Y-%m-%d' ) >= :date_start
                AND `l`.`id_office` IN (" . implode( ',', $arrIdsOffices ) . ")
                AND `l`.`id_office_lang` IS NOT NULL
                AND `l`.`id_deal` IS NOT NULL 
            GROUP BY
                `l`.`id_deal` 
            ORDER BY
                `id_deal`, `cnt`
        ";

        return Yii::$app->db->createCommand( $sqlLeadsByDeals )->bindValue( ':date_start', $date_start )->queryAll();
    }

    /**
     * @param array $arrIdsOffices
     * @param string $date_start
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function getLeadsByOffices( array $arrIdsOffices, string $date_start )
    {
        //получаем проданные лиды (точнее их количество), начитая с отчетного периода для выбранных офисов
        $sqlLeads = "
            SELECT
                `l`.`id_office`, 
                `l`.`id_office_lang`,
                DATE_FORMAT( CONVERT_TZ( FROM_UNIXTIME( `l`.`sent_at` ), '+00:00', '" . Yii::$app->params[ 'timezoneOffset' ] . "' ), '%Y-%m-%d' ) AS `dt`,
                count(*) AS `cnt`,
                SUM(`l`.`cost_purchase`) AS `sum_purchases`
            FROM
                `tb_lead` `l`
            WHERE
                `l`.`flag_trash` = 0 
                AND DATE_FORMAT( CONVERT_TZ( FROM_UNIXTIME( `l`.`sent_at` ), '+00:00', '" . Yii::$app->params[ 'timezoneOffset' ] . "' ), '%Y-%m-%d' ) >= :date_start
                AND `l`.`id_office` IN (" . implode( ',', $arrIdsOffices ) . ")
                AND `l`.`id_office_lang` IS NOT NULL 
            GROUP BY
                `l`.`id_office`, `l`.`id_office_lang`, `dt` 
            ORDER BY
                `id_office`, `dt`, `cnt`
        ";

        return Yii::$app->db->createCommand( $sqlLeads )->bindValue( ':date_start', $date_start )->queryAll();
    }

    /**
     * @param array $arrIdsOffices
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function depositByDeals( array $arrIdsOffices )
    {
        $sqlDepositByDeals = "
            SELECT
                `l`.`id_deal`, 
                count(*) AS `cnt`
            FROM
                `tb_lead` `l`
            WHERE
                `l`.`pay_flag` = 1 
                AND `l`.`id_office` IN (" . implode( ',', $arrIdsOffices ) . ")
                AND `l`.`id_office_lang` IS NOT NULL
                AND `l`.`id_deal` IS NOT NULL 
            GROUP BY
                `l`.`id_deal` 
            ORDER BY
                `id_deal`, `cnt`
        ";

        return Yii::$app->db->createCommand( $sqlDepositByDeals )->queryAll();
    }

}