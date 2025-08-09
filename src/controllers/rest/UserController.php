<?php

namespace croacworks\yii2basic\controllers\rest;
use Yii;
use croacworks\yii2basic\controllers\rest\Controller;
use croacworks\yii2basic\models\User;
use croacworks\yii2basic\models\Configuration;
use croacworks\yii2basic\models\UserGroup;

class UserController extends AuthorizationController{

    public $modelClass = 'croacworks\yii2basic\models\User';

    public function actionList(){
        return User::findAll(['status'=>10]);
    }

    public function actionView($id){
        return User::findOne($id);
    }
    
    public function actionSignup()
    {
        if (!$this->validate()) {
            return null;
        }
        $params = Configuration::get();
        $user = new User();
        $user_group = new UserGroup();
        $user_group->group_id = $params->group_id;
        $user->fullname = $this->fullname;

        $name_split = explode(' ',$user->fullname);
        $username = $name_split[0].(isset($name_split[1]) ? ' ' .end($name_split) : '');
        $user->username = $this->sanatizeReplace(strtolower($username)).'_'.date('ymdhims');
        $user->email = $this->email;
        $user->company = $this->company;
        $user->cpf_cnpj = $this->cpf_cnpj;
        $user->phone = $this->phone;
        $user->language_id = $this->language_id;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if($user->save()){
            $user_group->user_id = $user->id;
            $user_group->save();
            return $this->sendEmail($user,$params);
        }

    }

}