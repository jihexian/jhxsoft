<?php
class Member extends \common\models\Member{
    
    public $verifyPassword;
    
    public function rules(){
        return [
            ['username', 'string', 'on' => 'search'],
            ['username', 'required', 'on' => 'create'],
            ['username', 'unique', 'on' => 'create'],
            ['email', 'required', 'on' => 'create'],
            ['email', 'unique', 'on' => 'create'],
            ['password', 'required', 'on' => ['register']],
        ];
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return array_merge($scenarios, [
            'register' => ['username', 'email', 'password'],
            'connect'  => ['username', 'email'],
            'create'   => ['username', 'email', 'password'],
            'update'   => ['username', 'email', 'password'],
            'settings' => ['username', 'email', 'password'],
            'resetPassword' => ['password']
        ]);
    }
}