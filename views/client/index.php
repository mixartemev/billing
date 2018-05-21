<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <p>
        <?= Html::a('Create Client', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->name, Url::to(['transaction/index', 'TransactionSearch[clientId]' => $model->id]));
                }
            ],
            'city.name',
            'country.name',
            [
                'attribute' => 'balance',
                'value' => function ($model) {
                    return $model->balance .' '. strtoupper($model->currency->symbol);
                }
            ],
        ],
    ]); ?>
</div>
