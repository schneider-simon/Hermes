Hermes
======

A fully featured messaging package for Laravel.

##Install
### Composer.json
```Javascript
    "require": {
        ...
        "triggerdesign/hermes": "dev-master"
        }
```

Run a **composer update**.

### app.php
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

### Migrate tables
```
$ php artisan migrate --package="triggerdesign/hermes"
```
Now you have the 4 tables that we need for user conversations.


## Usage

### Start a new conversation or find an existing one

Start a converstion between user with the ID 1 and the user with the ID 2. If there is allready one it will return the existing conversation.
```PHP
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

You can also have groups of messages like in facebook. Several messages are collected into a group of messages, if they are is not much time in between and if they are all from one sender.

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

## Configuration
Publish the configuration files into your app directory:
```
$php artisan config:publish triggerdesign/hermes
```

* If you dont use the "users" table for storing your users you can rename it in **hermes.usersTable**
* Change the tableprefix if you want to: **hermes.tablePrefix**

