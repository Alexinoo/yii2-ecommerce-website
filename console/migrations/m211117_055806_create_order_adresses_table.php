<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_adresses}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%orders}}`
 */
class m211117_055806_create_order_adresses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_adresses}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'adresses' => $this->string(255)->notNull(),
            'city' => $this->string(255)->notNull(),
            'state' => $this->string(255)->notNull(),
            'country' => $this->string(255)->notNull(),
            'zipcode' => $this->string(255)->notNull(),
        ]);

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-order_adresses-order_id}}',
            '{{%order_adresses}}',
            'order_id'
        );

        // add foreign key for table `{{%orders}}`
        $this->addForeignKey(
            '{{%fk-order_adresses-order_id}}',
            '{{%order_adresses}}',
            'order_id',
            '{{%orders}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%orders}}`
        $this->dropForeignKey(
            '{{%fk-order_adresses-order_id}}',
            '{{%order_adresses}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-order_adresses-order_id}}',
            '{{%order_adresses}}'
        );

        $this->dropTable('{{%order_adresses}}');
    }
}
