<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use yii\httpclient\Client as HttpClient;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string $symbol
 *
 * @property Client[] $clients
 * @property Country[] $countries
 * @property RateHistory[] $rateHistories
 * @property Transaction[] $transactions
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['symbol'], 'required'],
            [['symbol'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'symbol' => 'Currency',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateHistories()
    {
        return $this->hasMany(RateHistory::class, ['currency_id' => 'id']);
    }

    /**
     * Func for cron daily task to log rate
     * @return bool
     * @throws ServerErrorHttpException
     */
    public static function dailyRates()
    {
        $response = (new HttpClient)->createRequest()
            ->setUrl('http://www.floatrates.com/daily/usd.json')
            ->send();
        if ($response->isOk) {
            $currencies = ArrayHelper::map(self::find()->select(['id','symbol'])->where(['<>','symbol','usd'])->all(), 'id', 'symbol');
            foreach ($currencies as $id => $currency){
                $rate = new RateHistory([
                    'date' => date('Y-m-d', time()),
                    'currency_id' => $id,
                    'rate' => $response->data[$currency]['rate'],
                ]);
                if(!$rate->save()){
                    throw new ServerErrorHttpException(implode('|', $rate->errors));
                }
            }
            return true;
        }else{
            return $response->statusCode;
        }
    }

	/**
	 * @param null|string $date if null - return last rate
	 * @return float
	 */
	public function getRate($date = null){
		return $this->id == 1
			? 1
			: $this->getRateHistories()
		     ->select('rate')
		     ->filterWhere(['date' => $date])
		     ->orderBy('id DESC') //if date isn't set
		     ->one()
			 ->rate;
	}
}
