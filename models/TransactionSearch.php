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
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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

	    $query = Transaction::find()
		    ->select([
		    	'transaction.id',
			    'sender' => 'IFNULL(from.name, "(FROM BANK)")',
			    'recipient' => 'to.name',
			    'value',
			    'currencyName' => 'currency.symbol',
			    'when'
		    ])
		    ->leftJoin('`client` `from`','`transaction`.`from` = `from`.id')
		    ->leftJoin('`client` `to`', '`transaction`.`to` = `to`.id')
		    ->leftJoin('currency', '`transaction`.currency_id = currency.id')
		    ->where(['OR', '`from` = ' . $this->clientId, '`to` = ' . $this->clientId])
	    ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

	    SortAddable::addSort($dataProvider->sort->attributes, ['sender' ,'recipient' ,'currencyName']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['>=', 'when', $this->beginPeriod ? $this->beginPeriod . ' 00:00:00' : null]);
        $query->andFilterWhere(['<=', 'when', $this->endPeriod ? $this->endPeriod . ' 23:59:59' : null]);

        return $dataProvider;
    }
}
