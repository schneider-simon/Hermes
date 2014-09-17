<?php

namespace Triggerdesign\Hermes\Models;


trait UserTrait {

    public function foo(){
        return "bar";
    }

    public function conversations()
    {
        return $this->belongsToMany('Conversation', EloquentBase::tableName('conversation_user'))->withTimestamps()->orderBy('updated_at', 'desc');
    }
    
	public function messageStates()
    {
        return $this->hasMany('Triggerdesign\Hermes\Models\MessageState')->orderBy('updated_at', 'desc');
    }
    
    public function unreadMessageStates(){
    	return $this->messageStates()->where('state', '=', 0)->orderBy('updated_at', 'desc')->get();
    }

    public function unreadMessagesCount(){
        return count($this->unreadMessageStates());
    }

    public function hasUnreadMessages(){
        return $this->unreadMessagesCount() > 0;
    }
    
    public function unreadConversations(){
    	//simon: is it possible to do this using Eloquent?
    	$unreadConversations = array();
    	foreach($this->unreadMessages() as $unreadMessage){
    		$unreadConversation = $unreadMessage->conversation;
    		$conversationId = $unreadConversation->id;
    		 
    		if(isset($unreadConversations[$conversationId])) continue;
    		 
    		$unreadConversations[$conversationId] = $unreadConversation;
    	}
      	return $unreadConversations;    	 
    }

    public function unreadMessages(){ 	
    	return $this->findMessages('unread');
    }
    
    public function findMessages($state = false){
    	$user_id = $this->id;
    	 
    	$unreadMessages = Message::whereHas('messageStates', function($q) use( &$user_id, &$state)
    	{
    		$q->where('user_id', '=', $user_id);
    		
    		if($state)
    		$q->where('state', '=', MessageState::indexOf($state));
    		 
    	})->with('conversation')->get();
    	 
    	return $unreadMessages;
    }
    
   
    
    
    
}