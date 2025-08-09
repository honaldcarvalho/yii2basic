<?php

namespace croacworks\yii2basic\models;

use croacworks\yii2basic\controllers\AuthorizationController;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{

    public $verGroup = false;

    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remember')
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $user = User::find()->where(['username'=>$this->username])->orWhere(['email'=>$this->username])->one();
            
            if ($user !== null){
                $extends = '+30 days';
                $expires = strtotime($extends, strtotime($user->token_validate));
                if( $expires < time() ||  $user->access_token == null){
                    $user->scenario = User::SCENARIO_AUTH;
                    $user->access_token = Yii::$app->security->generateRandomString();
                    $user->token_validate = date('Y-m-d H:i:s',strtotime('now'));
                    if(!$user->save()){
                        throw new \yii\web\ServerErrorHttpException();
                    }
                    
                }
                $user_group = $user->getUserGroups()->where(['<>','group_id',1])->one();
                Yii::$app->session->set('group',$user_group->group);
            }
            $this->_user = $user;
        }

        return $this->_user;
    }
}