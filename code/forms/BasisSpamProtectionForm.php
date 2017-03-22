<?php

class BasisSpamProtectionForm extends Form
{
	private static $extensions = array(
		'BasisFormProtectionExtension',
	);

	public function __construct($controller, $name, FieldList $fields = null, FieldList $actions = null, $validator = null)
	{
		if($fields == null) {
			$fields = FieldList::create();
		}

		if($actions == null) {
			$actions = FieldList::create();
		}

		parent::__construct($controller, $name, $fields, $actions, $validator);

		$extension = $this->getExtensionInstance('BasisFormProtectionExtension');
		if ($extension) {
			$extension->updateForm();
		}
	}
}
