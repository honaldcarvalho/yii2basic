<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sections}}`.
 */
class m230520_202200_create_sections_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sections}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer(),
            'section_id' => $this->integer()->defaultValue(null),
            'name' => $this->string()->notNull(),
            'uri' => $this->string()->defaultValue('#')->notNull(),
            'status' => $this->integer()->defaultValue(1)
        ]);

        $this->addForeignKey(
            'fk-sections-section_id',
            'sections',
            'section_id',
            'sections',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-sections-group_id',
            'sections',
            'group_id',
            'groups',
            'id',
            'RESTRICT'
        );

        $this->insert('menus', [
            'id'=> 17,
            'menu_id' => 1,
            'label'   => 'Dinamic Pages',
            'icon_style'=> 'fas',
            'icon'    => 'fas fa-copy',
            'visible' => 'page;index',
            'url'     => '#',
            'path'  => 'croacworks/controllers',
            'active'  => 'page',
            'order'   => 3,
            'status'  => true
        ]);

        $this->insert('menus', [
            'id'=> 171,
            'menu_id' => 17,
            'label'   => 'Sections',
            'icon_style'=> 'fas',
            'icon'    => 'fas fa-ellipsis-v',
            'visible' => 'section;index',
            'url'     => '/section/index',
            'path'  => 'croacworks/controllers',
            'active'  => 'section',
            'order'   => 3,
            'status'  => true
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sections}}');
    }
}
