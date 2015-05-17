Hermes
======

A fully featured messaging package for Laravel 4. (Working to make it fit for 5)

You can have conversations between multiple users to build a messenger with rooms or just a private 1to1 message system.

## The Idea

* Conversation  -> hasMany Users
* Conversation  -> hasMany Messages
* Message       -> hasOne User
* Message       -> hasMany MessageState
* MessageState  -> hasMany User

Like you see in the table above, Hermes is able to differ between different users when it comes to reading a message.

**For example:**

There is a conversation between 3 users: *User1, User2 and User3*.

*User1* writes a message and *User2* reads it.


Then we have three MessageStates for this new message:
* MessageState for User1 is ‘own’
* MessageState for User2 is ‘read’
* MessageState for User3 is ‘unread’


##Install
### Step 1: Composer.json

#### For Laravel 5
```Javascript
    "require": {
        ...
        "triggerdesign/hermes": "2.*"
        }
```

#### For Laravel 4
```Javascript
    "require": {
        ...
        "triggerdesign/hermes": "1.0"
        }
```

Run a **composer update**.

### Step 2: app.php
```PHP
	'providers' => array(
	    ...
        'Triggerdesign\Hermes\HermesServiceProvider'
	);
	...
	'aliases' => array(
	    ...
        'Messaging'         => 'Triggerdesign\Hermes\Facades\Messaging'
   );
```

### Step 3: Migrate tables
```
$ php artisan migrate --package="triggerdesign/hermes"
```
Now you have the 4 tables that we need for user conversations.

### Step 4: Use the user trait 
You should use a trait inside your **User model**:
```PHP
<?php

use Triggerdesign\Hermes\Models\UserTrait as HermesTrait;
...

class User extends BaseModel implements ConfideUserInterface
{
    use HermesTrait;
    ...
```

## Usage

### Start a new conversation or find an existing one

Start a converstion between user with the ID 1 and the user with the ID 2. If there is allready one it will return the existing conversation.
```PHP
    //This will start a new conversation between user 1 and 2 or find an existing one
    $conversation = Messaging::startConversation([1,2]);
    
    //or try to find one on your own
    $conversation =  Messagging::findConversations($user_ids, $arguments, $limit);
```

Now you have access to these functions and attributes:
```PHP
    //All messages in one conversation
    $conversation->messages;
    
    //Add a message
    $conversation->addMessage($content);
    
    $conversation->addUser($user);
    
    $conversation->latestMessage();
    $conversation->unreadMessages();
    
    $conversation->isUnread(); //conversation has unread messages
    $conversation->doRead(); //call this after a user has read his messages
```

You can also have groups of messages like in facebook. Several messages are collected into a group of messages, if they is not too much time in between and if they are all from one sender.

```PHP
    //Build an array of \Triggerdesign\Hermes\Classes\MessageGroup
    $messageGroups = $conversation->buildGroups();
    
    ...
    
    //now you can iterate throgh these groups and buld your own messenger
    @foreach($messageGroups as $messageGroup)
        <b>{{ $messageGroup->getUser()->name }}: @ {{ $messageGroup->getStart()->format('d.m.Y H:i:s');  }}</b>
        @foreach($messageGroup->getMessages() as $message)
            <p>{{ nl2br($message->content)  }}</p>
        @endforeach
    @endforeach
    
```

### Access the conversations of a user

The trait allows you to use these functions:

```PHP
	//All conversations that this user is a member of
	$user->conversations(); 
	
	//How many messages are unread
	$user->unreadMessagesCount();
	$user->hasUnreadMessages();
	
	//Get all unread conversations
	$user->unreadConversations();
	
	//Get all unread conversations inside all the unread conversations
	$user->unreadMessages();
```


## Configuration
Publish the configuration files into your app directory:
```
$php artisan config:publish triggerdesign/hermes
```

* If you dont use the "users" table for storing your users you can rename it in **hermes.usersTable**
* Change the tableprefix if you want to: **hermes.tablePrefix**

