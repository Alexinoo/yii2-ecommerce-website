<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_adresses}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m211116_185832_create_user_adresses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_adresses}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'address' => $this->string(255)->notNull(),
            'city' => $this->string(255)->notNull(),
            'state' => $this->string(255)->notNull(),
            'country' => $this->string(255)->notNull(),
            'zipcode' => $this->string(255),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_adresses-user_id}}',
            '{{%user_adresses}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_adresses-user_id}}',
            '{{%user_adresses}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
                $this->dropTable('{{%user_adresses}}');
    }
}
