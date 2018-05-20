<?php

namespace app\controllers;

use app\models\Currency;
use Yii;
use app\models\Client;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Client::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

	/**
	 * Deletes an existing Client model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

	/**
	 * Updates an existing Client model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @param $amount
	 *
	 * @return mixed
	 * @throws NotFoundHttpException
	 * @throws ServerErrorHttpException
	 */
	public function actionCharge($id, $amount)
	{
		$model = $this->findModel($id);
		$oldBalance = $model->balance;
		if($model->getMoney($amount) !== true){
			throw new ServerErrorHttpException();
		}
		$model->refresh();
		return "Success: {$model->name} have {$oldBalance}+{$amount}={$model->balance}{$model->currency->symbol}.";
	}

	/**
	 * @param int $senderId
	 * @param int $recipientId
	 * @param float $amount
	 * @param int|null $currencyId
	 *
	 * @return string
	 * @throws BadRequestHttpException
	 * @throws NotFoundHttpException
	 */
	public function actionSend($senderId, $recipientId, $amount, $currencyId = null)
	{
		$sender = $this->findModel($senderId);
		$recipient = $this->findModel($recipientId);

		if(!$currencyId){ //recipient currency by default
			$currencyId = $recipient->currency_id;
		}
		if(!in_array($currencyId, [$sender->currency_id, $recipient->currency_id])){
			throw new BadRequestHttpException('You cant use this currency');
		}

		$senderOldBalance = $sender->balance;
		$recipientOldBalance = $recipient->balance;

		$sender->sendMoney($recipient, $amount, $currencyId);

		$sender->refresh();
		$recipient->refresh();

		$transactionCurrencySymbol = Currency::findOne($currencyId)->symbol;

		return "Success: {$sender->name} have {$senderOldBalance}{$sender->currency->symbol}-{$amount}{$transactionCurrencySymbol}={$sender->balance}{$sender->currency->symbol}
		 {$recipient->name} have {$recipientOldBalance}{$recipient->currency->symbol}+{$amount}{$transactionCurrencySymbol}={$recipient->balance}{$recipient->currency->symbol}.";
	}
}
