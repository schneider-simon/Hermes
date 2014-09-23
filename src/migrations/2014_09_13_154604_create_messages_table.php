<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends \Triggerdesign\Hermes\BaseMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName('messages'), function(Blueprint $table)
		{
			$table->increments('id');

			//Has to be nullable so we dont get an 1452 sql error, when users allready exist
            $table->integer('user_id')->unsigned()->nullable();
                
            	

            $table->integer('conversation_id')->unsigned();
            
            if($this->useForeignKeys()){
            	$table->foreign('user_id')->references('id')->on($this->usersTable());
            	$table->foreign('conversation_id')->references('id')->on($this->tableName('conversations'));	
            }
            	
            $table->text('content');

            $table->softDeletes();
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
		Schema::drop($this->tableName('messages'));
	}

}
