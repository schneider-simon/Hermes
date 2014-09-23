<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageStatesTable extends \Triggerdesign\Hermes\BaseMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName('message_states'), function(Blueprint $table)
		{
			$table->increments('id');

			//Has to be nullable so we dont get an 1452 sql error, when users allready exist
            $table->integer('user_id')->unsigned()->nullable();           

            $table->integer('message_id')->unsigned();           

            if($this->useForeignKeys()){
            	$table->foreign('user_id')->references('id')->on($this->usersTable());
            	$table->foreign('message_id')->references('id')->on($this->tableName('messages'));
            }
            
            $table->integer('state');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop($this->tableName('message_states'));
	}

}
