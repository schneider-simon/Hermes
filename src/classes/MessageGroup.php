<?php
/**
 * Created by PhpStorm.
* User: Simon
* Date: 14.09.14
* Time: 17:40
*/

namespace Triggerdesign\Hermes\Classes;

class MessageGroup{



	public static $groupHours = 8;
	
	protected $messages;	
	
	protected $startTime;	
	
	protected $user;
	
	protected $endTime;
	
	public function __construct(){
		
	}
	
	public function setMessages($messages){
		$this->messages = $messages;

        $this->calculateTimespan();
	}
	
	public function getMessages(){
		return $this->messages;
	}
	
	public function addMessage($message, $calculateTimespan = true){
		$this->messages[] = $message;
		
		$this->calculateTimespan();
	}
	
	
	public function setUser($user){
		$this->user = $user;
	}
	
	public function getUser(){
		return $this->user;
	}

    public function getStart(){
        return $this->startTime;
    }

    public function getEnd(){
        return $this->endTime;
    }


    private function calculateTimespan(){
		
		$newestMessage = null;
		$oldestMessage = null;
		
		foreach($this->messages as $message){
			if($newestMessage == null || $message->updated_at->gt($newestMessage->updated_at)){
				$newestMessage = $message;
			}
			
			if($oldestMessage == null || !$message->updated_at->gt($oldestMessage->updated_at)){
				$oldestMessage = $message;
			}
		}
		
		$this->startTime = $newestMessage->updated_at;
		$this->endTime = $oldestMessage->updated_at;
		
		
	}
	
	public function getEndTime(){
		return $this->endTime;		
	}
	
	public function getStartTime(){
		return $this->startTime;
	}
	
	public static function buildGroups($conversation){
		
		$allMessages = $conversation->messages->reverse();
		$groups = [];
		
		$currentGroup = null;
		$lastMessage = null;
		
		foreach($allMessages as $message){
			$createNewGroup = false;

			//Check if we should start a new group
			if($lastMessage == null)
				$createNewGroup = true;
			else if($lastMessage->user->id != $message->user->id)
				$createNewGroup = true;
			else if($message->updated_at->diffInHours($currentGroup->startTime) > static::$groupHours)
				$createNewGroup = true;
			
			//New user or time since start of the group is too long -> create the next one
			if($createNewGroup){
                if($currentGroup != null)
					$groups[] = clone $currentGroup;
				
				$currentGroup = new \Triggerdesign\Hermes\Classes\MessageGroup();
				$currentGroup->user = $message->user;
			}


			//Add the message to the current Group we ae working with
			$currentGroup->addMessage($message);
			
			$lastMessage = $message;

		}

        if($currentGroup !== null)
        $groups[] = clone $currentGroup;
		
		return $groups;
		
	}
	
}


?>