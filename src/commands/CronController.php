<?php 

namespace croacworks\yii2basic\commands;

use croacworks\yii2basic\models\InstagramMedia;
use croacworks\yii2basic\models\YoutubeMedia;
use yii\console\Controller;

/**
 * RUN
 * 
 php yii cron/itoken -gi=2
 php yii cron/iload -gi=2
 php yii cron/yload -gi=2
 *
 */

class CronController extends Controller
{
    public $group_id;

    public function options($actionID)
    {
        return ['group_id'];
    }

    public function optionAliases()
    {
        return ['gi' => 'group_id'];
    }

    public function actionIload()
    {
        InstagramMedia::saveMediaToDatabase(true,$this->group_id);
    }

    public function actionItoken()
    {
        InstagramMedia::refreshToken();
    }

    public function actionYload()
    {
        YoutubeMedia::get_channel_videos(true,$this->group_id);
    }
}
