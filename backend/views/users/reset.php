<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Users */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Reset User Password';
?>
<div class="container-fluid">
    <h3>Reset Password</h3>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">Reset User Form</div>
                <div class="panel-body">
                    <div class="row">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'class' => 'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?= $form->field($model, 'verifyPassword')->passwordInput(['maxlength' => true,'class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 m-t-20">
                            <?= Html::submitButton('Reset', ['class' => 'btn btn-success']) ?>
                            <?= Html::a('Back', ['/admin/users'], ['class' => 'mb-sm btn btn-warning pull-right']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>