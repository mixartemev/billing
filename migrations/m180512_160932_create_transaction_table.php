<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transaction`.
 * Has foreign keys to the tables:
 *
 * - `client`
 * - `client`
 * - `currency`
 */
class m180512_160932_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transaction', [
            'id' => $this->primaryKey(),
            'from' => $this->integer()->unsigned(),
            'to' => $this->integer()->unsigned()->notNull(),
            'value' => $this->decimal(13,2)->notNull(),
            'currency_id' => $this->integer()->unsigned()->notNull(),
            'when' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `from`
        $this->createIndex(
            'idx-transaction-from',
            'transaction',
            'from'
        );

        // add foreign key for table `client`
        $this->addForeignKey(
            'fk-transaction-from',
            'transaction',
            'from',
            'client',
            'id',
            'CASCADE'
        );

        // creates index for column `to`
        $this->createIndex(
            'idx-transaction-to',
            'transaction',
            'to'
        );

        // add foreign key for table `client`
        $this->addForeignKey(
            'fk-transaction-to',
            'transaction',
            'to',
            'client',
            'id',
            'CASCADE'
        );

        // creates index for column `currency_id`
        $this->createIndex(
            'idx-transaction-currency_id',
            'transaction',
            'currency_id'
        );

        // add foreign key for table `currency`
        $this->addForeignKey(
            'fk-transaction-currency_id',
            'transaction',
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
        // drops foreign key for table `client`
        $this->dropForeignKey(
            'fk-transaction-from',
            'transaction'
        );

        // drops index for column `from`
        $this->dropIndex(
            'idx-transaction-from',
            'transaction'
        );

        // drops foreign key for table `client`
        $this->dropForeignKey(
            'fk-transaction-to',
            'transaction'
        );

        // drops index for column `to`
        $this->dropIndex(
            'idx-transaction-to',
            'transaction'
        );

        // drops foreign key for table `currency`
        $this->dropForeignKey(
            'fk-transaction-currency_id',
            'transaction'
        );

        // drops index for column `currency_id`
        $this->dropIndex(
            'idx-transaction-currency_id',
            'transaction'
        );

        $this->dropTable('transaction');
    }
}
