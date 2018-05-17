<?php

namespace app\models;

use Yii;
use yii\web\BadRequestHttpException;

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
            [['city_id', 'name'], 'required'],
            [['city_id', 'currency_id'], 'integer'],
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
            'city_id' => 'City',
            'balance' => 'Balance',
            'currency_id' => 'Currency',
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
     * @return array|bool
     * @throws BadRequestHttpException
     */
    public function sendMoney(Client $recipient, $amount){
        if($amount > $this->balance){
            $transaction = new Transaction([
                'from' => $this->id,
                'to' => $recipient->id,
                'value' => $amount,
            ]);
            return $transaction->save() ?: $transaction->errors;
        }
        throw new BadRequestHttpException('You haven\'t such many money');
    }

    /**
     * @param $amount
     * @return array|bool
     */
    public function getMoney($amount){
        $transaction = new Transaction([
                'to' => $this->id,
                'value' => $amount,
            ]);
        return $transaction->save() ?: $transaction->errors;
    }

    public function getCoeff($currencyId, $date = null){
        if ($this->currency_id != $currencyId){
            if ($from == 1){
                return self::find()
                    ->select('rate')
                    ->where(['to' => $currencyId])
                    ->andFilterWhere(['date' => $date])
                    ->orderBy('id DESC')
                    ->one()
                    ->rate;
            }
        }else{
            return 1;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        if(!$this->currency_id && $this->city_id){
            $this->currency_id = $this->city->country->currency_id;
        }
    }

    public function beforeSave($insert)
    {
        if($insert && !$this->currency_id){
            $this->currency_id = $this->city->country->currency_id;
        }
        return parent::beforeSave($insert);
    }
}
