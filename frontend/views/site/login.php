<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Vibero';
$this->params[ 'breadcrumbs' ][] = $this->title;
$this->params[ 'h1' ] = $this->title;

$this->registerCssFile( "plugins/disk/slidercaptcha.min.css",
    [ 'rel'     => 'stylesheet',
      'depends' => [ backend\assets\AppAsset::class ],
    ] );
$this->registerCssFile( "css/sliderCaptcha.css",
    [ 'depends' => [ backend\assets\AppAsset::class ] ] );

$this->registerJsFile( "plugins/disk/longbow.slidercaptcha.min.js",
    [ 'rel'     => 'stylesheet',
      'depends' => [ yii\web\JqueryAsset::class ],
    ] );

$js = '
    $( document ).on( "click", ".js-unmask", function( e ) {
        $(".js-password").attr("type", $(".js-password").attr("type") == "text" ? "password" : "text");
        e.preventDefault();  
    });
    
//    var captcha = sliderCaptcha({
//        id: "captcha",
//        width: 220,
//        height: 135,
//        //repeatIcon: "fa fa-redo",
//        repeatIcon: false,
//        barText: "' . Yii::t( 'messages', 'Slide to verify' ) . '",
//        onSuccess: function () {
//            document.getElementById("verify").value = "success";
//        },
//        onFail:function () {
//            document.getElementById("verify").value = "";
//        },
//        onRefresh:function () {
//            document.getElementById("verify").value = "";
//        }
//    });
';

$this->registerJs( $js );
?>


<div class="login-box">


    <!-- /.login-logo -->
    <div class="card-login">
        <div class="card-body login-card-body">
            <div class="card-login__header">
                <div class="card-login__header__ico">
                    <span class="info-box__icon">
                        <svg width="29" height="29" class="ico">
                            <use xlink:href="#svg-user"></use>
                        </svg>
                    </span>
                </div>
                <div class="card-login__header__title">
                    <h1><?= Yii::t( 'messages', 'Nice to see you!' ) ?></h1>
                    <p><?= Yii::t( 'messages', 'Sign in to start your session' ) ?></p>
                </div>
            </div>


            <?php $form = ActiveForm::begin( [ 'id' => 'login-form' ] ); ?>

            <div class="card-login__form">
                <div class="card-login__form__left">
                    <?= $form->field( $model, 'email' )->textInput( [ 'autofocus' => true, 'placeholder' => Yii::t( 'messages', 'Email' ) ] )->label( true ) ?>

                    <?= $form->field( $model, 'password', [ 'inputTemplate' => '<div class="input-group pass">{input}
                        <div class="input-group-append">
                        <div class="input-group-text password-unmask-wrapper" style="padding: 10px 0 0 0 !important;">
                                                <a class="js-unmask" ><span class="material-symbols-outlined">visibility</span></a>
                                            </div>
                      </div> </div>' ] )->passwordInput( [ 'placeholder' => Yii::t( 'messages', 'Password' ), 'class' => 'js-password form-control' ] )->label( true ) ?>
                    <div class="card-login__form__remember">
                        <label class="checkbox-r">
                            <input type="checkbox" value="1" name="Remember" checked="">
                            <span><?= Yii::t( 'messages', 'Remember me' ) ?></span>
                        </label>
                    </div>
                </div>
                <?php /*
                <div class="card-login__form__right">
                    <div class="form-group">
                        <div class="slidercaptcha card">
                            <div class="card-header" style="min-height: 40px !important;">
                                <span><?= Yii::t('messages', 'Complete the security check') ?></span>
                            </div>
                            <div class="card-body">
                                <div id="captcha"></div>
                            </div>
                        </div>
                    </div>
                </div>
                */ ?>

                <div class="card-login__form__footer">
                    <?= Html::submitButton( Yii::t( 'buttons', 'Login' ), [ 'class' => 'btn btn-primary', 'name' => 'login-button' ] ) ?>
                    <?php /*
                    <div class="card-login__form__footer__ps">
                        <p><?= Html::a(Yii::t('buttons', 'Forgot password?'), ['site/request-password-reset']) ?></p>
                    </div>
                    */ ?>
                </div>
            </div>

            <?= $form->field( $model, 'verify' )->hiddenInput( [
                'type'  => 'hidden',
                'id'    => 'verify',
                'value' => '',
            ] )->label( false ) ?>

            

            <?php ActiveForm::end(); ?>


        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
<br>