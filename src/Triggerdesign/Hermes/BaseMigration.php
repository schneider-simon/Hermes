<?php

namespace Triggerdesign\Hermes;

use Config;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BaseMigration extends Migration {

    protected function tableName($name){
        $prefix = Config::get('hermes::tablePrefix', '');

        return $prefix . $name;
    }

    protected function usersTable(){
        $usersTableConfig = Config::get('hermes::usersTable', 'users');


        return $usersTableConfig;
    }

}


?>