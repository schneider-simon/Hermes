<?php
/**
 * Project: Luxus Point | ConversationManager.php
 * User: Simon - triggerdesign.de
 * Date: 14.09.14
 * Time: 21:50
 */

namespace Triggerdesign\Hermes\Models;

use Triggerdesign\Hermes\Models\Conversation as HermesConversation;

//The facade stuff is in here ;)
class ConversationManager {

    /**
     * @param array $user_ids
     * @param array $arguments
     * @param bool $forceNew If set to true it will not search for existing 
     * @return Conversation
     */
    public function startConversation(array $user_ids, array $arguments = array(), $forceNew = false){
        if(!$forceNew){
            $existingConversations = $this->findConversations($user_ids, $arguments);

            if(!empty($existingConversations))
                return reset($existingConversations);
        }

        $newConversation = new HermesConversation();



        foreach($arguments as $column => $value){
            $newConversation->$column = $value;
        }

        $newConversation->save();


        foreach($user_ids as $user_id){
            $newConversation->users()->attach($user_id);
        }

        return $newConversation;
    }


    public function findConversations(array $user_ids, array $arguments = array(), $limit = 1){
        //TODO: Do this using eloquent    
        
    	if(!is_array($user_ids) || count($user_ids) < 2){
    		throw new \Exception('You need at least 2 users for a conversation', 1410783987);
    	}
    	
    	//Get all conversations of the first user and check if 
    	//the other users are in these conversations too.
    	
        $firstUser = \User::find($user_ids[0]);
        $conversations = $firstUser->conversations()->with('users')->get();   
               
        $filteredConversations = array();

        foreach($conversations as $conversation){

            if(count($conversation->users) != count($user_ids))
                return false;

            $argumentsFitting = true;
            foreach($arguments as $column => $value){
                if($conversation->$column != $value){
                    $argumentsFitting = false;
                    break;
                }
            }            
            
            
            

            if(!$argumentsFitting) continue;

            $usersFitting = true;
            foreach($conversation->users as $convUser){
                if(!in_array($convUser->id, $user_ids)){
                    $usersFitting = false;
                    break;
                }

            }

            if(!$usersFitting) continue;

            $filteredConversations[] = $conversation;

            if(count($filteredConversations) == $limit)
                break;
        }
        

        return $filteredConversations;


    }

    public function findByUsers($user_id1, $user_id2){
        return $this->findConversations(array($user_id1, $user_id2));
    }

} 