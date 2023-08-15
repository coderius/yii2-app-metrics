<?php

use backend\components\DashboardOffersTable;
use backend\components\DashboardOfficeTable;
use backend\components\PpStataByBuyers;

/**
 * @var $idExternalLead
 * @var $dateFrom string
 * @var $dateTo string
 */

?>

<?= PpStataByBuyers::widget(['dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'idExternalLead' => $idExternalLead]) ?>
