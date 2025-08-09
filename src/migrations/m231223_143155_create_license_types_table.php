<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%license_types}}`.
 */
class m231223_143155_create_license_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%license_types}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'value' => $this->string('15')->notNull(),
            'contract' => $this->text()->notNull(),
            'max_devices' => $this->integer()->notNull()->defaultValue(1),
            'status' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'license-type',
            'actions' => 'index;create;view;update;delete',
            'status'=>true
        ]);

        $this->insert('license_types', [
            'name' => 'Basic',
            'description' =>'Basic',
            'value' => 100,
            'contract' => 'Basic',
            'max_devices' => 1,
            'status' => 1,
        ]);

        $this->insert('license_types', [
            'name' => 'Pro',
            'description' =>'Pro',
            'value' => 300,
            'contract' => 'Pro',
            'max_devices' => 1,
            'status' => 1,
        ]);
        
        $this->insert('license_types', [
            'name' => 'Master',
            'description' =>'Master',
            'value' => 600,
            'contract' => 'Master',
            'max_devices' => 1,
            'status' => 1,
        ]);

        $this->insert('menus', [
            'id'=> 124,
            'menu_id' => 12,
            'label'   => 'License Types',
            'icon_style'=> 'fas',
            'icon'    => 'fas fa-certificate',
            'visible' => 'license-type;index',
            'url'     => '/license-type/index',
            'path'  => 'croacworks/controllers',
            'active'  => 'license-type',
            'order'   => 5,
            'status'  => true
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%license_types}}');
    }
}
