<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model SignupForm */

use backend\models\SignupForm;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
$this->params['h1'] = $this->title;

$this->registerMetaTag( [
    'name'    => 'description',
    'content' => Yii::t( 'messages', 'Sign up on eclipseaffiliate.com' ),
] );

$this->registerCssFile( "plugins/disk/slidercaptcha.min.css",
    [   'rel' => 'stylesheet',
        'depends'=> [ backend\assets\AppAsset::class ]
    ]);
$this->registerCssFile( "css/sliderCaptcha.css",
    [ 'depends'=> [ backend\assets\AppAsset::class ]]);

$this->registerJsFile( "plugins/disk/longbow.slidercaptcha.min.js",
    [   'rel' => 'stylesheet',
        'depends'=> [ yii\web\JqueryAsset::class ]
    ]);


$js = '    
    $( document ).on( "click", ".js-unmask", function( e ) {
        var pswrdField = $(this).parent().parent().parent().find(".js-password");
        pswrdField.attr("type", pswrdField.attr("type") == "text" ? "password" : "text");
        e.preventDefault();  
    });
    
     var captcha = sliderCaptcha({
        id: "captcha",
        width: 220,
        height: 135,
        repeatIcon: "fa fa-redo",
        barText: "'. Yii::t( 'messages', 'Slide to verify' ).'",
        onSuccess: function () {
            document.getElementById("verify").value = "success";
        },
        onFail:function () {
            document.getElementById("verify").value = "";
        },
        onRefresh:function () {
            document.getElementById("verify").value = "";
        }
    });
   
';

$this->registerJs( $js );

$this->registerMetaTag( [ 'name' => 'robots', 'content' => 'noindex, follow' ] );

?>
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>Vibero TDS</b></a>
    </div>

    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>
            <?= $form->field($model, 'email', [
                'inputTemplate' => '<div class="input-group">{input}
                <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div></div>',
            ])->textInput(['autofocus' => true]) ?>


            <?= $form->field( $model, 'firstname' )->textInput( [ 'autofocus' => true ] ) ?>

            <?= $form->field( $model, 'lastname' )->textInput() ?>

            <?= $form->field( $model, 'password', [
                'inputTemplate' => '<div class="input-group">{input}
                <div class="input-group-append">
                <div class="input-group-text password-unmask-wrapper" >
                    <a class="fas fa-eye-slash js-unmask" ></a>
                </div>
              </div> </div>',
            ] )->passwordInput( [ 'class' => 'js-password form-control' ] ); ?>

            <?= $form->field( $model, 'passwordconfirm', [
                'inputTemplate' => '<div class="input-group">{input}
                <div class="input-group-append">
                <div class="input-group-text password-unmask-wrapper" >
                    <a class="fas fa-eye-slash js-unmask" ></a>
                </div>
              </div> </div>',
            ] )->passwordInput( [ 'class' => 'js-password form-control' ] ); ?>


            <div class="form-group">
                <div class="slidercaptcha card">
                    <div class="card-header">
                        <span><?= Yii::t( 'messages', 'Complete the security check' ) ?></span>
                    </div>
                    <div class="card-body">
                        <div id="captcha"></div>
                    </div>
                </div>
            </div>

            <?= $form->field($model, 'verify')->hiddenInput([
                'type' => 'hidden',
                'id' => 'verify',
                'value' => '',
            ])->label(false) ?>

            <div class="row">
                <div class="col-12 text-center">
                    <?= Html::submitButton( Yii::t( 'buttons', 'Create an account' ), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>


            <?php ActiveForm::end(); ?>


        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->