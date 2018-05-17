<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\City;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map(City::find()->filterWhere(['country_id' => $model->city->country_id])->all(), 'id', 'name'), ['prompt' => 'Your city']) ?>

    <?= $form->field($model, 'currency_id')->dropDownList(ArrayHelper::map(\app\models\Currency::find()->all(), 'id', 'symbol'), ['prompt' => 'Select your currency if it differ from your country currency']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
