<?php

class BasisFormProtectionExtension extends Extension
{
	private static $controllers = array();
	private static $includes = array();
	private static $excludes = array();

	public function updateForm()
	{
		if($this->owner->hasExtension('FormSpamProtectionExtension') &&
			($this->usesIncludesController() || $this->isIncluded()) &&
			!$this->owner->isExcluded()) {
			
			$this->owner->enableSpamProtection();
		}
	}

	public function usesIncludesController()
	{
		$controllers = Config::inst()->get(__CLASS__, 'controllers');
		foreach(ClassInfo::ancestry($this->owner->controller) as $controller) {
			if(isset($controllers[$controller])) {
				return true;
			}
		}
		return false;
	}

	public function isIncluded()
	{
		$includes = Config::inst()->get(__CLASS__, 'includes');
		if(in_array($this->owner->name, $includes)) {
			return true;
		}
		return false;
	}

	public function isExcluded()
	{
		$excludes = Config::inst()->get(__CLASS__, 'excludes');
		if(in_array($this->owner->name, $excludes)) {
			return true;
		}
		return false;
	}
}
