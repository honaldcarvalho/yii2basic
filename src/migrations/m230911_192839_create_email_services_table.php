<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%email_services}}`.
 */
class m230911_192839_create_email_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%email_services}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string()->notNull(),
            'scheme' => $this->string()->defaultValue('smtp')->notNull(),            
            'enable_encryption' => $this->integer()->defaultValue(0),
            'encryption' => $this->string()->defaultValue('tls'),
            'host' => $this->string()->notNull(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'port' => $this->integer()->notNull(),
        ]);

        $this->insert('email_services', [
            'description' => 'EMAIL',
            'scheme' => 'smtp',
            'enable_encryption'=>true,
            'encryption'=>'tls',
            'host' => 'smtp.email.com.br',
            'username' => 'suporte@email.com.br',
            'password' => '',
            'port' => '465'
        ]);

        $this->insert('menus', [
            'id'=> 18,
            'menu_id' => 1,
            'label'   => 'Email Services',
            'icon_style'=> 'fas',
            'icon'    => 'fas fa-envelope',
            'visible' => 'email-service;index',
            'url'     => '/email-service/index',
            'path'  => 'croacworks/controllers',
            'active'  => 'email-service',
            'order'   => 8,
            'status'  => true
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%email_services}}');
    }
}
