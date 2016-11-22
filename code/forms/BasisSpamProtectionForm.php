<?php

class BasisSpamProtectionForm extends Form
{
	private static $controllers = array();
	private static $includes = array();
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

		if($this->hasExtension('FormSpamProtectionExtension') &&
			($this->usesIncludesController() || $this->isIncluded()) &&
			!$this->isExcluded()) {
			
			$this->enableSpamProtection();
		}
	}

	public function usesIncludesController()
	{
		$controllers = Config::inst()->get(__CLASS__, 'controllers');
		foreach(ClassInfo::ancestry($this->controller) as $controller) {
			if(isset($controllers[$controller])) {
				return true;
			}
		}
		return false;
	}

	public function isIncluded()
	{
		$includes = Config::inst()->get(__CLASS__, 'includes');
		if(in_array($this->name, $includes)) {
			return true;
		}
		return false;
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
