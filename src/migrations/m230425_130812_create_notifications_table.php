<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notifications}}`.
 */
class m230425_130812_create_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'group_id' => $this->integer(),
            'notification_message_id' => $this->integer()->notNull(),
            'description' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'send_email' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(false),
        ]);

        $this->addForeignKey(
            'fk-notifications-user_id',
            'notifications',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-notifications-notification_message_id',
            'notifications',
            'notification_message_id',
            'notification_messages',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-notifications-group_id',
            'notifications',
            'group_id',
            'groups',
            'id',
            'RESTRICT'
        );

        $this->insert('menus', [
            'id'=> 162,
            'menu_id' => 16,
            'label'   => 'Notifications',
            'icon_style'=> 'fas',
            'icon'    => 'fas fa-paper-plane',
            'visible' => 'notification;index',
            'url'     => '/notification/index',
            'path'  => 'croacworks/controllers',
            'active'  => 'notification',
            'order'   => 7,
            'status'  => true
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notifications}}');
    }
}
