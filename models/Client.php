<?php

namespace app\models;

use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property string $name
 * @property int $city_id
 * @property float $balance
 * @property int $currency_id
 *
 * @property City $city
 * @property Currency $currency
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions0
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    public $country_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'name'], 'required'],
            [['city_id', 'country_id', 'currency_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City',
            'currency_id' => 'Currency',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

	/**
	 * @return Country
	 */
    public function getCountry()
    {
        return @$this->city->country;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutbox()
    {
        return $this->hasMany(Transaction::class, ['from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInbox()
    {
        return $this->hasMany(Transaction::class, ['to' => 'id']);
    }


    public function getCredit(){
        Transaction::findAll([]);
    }

    public function checkBalance(){
        Transaction::findAll([]);
    }

	/**
	 * @param Client $recipient
	 * @param float $amount
	 * @param int $currencyId
	 *
	 * @return array|bool
	 */
    public function sendMoney($recipient, $amount, $currencyId){
        $transaction = new Transaction([
            'from' => $this->id,
            'to' => $recipient->id,
            'value' => $amount,
            'currency_id' => $currencyId,
        ]);
        return $transaction->save() ?: $transaction->errors;
    }

    /**
     * @param $amount
     * @return array|bool
     */
    public function getMoney($amount){
	    return (new Transaction([
		    'to' => $this->id,
		    'value' => $amount,
            'currency_id' => $this->currency_id
	    ]))->save();
    }

	/**
	 * @param int $transactionCurrencyId
	 * @param null|string $date
	 *
	 * @return float|int
	 */
	public function getConvertFactor($transactionCurrencyId, $date = null){
		return $this->currency_id == $transactionCurrencyId
			? 1
			: $this->currency->getRate($date) / Currency::findOne($transactionCurrencyId)->getRate($date);
	}

	/**
	 * Default currency on client create, from country of selected city
	 *
	 * @param bool $insert
	 *
	 * @return bool
	 * @throws BadRequestHttpException
	 */
	public function beforeSave($insert)
    {
        if($insert){
	        if($this->country_id && $this->country_id != $this->city->country_id){
		        throw new BadRequestHttpException(ucfirst($this->city->name) . ' is not in ' . ucfirst(Country::findOne($this->country_id)->name));
	        }
	        if(!$this->currency_id){
		        $this->currency_id = $this->city->country->currency_id;
	        }
        }
        return parent::beforeSave($insert);
    }
}
