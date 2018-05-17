<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use yii\httpclient\Client as HttpClient;

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
            'id' => 'ID',
            'symbol' => 'Symbol',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateHistories()
    {
        return $this->hasMany(RateHistory::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['currency_id' => 'id']);
    }

    /**
     * @return bool
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
                    print_r($rate->errors);
                    return false;
                }
            }
            return true;
        }else{
            print_r($response->data);
        }
    }
}
