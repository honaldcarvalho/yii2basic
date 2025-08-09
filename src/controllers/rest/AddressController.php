<?php

namespace croacworks\yii2basic\controllers\rest;
use Yii;
use croacworks\yii2basic\controllers\rest\ControllerCustom;
use croacworks\yii2basic\models\City;
use croacworks\yii2basic\models\State;

class AddressController extends AuthorizationController {
    
    public function __construct($id, $module, $config = array())
    {
        parent::__construct($id, $module, $config);
        $this->free = ['cities', 'states'];
    }

        public function actionCities(){
            $body = Yii::$app->request->getBodyParams();
        return City::findAll(['state_id'=>$body['state_id'], 'status'=>1]);
    }

    public function actionStates(){
        return State::findAll(['status'=>1]);
    }

}