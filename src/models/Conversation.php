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
        return $this->hasMany('Triggerdesign\Hermes\Models\Message')->orderBy('updated_at', 'desc');
    }


    public function users()
    {
        return $this->belongsToMany('\User', parent::tableName('conversation_user'))->withTimestamps();
    }

    

    public function addMessage($content, \User $user = null){
        $user = $this->getUser($user);


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
    
    public function latestMessage(){
    	return $this->messages()->first();
    }
    
    public function unreadMessages(){
    	$unreadMessages = array();
    	foreach($this->messages() as $message){
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


}