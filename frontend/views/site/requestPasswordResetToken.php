<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = Yii::t( 'messages', 'Password recovery' );
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow']);

?>

<div class="login-box">

    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg"><?= Html::encode( $this->title ) ?></p>

            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form'] ); ?>

            <?= $form->field( $model, 'email' )->textInput( ['autofocus' => true] ) ?>

            <p><?= Yii::t( 'messages', 'We will send you a link to your e-mail address to reset your password.' ) ?></p>


            <div class="row">
                <div class="col-12 text-center">
                    <?= Html::submitButton( Yii::t( 'buttons', 'Recover' ), [ 'class' => 'btn btn-primary' ] ) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>


        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->