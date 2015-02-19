<?php

	// Add a password complexity validator.

	$validator = new PasswordValidator();
	$validator->minLength(10);
	$validator->characterStrength(2 ,array(
		'lowercase',
		'uppercase',
		'digits'
	));
	Member::set_password_validator($validator);
