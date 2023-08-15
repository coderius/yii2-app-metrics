<?php

namespace backend\components\graph;

use backend\helpers\OfficeHelper;
use DateTime;
use DateTimeZone;
use Yii;
use yii\base\Widget;


class GeosByBuyersGraph extends Widget
{
    public $idBuyer = null;

    public function run()
    {

        //$week = OfficeHelper::getArrayDatesCurrentWeek();

        $dateToday = ( new DateTime( 'NOW', new DateTimeZone( Yii::$app->params['timezoneOffset'] ) ) )->format( 'Y-m-d' );
        return $this->render( 'geosByBuyersGraph', [
            'idBuyer'   => $this->idBuyer,
//            'dateStart' => min( $week ),
//            'dateEnd'   => max( $week ),
            'dateStart' => Yii::$app->formatter->asDatetime(strtotime(date('Y-m-01')), 'yyyy-MM-dd'),
            'dateEnd'   => $dateToday,
        ] );
    }

}