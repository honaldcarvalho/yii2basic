<?php 

namespace croacworks\yii2basic\commands;

use yii\console\Controller;

/**
 * USAGE: php yii load-query/run -f \\croacworks\\yii2basic\\migrations\\m241010_171508_always_run_migration
 * 
 */
class LoadQueryController extends Controller
{
    public $file_name;
    
    public function options($actionID)
    {
        return ['file_name'];
    }
    
    public function optionAliases()
    {
        return ['f' => 'file_name'];
    }

    public function actionRun()
    {
        $result = '';
        $sql = file_get_contents("{$this->file_name}");
        try {
            $result = \Yii::$app->db->createCommand($sql)->execute();
        } catch (\yii\db\Exception $e) {
            echo "Query error:" . $e->getMessage() . "\n";
        }
        echo "Query completed. Result: {$result}\n";
    }
}
