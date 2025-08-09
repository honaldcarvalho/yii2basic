<?php

namespace croacworks\yii2basic\controllers\rest;
use croacworks\yii2basic\controllers\AuthorizationController;

/**
 * 
 Example of setting up a cron job:
 0 0 * * * /usr/bin/php /path/to/your/instagram_media_fetch.php

 */
class InstagramController extends AuthorizationController {

    public function actionRefreshToken(){
        $this->refreshToken();
    }

    public function actionLoadMedias(){
        $this->saveMediaToDatabase();
    }

}
