<?php

namespace app\controllers;

use app\models\RateHistory;
use yii\web\Controller;

/**
 * RateHistoryController implements the CRUD actions for RateHistory model.
 */
class RateHistoryController extends Controller
{
	/**
	 * Get Last or specify date Currency Rate.
	 *
	 * @param int $currencyId
	 * @param null|string $date
	 *
	 * @return mixed
	 */
	public function actionRate($currencyId, $date = null)
	{
		return RateHistory::find()
		        ->select('rate')
				->where(['currency_id' => $currencyId])
				->filterWhere(['date' => $date])
				->orderBy('id DESC') //if date isn't set
				->one()
				->rate;
	}
}
