<?php

use yii\db\Migration;

/**
 * Handles the creation of table `client`.
 * Has foreign keys to the tables:
 *
 * - `currency`
 */
class m180512_160843_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('client', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'city_id' => $this->integer()->unsigned()->notNull(),
            'balance' => $this->decimal(13,2)->defaultValue(0)->notNull(),
            'currency_id' => $this->integer()->unsigned()->notNull(),
        ]);

        // creates index for column `city_id`
        $this->createIndex(
            'idx-client-city_id',
            'client',
            'city_id'
        );

        // add foreign key for table `city`
        $this->addForeignKey(
            'fk-client-city_id',
            'client',
            'city_id',
            'city',
            'id',
            'CASCADE'
        );

        // creates index for column `currency_id`
        $this->createIndex(
            'idx-client-currency_id',
            'client',
            'currency_id'
        );

        // add foreign key for table `currency`
        $this->addForeignKey(
            'fk-client-currency_id',
            'client',
            'currency_id',
            'currency',
            'id',
            'CASCADE'
        );

        $this->insert('client', ['name' => 'Mike', 'city_id' => 2, 'currency_id' => 3]);
        $this->insert('client', ['name' => 'Daniel', 'city_id' => 1, 'currency_id' => 2]);
        $this->insert('client', ['name' => 'Nastia', 'city_id' => 4, 'currency_id' => 1]);
        //$this->insert('client', ['name' => 'Bank', 'city_id' => 1, 'currency_id' => 1]);
        //$this->update('client', ['id' => 0], ['name' => 'Bank']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `city`
        $this->dropForeignKey(
            'fk-client-city_id',
            'client'
        );

        // drops index for column `city_id`
        $this->dropIndex(
            'idx-client-city_id',
            'client'
        );

        // drops foreign key for table `currency`
        $this->dropForeignKey(
            'fk-client-currency_id',
            'client'
        );

        // drops index for column `currency_id`
        $this->dropIndex(
            'idx-client-currency_id',
            'client'
        );

        $this->dropTable('client');
    }
}
