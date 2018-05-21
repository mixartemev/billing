<?php

namespace app\controllers;

use app\models\Client;
use Yii;
use app\models\Transaction;
use app\models\TransactionSearch;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{
    /**
     * Lists all Transaction models.
     * @param null $xml
     * @return mixed
     */
    public function actionIndex($xml = null)
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($xml) {
            Yii::$app->response->format = Response::FORMAT_XML;
            $period = ($searchModel->beginPeriod ?: 'mesozoic') . '_' . ($searchModel->endPeriod ?: date('Y-m-d'));
            $fileName = Client::findOne($searchModel->clientId)->name . "_transactions_{$period}.xml";
            Yii::$app->response->setDownloadHeaders($fileName);
            /** @var ActiveQuery $aq */
            $aq = $dataProvider->query;
            return $aq->asArray()->all();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
