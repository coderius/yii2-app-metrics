<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = Yii::t( 'messages', 'Reset password' );
$this->registerMetaTag( [ 'name' => 'robots', 'content' => 'noindex, follow' ] );

$js = '
    $( document ).on( "click", ".js-unmask", function( e ) {
        $(".js-password").attr("type", $(".js-password").attr("type") == "text" ? "password" : "text");
        e.preventDefault();  
    });
    $( document ).on( "click", ".js-unmask-passwordconfirm", function( e ) {
        $(".js-passwordconfirm").attr("type", $(".js-passwordconfirm").attr("type") == "text" ? "password" : "text");
        e.preventDefault();  
    });
';

$this->registerJs($js);

?>

<div class="login-box">

    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg"><?= Html::encode( $this->title ) ?></p>

            <?php $form = ActiveForm::begin(); ?>


            <?= $form->field($model, 'password', ['inputTemplate' => '<div class="input-group">{input}
                <div class="input-group-append">
                 <div class="input-group-text password-unmask-wrapper" >
                     <a class="fas fa-eye-slash js-unmask" ></a>
                 </div>
              </div></div>'])->passwordInput(['class' => 'js-password form-control', 'placeholder' => Yii::t( 'messages', 'Create new password' )])->label(false) ?>



            <?= $form->field($model, 'passwordconfirm', ['inputTemplate' => '<div class="input-group">{input}
                <div class="input-group-append">
                  <div class="input-group-text password-unmask-wrapper" >
                      <a class="fas fa-eye-slash js-unmask-passwordconfirm" ></a>
                  </div>
              </div></div>'])->passwordInput(['class' => 'js-passwordconfirm form-control', 'placeholder' => Yii::t('messages', 'Confirm new password')])->label(false) ?>

            <div class="row">
                <div class="col-12 text-center">
                    <?= Html::submitButton( Yii::t( 'buttons', 'Reset password' ), [ 'class' => 'btn btn-primary' ] ) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>


        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->