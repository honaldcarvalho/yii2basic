<?php

use yii\db\Migration;

/**
 * Class m240807_161427_add_admin_rules
 */
class m240807_161427_add_admin_rules extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'util',
            'path' => 'croacworks/controllers',
            'actions' => 'save-model;status-model;get-model;get-models;save-model;status-model;remove-model',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'site',
            'path' => 'app',
            'actions' => 'index;dashboard',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'site',
            'path' => 'croacworks/controllers',
            'actions' => 'index;dashboard',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'menu',
            'path' => 'croacworks/controllers',
            'actions' => 'create;delete;index;order-menu;update;view;get-model;save-model;status-model;remove-model',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'folder',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete;edit;add;get-model;save-model;status-model;remove-model',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'file',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete;list;upload;move;remove-file;delete-files;form;send;edit;add',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'group',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'language',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'message',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'source-message',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete;add-translation;get-model;save-model;status-model;remove-model',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'user',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete;add-group;remove-group;profile;edit;change-lang;change-theme;get-model;save-model;status-model;remove-model',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'rule',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'log',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'email-service',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete;test',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'configuration',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete;get-model;save-model;status-model;remove-model',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'license-type',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'license',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'page',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'section',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete;get-model;save-model;status-model;remove-model',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'notification',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'notification-message',
            'path' => 'croacworks/controllers',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240807_161427_add_admin_rules cannot be reverted.\n";

        return false;
    }
}
