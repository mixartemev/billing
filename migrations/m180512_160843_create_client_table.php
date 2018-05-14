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
            'city_id' => $this->integer()->unsigned(),
            'ballance' => $this->decimal(13,2)->defaultValue(0)->notNull(),
            'currency_id' => $this->integer()->unsigned()->notNull()->defaultValue(1),
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
