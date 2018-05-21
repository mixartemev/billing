<?php

use app\models\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
	    <?= $form->field($model, 'clientId', ['options' => ['class' => 'col-xs-12']])
	             ->dropDownList(ArrayHelper::map(Client::find()->all(), 'id', 'name')) ?>
        <br>
	    <?= $form->field($model, 'beginPeriod', ['options' => ['class' => 'col-xs-6']])->textInput(['options' => ['class' => 'form-control']])
	             ->widget( DatePicker::className(), ['dateFormat' => 'yyyy-MM-dd']) ?>
	    <?= $form->field($model, 'endPeriod', ['options' => ['class' => 'col-xs-6']])
	             ->widget( DatePicker::className(), ['dateFormat' => 'yyyy-MM-dd']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
