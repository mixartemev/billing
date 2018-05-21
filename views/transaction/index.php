<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyTextOptions' => ['class' => 'danger'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'senderName',
            'recipientName',
            'value',
            'cur',
	        'when:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?= Html::a('XML', Yii::$app->request->url . '&xml=1', ['class' => 'btn btn-warning']) ?>

</div>
