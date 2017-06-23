<?php

class SeedUserDefinedFormSpamProtectionExtension extends DataExtension
{
	private static $db = array(
		'DisableHoneypotProtection' => 'Boolean'
	);

	public function updateFormOptions(FieldList $fields)
	{
		$fields->push(CheckboxField::create('DisableHoneypotProtection', 'Disable Honeypot Spam Protection'));
	}
}
