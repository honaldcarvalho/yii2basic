<?php

//namespace croacworks\yii2basic\migrations;
use yii\db\Migration;

/**
 * Class m241010_171508_always_run_migration
 */
class m241010_171508_always_run_migration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        echo "m241010_171508_always_run_migration!!!!\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241010_171508_always_run_migration cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241010_171508_always_run_migration cannot be reverted.\n";

        return false;
    }
    */
}

