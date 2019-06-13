<?php
namespace frontend\models;

use yii\base\Model;
use backend\models\Users;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\backend\models\Users', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            /*['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            */
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new Users();
        $user->username = $this->username;
//        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->role = 'admin';
        $user->relation = '5e698be7-1cc5-11e8-88d9-00e04ca8063e';
        $user->id = create_guid();
        $user->created_by = $user->id;
        $user->modified_by = $user->id;
        $user->status = 1;
        $user->deleted = 0;
        $user->date_entered = date("Y-m-d H:i:s");
        $user->date_modified = date("Y-m-d H:i:s");
//        pre($user, true);
        if($user->save()){
            return $user;
        } else {
            pre($user->getErrors(), true);
        }
//        return $user->save() ? $user : null;
    }
}
