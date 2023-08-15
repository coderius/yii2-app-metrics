<?php

namespace backend\components\graph;

use backend\helpers\OfficeHelper;
use Yii;
use DateTime;
use DateTimeZone;
use yii\base\Widget;


class LeadsByMarkersGraph extends Widget
{
    public $dateStart = null;
    public $dateEnd = null;

    public function run()
    {
        $dateToday = ( new DateTime( 'NOW', new DateTimeZone( Yii::$app->params['timezoneOffset'] ) ) )->format( 'Y-m-d' );
        return $this->render( 'leadsByMarkersGraph', [
            'action'    => Yii::$app->controller->action->id,
            'dateStart' => $this->dateStart ?? $dateToday,
            'dateEnd'   => $this->dateEnd ?? $dateToday,
        ] );

    }

}