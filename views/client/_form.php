<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\City;
use app\models\Country;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(['method' => 'get']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field(new Country(),'id')
        ->dropDownList(ArrayHelper::map(Country::find()->all(), 'id', 'name'), ['prompt' => 'Your country'])
        ->label('Country') ?>

    <?= $form->field($model, 'city_id')->dropDownList(
            ArrayHelper::map(City::find()->filterWhere(['country_id' => @$model->city->country_id])->all(), 'id', 'name'),
            ['prompt' => 'Your city']
    ) ?>

    <?= $form->field($model, 'currency_id')
        ->dropDownList(ArrayHelper::map(\app\models\Currency::find()->all(), 'id', 'symbol'),
            ['prompt' => 'Select your currency if it differ from your country currency']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
