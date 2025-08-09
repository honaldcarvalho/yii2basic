<?php
namespace croacworks\yii2basic\controllers\rest;
use Yii;
use croacworks\yii2basic\controllers\rest\Controller ;
use croacworks\yii2basic\models\Log;
use croacworks\yii2basic\models\User;

class ToolsController extends AuthorizationController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'send-log' => ['POST'],
            ],
        ];
        return $behaviors;
    }
    
    // public function security($groups){
    //     if(!\croacworks\yii2basic\models\Usuario::inGrupos($groups)){
    //         throw new \yii\web\ForbiddenHttpException();
    //     }     
    // }
    
    public function actionSendLog()
    {

        $body = Yii::$app->request->getBodyParams();

        $model = new Log();
        $model->user_id = $body['user_id'];
        $model->action = 'send-log';
        $model->controller = 'tools';
        $model->data = $body['data'];
        $model->save();

        return Log::findOne($model->id);

    }


}  
    
?>