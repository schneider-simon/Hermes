<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 14.09.14
 * Time: 18:06
 */

namespace Triggerdesign\Hermes\Models;


class EloquentBase extends \Eloquent {
    protected $table = "";


    public static function tableName($name){
    	if($name == 'users'){
    		return Config::get('hermes::usersTable', 'users');
    	}    	
    	
        $prefix = \Config::get('hermes::tablePrefix', '');

      
        
        return $prefix . $name;
    }
    
    public static function modelPath($classname){
    	
    }


    function __construct(){
        //Table name is set as a protected value und the models
        $baseTableName = $this->table;

        //Rewrite with the correct prefix
        $this->table = static::tableName($baseTableName);
    }

    protected function getUser($user){
        if(!$user)  $user = \Auth::user();

        if(!$user){
            throw new \Exception('It is not possible to add a message without a valid user or login.');
        }

        return $user;
    }
} 