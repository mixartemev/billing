<?php

use yii\db\Migration;

/**
 * Handles the creation of table `currency`.
 */
class m180511_160158_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('currency', [
            'id' => $this->primaryKey()->unsigned(),
            'symbol' => $this->string(3)->notNull(),
        ]);

        $this->insert('currency', ['symbol' => 'usd']);
        $this->insert('currency', ['symbol' => 'eur']);
        $this->insert('currency', ['symbol' => 'rub']);
        $this->insert('currency', ['symbol' => 'gbp']);
        $this->insert('currency', ['symbol' => 'cad']);
        $this->insert('currency', ['symbol' => 'jpy']);
        $this->insert('currency', ['symbol' => 'cny']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('currency');
    }
}
