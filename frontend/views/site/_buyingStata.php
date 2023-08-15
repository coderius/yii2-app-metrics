<?php

use backend\components\BuyingStata;

/**
 * @var $type string
 * @var $dateFrom string
 * @var $dateTo string
 */

?>

<?= BuyingStata::widget( [ 'type' => $type, 'dateFrom' => $dateFrom, 'dateTo'   => $dateTo ]); //это только в командном ?>