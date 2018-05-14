<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transction`.
 * Has foreign keys to the tables:
 *
 * - `client`
 * - `client`
 * - `currency`
 */
class m180512_160932_create_transction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transction', [
            'id' => $this->primaryKey(),
            'from' => $this->integer()->unsigned()->notNull(),
            'to' => $this->integer()->unsigned()->notNull(),
            'value' => $this->decimal(13,2)->notNull(),
            'currency_id' => $this->integer()->unsigned()->notNull()->defaultValue(1),
            'when' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `from`
        $this->createIndex(
            'idx-transction-from',
            'transction',
            'from'
        );

        // add foreign key for table `client`
        $this->addForeignKey(
            'fk-transction-from',
            'transction',
            'from',
            'client',
            'id',
            'CASCADE'
        );

        // creates index for column `to`
        $this->createIndex(
            'idx-transction-to',
            'transction',
            'to'
        );

        // add foreign key for table `client`
        $this->addForeignKey(
            'fk-transction-to',
            'transction',
            'to',
            'client',
            'id',
            'CASCADE'
        );

        // creates index for column `currency_id`
        $this->createIndex(
            'idx-transction-currency_id',
            'transction',
            'currency_id'
        );

        // add foreign key for table `currency`
        $this->addForeignKey(
            'fk-transction-currency_id',
            'transction',
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
            'fk-transction-from',
            'transction'
        );

        // drops index for column `from`
        $this->dropIndex(
            'idx-transction-from',
            'transction'
        );

        // drops foreign key for table `client`
        $this->dropForeignKey(
            'fk-transction-to',
            'transction'
        );

        // drops index for column `to`
        $this->dropIndex(
            'idx-transction-to',
            'transction'
        );

        // drops foreign key for table `currency`
        $this->dropForeignKey(
            'fk-transction-currency_id',
            'transction'
        );

        // drops index for column `currency_id`
        $this->dropIndex(
            'idx-transction-currency_id',
            'transction'
        );

        $this->dropTable('transction');
    }
}
