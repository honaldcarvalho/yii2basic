<?php

use yii\db\Migration;

/**
 * Corrige o namespace dos controllers gravados em `roles.controller`
 * de 'weebz\yii2basics' para 'croacworks\yii2basic'.
 */
class m250809_140000_fix_roles_controller_namespace extends Migration
{
    public function safeUp()
    {
        $this->updateNamespaces('weebz\\yii2basics', 'croacworks\\yii2basic');
    }

    public function safeDown()
    {
        $this->updateNamespaces('croacworks\\yii2basic', 'weebz\\yii2basics');
    }

    private function updateNamespaces(string $from, string $to): void
    {
        $sql = <<<SQL
UPDATE {{%roles}}
SET [[controller]] = REPLACE([[controller]], :from, :to)
WHERE [[controller]] LIKE :prefix
SQL;

        $count = $this->db->createCommand($sql, [
            ':from'   => $from,
            ':to'     => $to,
            ':prefix' => $from . '\\%'  // ex.: weebz\yii2basics\%
        ])->execute();

        echo "Atualizados {$count} registro(s) em roles.controller.\n";
    }
}
