<?php

class BasisSpamProtectionForm extends Form
{
	private static $excludes = array();

	public function __construct($controller, $name, FieldList $fields = null, FieldList $actions = null, $validator = null)
	{
		if($fields == null) {
			$fields = FieldList::create();
		}

		if($actions == null) {
			$actions = FieldList::create();
		}

		parent::__construct($controller, $name, $fields, $actions, $validator);

		$captcha = true;
		if ($controller instanceof LeftAndMain ||
			$controller instanceof TaskRunner ||
			$controller instanceof Security ||
			$controller instanceof DevelopmentAdmin ||
			$controller instanceof DevBuildController ||
			$controller instanceof DatabaseAdmin ||
			$controller instanceof GridFieldDetailForm_ItemRequest) {
			$captcha = false;
		}

		if($captcha && $this->hasExtension('FormSpamProtectionExtension') && !$this->isExcluded()) {
			$this->enableSpamProtection();
		}
	}

	public function isExcluded()
	{
		$excludes = Config::inst()->get(__CLASS__, 'excludes');
		if(in_array($this->name, $excludes)) {
			return true;
		}
		return false;
	}
}
