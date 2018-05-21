<?php

use app\models\Client;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$client = Client::findOne($searchModel->clientId);
$clientName = $client->name;

$this->title = $clientName . ' transactions';
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['client/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    //$sum = array_sum($client->getInbox()->select('value')->column()) - array_sum($client->getOutbox()->select('value')->column());
    try {
        $grid = GridView::widget([
            'dataProvider' => $dataProvider,
            'showFooter' =>true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'senderName',
                'recipientName',
                [
                    'attribute' => 'value',
                    //'footer' => $sum,
                ],
                'cur',
                'when:datetime',
            ],
            'rowOptions' => function ($model) use ($clientName) {
                if ($model->senderName == '(FROM BANK)') {
                    $bgColor = 'dfe';
                } elseif ($model->senderName == $clientName) {
                    $bgColor = 'fee';
                } elseif ($model->recipientName == $clientName) {
                    $bgColor = 'efe';
                }
                return ['style' => 'background: #' . $bgColor];
            },
        ]);
    } catch (Exception $e) {
    }
    echo $grid;
    ?>

    <?= Html::a('XML', Yii::$app->request->url . '&xml=1', ['class' => 'btn btn-warning']) ?>

</div>
