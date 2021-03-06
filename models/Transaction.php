<?php

namespace app\models;

use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $from
 * @property int $to
 * @property float $value
 * @property int $currency_id
 * @property string $when
 *
 * @property Currency $currency
 * @property Client $sender
 * @property Client $recipient
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    public $senderName, $recipientName, $cur;

    /**
     * Validate
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['to', 'value'], 'required'],
            [['from', 'to', 'currency_id'], 'integer'],
            [['value'], 'number'],
            [['when'], 'safe'],
	        [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currency_id' => 'id']],
            [['from'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['from' => 'id']],
            [['to'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['to' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'senderName' => 'Sender',
            'recipientName' => 'Recipient',
        ];
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
    public function getSender()
    {
        return $this->hasOne(Client::class, ['id' => 'from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(Client::class, ['id' => 'to']);
    }

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 * @throws BadRequestHttpException
	 */
	public function beforeSave( $insert ) {
        if(!$this->currency_id){ // if run not from webController
            $this->currency_id = $this->recipient->currency_id;
        }
	    if($this->from){ //if it money sending, not balance charging
			$senderMinus = $this->value * $this->sender->getConvertFactor($this->currency_id); //todo think about DRY
			if($this->sender->balance < $senderMinus) {
				throw new BadRequestHttpException('You haven\'t such many money');
			}
			$this->sender->balance -= $senderMinus;
			$this->sender->save();
		}
		return parent::beforeSave( $insert );
	}

	public function afterSave( $insert, $changedAttributes ) {
	    $this->refresh();
	    $this->recipient->balance += $this->value * $this->recipient->getConvertFactor($this->currency_id);
    	$this->recipient->save();
        parent::afterSave( $insert, $changedAttributes );
    }
}
