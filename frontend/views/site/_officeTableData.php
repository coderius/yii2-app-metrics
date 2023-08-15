<?php

use backend\components\DashboardOffersTable;
use backend\components\DashboardOfficeTable;

/**
 * @var $dateFrom string
 * @var $dateTo string
 * @var $type string
 * @var $date string
 * @var $sort string
 * @var $isChief bool
 * @var $notShow bool
 */

?>

<?php echo $type == 'office' ? DashboardOfficeTable::widget( [
//    'officeTp' => $officeTp,
    'dateFrom' => $dateFrom,
    'dateTo'   => $dateTo,
    'date'     => $date,
    'sort'     => $sort ,
    'notShow'  => $notShow
] ) : DashboardOffersTable::widget([
    'dateFrom' => $dateFrom,
    'dateTo'   => $dateTo,
    'date'     => $date,
    'sort'     => $sort,
    'isChief'  => $isChief,
    'notShow'  => $notShow
]) ?>