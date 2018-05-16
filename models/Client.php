<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property string $name
 * @property int $city_id
 * @property string $balance
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['city_id', 'currency_id'], 'integer'],
            [['balance'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'city_id' => 'City ID',
            'balance' => 'Balance',
            'currency_id' => 'Currency ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions0()
    {
        return $this->hasMany(Transaction::className(), ['to' => 'id']);
    }

    public function getDebit(){
        foreach (Transaction::findAll(['to' => $this->id]) as $in){
            if($in->currency_id != $this->currency_id){

            }
        }
    }

    public function getCredit(){
        Transaction::findAll([]);
    }

    public function getBalance(){
        Transaction::findAll([]);
    }

    /**
     * @param Client $recipient
     * @param float $amount
     * @param null $currencyId
     * @return array|bool
     */
    public function sendMomey(Client $recipient, $amount, $currencyId){
        if($amount > $this->balance)
        $transaction = new Transaction([
            'from' => $this->id,
            'to' => $recipient->id,
            'currency_id' => $currencyId,
            'value' => $amount,
        ]);
        return $transaction->save() ?: $transaction->errors;
    }

    public function beforeSave($insert)
    {
        if($insert && !$this->currency_id){
            $this->currency_id = $this->city->country->currency_id;
        }
        return parent::beforeSave($insert);
    }
}
