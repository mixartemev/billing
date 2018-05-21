<?php

namespace app\controllers;

use app\models\Currency;
use Yii;
use app\models\Client;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
{
    use SortAddable;

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Client::find()->joinWith(['city', 'currency', 'city.country']),
        ]);
        $dataProvider->sort->attributes []= ['city.name' => SORT_ASC];
        self::addSort($dataProvider->sort->attributes, ['city.name', 'country.name', 'currency.symbol']);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->enableCsrfValidation = false;
        $model = new Client();

        if ($model->load(['Client' => Yii::$app->request->get()]) && $model->save()) {
            return "Successful: Client {$model->name} from {$model->city->country->name} {$model->city->name} created, and use {$model->currency->symbol}";
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

	/**
	 * Updates an existing Client model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @param $amount
	 * @return mixed
	 * @throws NotFoundHttpException
	 * @throws ServerErrorHttpException
	 */
	public function actionCharge($id, $amount)
	{
		$model = $this->findModel($id);
		if($model->getMoney($amount) !== true){
			throw new ServerErrorHttpException();
		}

		/** Only for return check info, it might be removed */
		/**/ $oldBalance = $model->balance;
		/**/ $model->refresh();
		/** Only for return check info, it might be removed */

		return "Success: {$model->name} have {$oldBalance}+{$amount}={$model->balance}{$model->currency->symbol}.";
	}

	/**
	 * @param int $senderId
	 * @param int $recipientId
	 * @param float $amount
	 * @param int|null $currencyId
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

		$sender->sendMoney($recipient, $amount, $currencyId);

		/** Only for return check info, it might be removed */
		/**/ $senderOldBalance = $sender->balance;
		/**/ $recipientOldBalance = $recipient->balance;
		/**/ $sender->refresh();
		/**/ $recipient->refresh();
		/**/ $transactionCurrencySymbol = Currency::findOne($currencyId)->symbol;
		/** Only for return check info, it might be removed */

		return "Success: {$sender->name} have {$senderOldBalance}{$sender->currency->symbol}-{$amount}{$transactionCurrencySymbol}={$sender->balance}{$sender->currency->symbol}
		 {$recipient->name} have {$recipientOldBalance}{$recipient->currency->symbol}+{$amount}{$transactionCurrencySymbol}={$recipient->balance}{$recipient->currency->symbol}.";
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
}
