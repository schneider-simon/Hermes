<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 14.09.14
 * Time: 17:40
 */

namespace Triggerdesign\Hermes\Models;


use Illuminate\Database\Eloquent\Model;


class Message extends EloquentBase{

    protected $table = "messages";
    protected $touches = array('conversation');


    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function conversation()
    {
        return $this->belongsTo(static::modelPath('Conversation'));
    }

    public function messageStates()
    {
        return $this->hasMany(static::modelPath('MessageState'));
    }


    public function messageState(Model $byUser = null){
        $byUser = $this->getUser($byUser);

        $userStates = $this->messageStates()->where('user_id', '=', $byUser->id)->get()->first();

        return $userStates;
    }

    public function changeState($newStateKey = "read", Model $byUser = null){
        $messageState = $this->messageState($byUser);

        if(!$messageState)
            return false;

        $newStateIndex = MessageState::indexOf($newStateKey);

        if($messageState->state == $newStateIndex)
            return $messageState;
        else {
            $messageState->state = $newStateIndex;
            $messageState->save();
        }
    }

    public function doRead(Model $user = null){
        return $this->changeState('read', $user);
    }

    public function doDelete(Model $byUser = null){
        return $this->changeState('delete', $user);
    }

    public function isRead(Model $byUser = null){
        $messageState = $this->messageState($byUser);

        $notReadState = MessageState::indexOf('unread');
        return $messageState->state != $notReadState;
    }

    public function isUnread(Model $byUser = null){
        $messageState = $this->messageState($byUser);

        $notReadState = MessageState::indexOf('unread');

        return $messageState->state == $notReadState;
    }

    public function isDeleted(Model $byUser = null){
        $messageState = $this->messageState($byUser);

        $deletedState = MessageState::indexOf('deleted');
        return $messageState->state == $deletedState;
    }



}