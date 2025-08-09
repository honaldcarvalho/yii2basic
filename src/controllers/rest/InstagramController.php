<?php

namespace croacworks\yii2basic\controllers\rest;
use croacworks\yii2basic\controllers\rest\AuthorizationController;
use croacworks\yii2basic\models\InstagramMedia;
use croacworks\yii2basic\models\Parameter;

/**
 * 
 Example of setting up a cron job:
 0 0 * * * /usr/bin/php /path/to/your/instagram_media_fetch.php

 */
class InstagramController extends AuthorizationController {

     public function actionRefreshToken(){
        return InstagramMedia::refreshToken();
    }

    public function actionLoadMedias(){
        return InstagramMedia::saveMediaToDatabase(true);
    }

    public function actionListMedias(){
        return InstagramMedia::find()->all();
    }
    
    public function actionFetchMedias(){
        return InstagramMedia::fetchInstagramMedia(true);
    }

}