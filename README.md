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
