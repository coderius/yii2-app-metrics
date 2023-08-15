<?php

use yii\helpers\Html;
use yii\helpers\Url; ?>

<div class="text-center" style="margin-top: 20%">
    <i class="fa fa-exclamation-circle" style="font-size: 70px"></i>
    <h4 class="mt-4"><?= Yii::t( 'messages', 'Login for this ip address is restricted, please contact our support' ) ?></h4>
    <?php if ( Yii::$app->user->isGuest ): ?>
        <?= Html::a( Yii::t( 'buttons', 'Login page' ), Url::to( [ 'site/login' ] ), [ 'class' => 'btn btn-primary mt-4' ] ) ?>
    <?php else: ?>
        <?= Html::a( Yii::t( 'buttons', 'Login page' ), Url::to( [ 'site/logout' ] ), [ 'class' => 'btn btn-primary mt-4', 'data-method' => 'POST'] ) ?>
    <?php endif; ?>

</div>
