<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rate_history`.
 * Has foreign keys to the tables:
 *
 * - `currency`
 */
class m180512_160739_create_rate_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('rate_history', [
            'id' => $this->primaryKey()->unsigned(),
            'date' => $this->date()->notNull(),
            'currency_id' => $this->integer()->unsigned()->notNull(),
            'rate' => $this->float()->unsigned()->notNull(),
        ]);

        // creates index for column `currency_id`
        $this->createIndex(
            'idx-rate_history-currency_id',
            'rate_history',
            'currency_id'
        );

        // add foreign key for table `currency`
        $this->addForeignKey(
            'fk-rate_history-currency_id',
            'rate_history',
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
        // drops foreign key for table `currency`
        $this->dropForeignKey(
            'fk-rate_history-currency_id',
            'rate_history'
        );

        // drops index for column `currency_id`
        $this->dropIndex(
            'idx-rate_history-currency_id',
            'rate_history'
        );

        $this->dropTable('rate_history');
    }
}
