<?php

use croacworks\yii2basic\models\Menu;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_methods}}`.
 */
class m231228_161615_create_payment_methods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_methods}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string()->notNull(),
            'icon' => $this->string()->notNull(),
            'tag' => $this->string()->notNull(),
            'status' => $this->string()->defaultValue(0)
        ]);

        $this->insert('rules', [
            'group_id' => 2,
            'controller' => 'payment-method',
            'actions' => 'index;create;view;update;delete;status',
            'status'=>true
        ]);

        $this->insert('payment_methods', [
            'id'=>1,
            'description' => 'Free',
            'icon' => 'fas fa-hand-holding-usd',
            'tag' => 'free',
            'status'=>false
        ]);

        $this->insert('payment_methods', [
            'id'=>2,
            'description' => 'Cash',
            'icon' => 'fas fa-money-bill-wave',
            'tag' => 'cash',
            'status'=>true
        ]);

        $this->insert('payment_methods', [
            'id'=>3,
            'description' => 'Pix',
            'icon' => 'fas fa-qrcode',
            'tag' => 'pix',
            'status'=>true
        ]);

        $this->insert('payment_methods', [
            'id'=>4,
            'description' => 'Credit',
            'icon' => 'fas fa-credit-card',
            'tag' => 'credit',
            'status'=>false
        ]);

        $this->insert('payment_methods', [
            'id'=>5,
            'description' => 'Debit',
            'icon' => 'far fa-credit-card',
            'tag' => 'debit',
            'status'=>false
        ]);

        $this->insert('payment_methods', [
            'id'=>6,
            'description' => 'Transfer',
            'icon' => 'fas fa-exchange-alt',
            'tag' => 'transfer',
            'status'=>true
        ]);

        $this->insert('payment_methods', [
            'id'=>7,
            'description' => 'Barcode',
            'icon' => 'fas fa-barcode',
            'tag' => 'barcode',
            'status'=>true
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment_methods}}');
    }
}
