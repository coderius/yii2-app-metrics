<?php

use backend\components\control\ControlStata;

/**
 * @var $dateFrom string
 * @var $dateTo string
 */

?>

<?= ControlStata::widget([
	'dateFrom' => $dateFrom,
	'dateTo' => $dateTo
]) ?>
