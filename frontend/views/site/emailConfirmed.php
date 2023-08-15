<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t( 'messages', 'Email has been successfully confirmed. Click login to proceed to your account.' );
$this->registerMetaTag( [ 'name' => 'robots', 'content' => 'noindex, follow' ] );
?>

<div class="login-box">

    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="ec-form-box__text"><?= Yii::t( 'messages', 'Email has been successfully confirmed. Click login to proceed to your account.' ) ?></p>

            <div class="text-center mt-4">
                <?= Html::a( Yii::t( 'buttons', 'Login' ), [ '/site/login' ], [ 'class' => 'btn btn-primary' ] ) ?>
            </div>


        </div>
        <!-- /.login-card-body -->
    </div>
</div>
