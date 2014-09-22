<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 14.09.14
 * Time: 17:40
 */

namespace Triggerdesign\Hermes\Models;


class Conversation extends EloquentBase {

    protected $table = 'conversations';

    public function messages()
    {
        return $this->hasMany('Triggerdesign\Hermes\Models\Message')->orderBy('updated_at', 'desc')->with('user');
    }


    public function users()
    {
        return $this->belongsToMany('\User', parent::tableName('conversation_user'))->withTimestamps();
    }

    

    public function addMessage($content, \User $user = null){
        $user = $this->getUser($user);


        if(!$this->canWrite($user)){
        	return false;
        }
        
        $newMessage = new Message();

        $newMessage->user_id = $user->id;
        $newMessage->conversation_id = $this->id;
        $newMessage->content = $content;

        $newMessage->save();

        foreach($this->users as $convUser){
            $newMessageState = new MessageState();
            $newMessageState->user_id = $convUser->id;
            $newMessageState->message_id = $newMessage->id;
            $newMessageState->state = MessageState::indexOf('unread');

            if($user->id == $convUser->id)
                $newMessageState->state = MessageState::indexOf('own');

            $newMessageState->save();
        }

        return $newMessage;


    }
    
    public function addUser(\User $user){
    	$this->users()->attach($user->id);
    	$this->touch();
    }
    
    public function canWrite(\User $user){
    	$user = $this->getUser($user);
    	
    	foreach($this->users as $convUser){
    		if($convUser->id == $user->id) return true;
    	}
    	
    	return false;
    	
    }
    
    public function latestMessage(){
    	return $this->messages()->first();
    }
    
    public function unreadMessages(){
    	//TODO: Do this using eloquent


    	$unreadMessages = array();
    	foreach($this->messages as $message){
    		if($message->isUnread()){
    			$unreadMessages[] = $message;
    		}
    	}


    	
    	return $unreadMessages;
    }
    
    public function isUnread(){
    	return count($this->unreadMessages()) > 0;
    }
    
    
    public function doRead(){
		foreach($this->unreadMessages() as $unreadMessage){
			$unreadMessage->doRead();
		}
		
		return true;
    }

    public function buildGroups(){
        return \Triggerdesign\Hermes\Classes\MessageGroup::buildGroups($this);
    }


}