<?php

/* @var $this yii\web\View */
/* @var $model common\models\UserBan */

use yii\helpers\Html;

$this->title = Yii::t( 'messages', 'Your account is blocked' );
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow']);
?>


<div class="container mt-5">

    <div class="justify-content-center w-100 row text-center">
        <h1 class="ec-form-box__title"><?= Html::encode( $this->title ) ?></h1>
    </div>

    <?php if( !empty($model->descr) ): ?>
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8">
                <p class="ec-form-box__text text-center">
                    <?= Yii::t( 'messages', 'Reason' ) ?>: <?= Html::encode($model->descr) ?>
                </p>
            </div>
        </div>
    <?php endif; ?>


    <div class="row justify-content-center">
        <?= Html::a( Yii::t( 'messages', 'Go to login page'), [ '/site/login' ], [ 'class' => 'btn btn-info' ] ) ?>
    </div>

</div>
