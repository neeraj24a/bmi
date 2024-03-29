<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\Users;

/**
 * Login form
 */
class LoginForm extends Model
{
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
            $user = Users::find()->where(['username' => $this->username])->one();
            $user->last_visit = date("Y-m-d H:i:s");
            if($user->update(false) !== false){
                $session = Yii::$app->session;
                $session->set('user-type', $user->type);
                $session->set('label', $user->recordlabel);
                $session->set('username', $user->username);
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            } else {
                throw new \yii\web\NotFoundHttpException("Something Went wrong Try Again.");
            }
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
            $this->_user = Users::findByUsername($this->username);
        }
        if ($this->_user !== null) {
            $session = Yii::$app->session;
            $session->set('user-type', $this->_user->type);
            $session->set('label', $this->_user->recordlabel);
            $session->set('username', $this->_user->username);
        }
        return $this->_user;
    }
}

