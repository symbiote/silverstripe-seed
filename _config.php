<?php

// Add a password complexity validator.

$validator = new PasswordValidator();

// The minimum length may be lowered when required.

$validator->minLength(8);
$validator->characterStrength(2 ,array(
	'lowercase',
	'uppercase',
	'digits'
));
Member::set_password_validator($validator);

// Remove the duplicate broken link reports.

SS_Report::add_excluded_reports(array(
	'Multisites_SideReport_BrokenLinks',
	'Multisites_SideReport_BrokenFiles',
	'Multisites_SideReport_BrokenVirtualPages',
	'Multisites_SideReport_BrokenRedirectorPages'
));
