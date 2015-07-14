<?php

return array(
    'usersTable' => 'users',
    'userClass' => '\App\User',
    'tablePrefix' => 'hermes_',
		
	//Include foreign keys for you database if you want.
	//But it produces errors in some envorinments, therefore its disables by default.
	'useForeignKeys' => false


);