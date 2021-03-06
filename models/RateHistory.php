<?php

namespace app\models;

/**
 * This is the model class for table "rate_history".
 *
 * @property int $id
 * @property string $date
 * @property int $currency_id
 * @property double $rate
 *
 * @property Currency $currency
 */
class RateHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rate_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'currency_id', 'rate'], 'required'],
            [['date'], 'safe'],
            [['currency_id'], 'integer'],
            [['rate'], 'number'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'currency_id' => 'Currency',
            'rate' => 'Rate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }
}
