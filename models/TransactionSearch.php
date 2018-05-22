<?php

namespace app\models;

use app\controllers\SortAddable;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TransactionSearch represents the model behind the search form of `app\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
	public $clientId, $beginPeriod, $endPeriod;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clientId'], 'integer'],
            [['beginPeriod','endPeriod'], 'safe'],
	        [['clientId'], 'required']
        ];
    }

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'clientId' => 'Client',
		];
	}

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
	    $this->load($params);

//SELECT `transaction`.`id`, `from`, `to`, concat(value, currency.symbol) as transVal,
//IF(1=`transaction`.currency_id, value, value /*($client_rate_on_transDate)*/ /trans_rate.rate) as clientVal,
//`transaction`.currency_id, trans_rate.rate, `when`
//FROM `transaction`
//LEFT JOIN `client` `from` ON `transaction`.`from` = `from`.id
//LEFT JOIN `client` `to` ON `transaction`.`to` = `to`.id
//LEFT JOIN `currency` ON `transaction`.currency_id = currency.id
//LEFT JOIN (SELECT currency_id, rate, date FROM rate_history) `trans_rate`
//     ON `trans_rate`.currency_id = currency.id AND `trans_rate`.date = DATE_FORMAT(`when`, '%Y-%m-%d')
//WHERE ((`from` =1) OR (`to` =1)) AND (`when` >= '2018-05-20 00:00:00')

	    $query = Transaction::find()
		    ->select([
		    	'transaction.id',
			    'senderName' => 'IFNULL(from.name, "(FROM BANK)")',
			    'recipientName' => 'to.name',
			    'value',
			    'cur' => 'currency.symbol',
			    'when'
		    ])
		    ->leftJoin('`client` `from`','`transaction`.`from` = `from`.id')
		    ->leftJoin('`client` `to`', '`transaction`.`to` = `to`.id')
		    ->leftJoin('currency', '`transaction`.currency_id = currency.id')
		    ->where(['OR', '`from` = ' . $this->clientId, '`to` = ' . $this->clientId])
	    ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

	    SortAddable::addSort($dataProvider->sort->attributes, ['senderName' ,'recipientName' ,'cur']);

        // grid filtering conditions
        $query->andFilterWhere(['>=', 'when', $this->beginPeriod ? $this->beginPeriod . ' 00:00:00' : null]);
        $query->andFilterWhere(['<=', 'when', $this->endPeriod ? $this->endPeriod . ' 23:59:59' : null]);

        return $dataProvider;
    }
}
