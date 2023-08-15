<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */


use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = Yii::t( 'messages', 'Request reset password successful' );
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow']);
?>

<div class="login-box">

    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">
                <b><?= Html::encode( $this->title ) ?></b>
                <br><br>

            <?= Yii::t( 'messages', 'Check your email for further instructions.' ) ?>

            </p>

        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->