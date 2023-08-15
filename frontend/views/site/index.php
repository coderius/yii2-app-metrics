<?php

use backend\components\graph\GeosByBuyersGraph;
use backend\components\graph\LeadsByMarkersGraph;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $officeTp string */
/* @var $dateFrom string */
/* @var $dateTo string */
/* @var $date string */
/* @var $isCreator bool */

$this->title = Yii::t('messages', 'Dashboard panel');

?>

<div class="site-index">
    <div class="row">
        <div class="col-lg-6 fpad24">
            <?= GeosByBuyersGraph::widget() ?>
        </div>
    <php if(isCreator): ?>
        <div class="col-lg-6 fpad24">
            <?= LeadsByMarkersGraph::widget(['dateStart' => Yii::$app->formatter->asDatetime(strtotime(date('Y-m-01')), 'yyyy-MM-dd')]) ?>
        </div>
    <?php endif; ?>    
    </div>
</div>
