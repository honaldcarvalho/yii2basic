<?php

namespace croacworks\yii2basic\controllers\rest;
use croacworks\yii2basic\controllers\rest\AuthorizationController;
use croacworks\yii2basic\models\YoutubeMedia;

/**
 * 
 Example of setting up a cron job:
 0 0 * * * /usr/bin/php /path/to/your/instagram_media_fetch.php

 */
class YoutubeController extends AuthorizationController {

    public function actionLoadMedias(){
        return YoutubeMedia::get_channel_videos(false);
    }

    public function actionListMedias(){
        return YoutubeMedia::find()->all();
    }
    

}
