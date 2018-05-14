<?php

use yii\db\Migration;

/**
 * Handles the creation of table `city`.
 * Has foreign keys to the tables:
 *
 * - `country`
 */
class m180512_103838_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('city', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'country_id' => $this->integer()->unsigned()->notNull(),
        ]);

        // creates index for column `country_id`
        $this->createIndex(
            'idx-city-country_id',
            'city',
            'country_id'
        );

        // add foreign key for table `country`
        $this->addForeignKey(
            'fk-city-country_id',
            'city',
            'country_id',
            'country',
            'id',
            'CASCADE'
        );

        $this->insert('city', ['name' => 'limassol', 'country_id' => 1]);
        $this->insert('city', ['name' => 'moscow', 'country_id' => 2]);
        $this->insert('city', ['name' => 'ekaterinburg', 'country_id' => 2]);
        $this->insert('city', ['name' => 'los angeles', 'country_id' => 3]);
        $this->insert('city', ['name' => 'new york', 'country_id' => 3]);
        $this->insert('city', ['name' => 'tokyo', 'country_id' => 4]);
        $this->insert('city', ['name' => 'nicosia', 'country_id' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `country`
        $this->dropForeignKey(
            'fk-city-country_id',
            'city'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            'idx-city-country_id',
            'city'
        );

        $this->dropTable('city');
    }
}
