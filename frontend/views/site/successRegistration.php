<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t( 'messages', 'Registration successed' );
$this->registerMetaTag( [ 'name' => 'robots', 'content' => 'noindex, follow' ] );
?>

<div class="login-box">

    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="ec-form-box__text text-center">
                <?= Yii::t( 'messages', 'You have been successfully registered, the confirmation email is on its way.' ) ?>
                <br><br>
                <?= Yii::t('messages', 'Please check your email and don’t forget to check the spam folder. In case you haven’t received the email in 10 minutes, please contact our support.') ?>
            </p>

            <div class="text-center mt-4">
                <?= Html::a( Yii::t( 'buttons', 'Login' ), ['/site/login'], ['class' => 'btn btn-primary'] ) ?>
            </div>

        </div>
        <!-- /.login-card-body -->
    </div>
</div>
