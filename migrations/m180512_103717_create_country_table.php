<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country`.
 */
class m180512_103717_create_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('country', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'currency_id' => $this->integer()->unsigned(),
        ]);

        // creates index for column `currency_id`
        $this->createIndex(
            'idx-country-currency_id',
            'country',
            'currency_id'
        );

        // add foreign key for table `currency`
        $this->addForeignKey(
            'fk-country-currency_id',
            'country',
            'currency_id',
            'currency',
            'id',
            'CASCADE'
        );

        $this->insert('country', ['name' => 'Cyprus', 'currency_id' => 2]);
        $this->insert('country', ['name' => 'Russia', 'currency_id' => 3]);
        $this->insert('country', ['name' => 'USA', 'currency_id' => 1]);
        $this->insert('country', ['name' => 'Japan', 'currency_id' => 6]);
        $this->insert('country', ['name' => 'China', 'currency_id' => 7]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `currency`
        $this->dropForeignKey(
            'fk-country-currency_id',
            'country'
        );

        // drops index for column `currency_id`
        $this->dropIndex(
            'idx-country-currency_id',
            'country'
        );

        $this->dropTable('country');
    }
}
