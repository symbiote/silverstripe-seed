<?php

class SeedFormProtectionExtension extends Extension
{
	/**
	 *  Example of applying to pages with Page_Controller and forms
	 *  with an 'updateForm' hook.
	 *
	 *	SeedFormProtectionExtension:
	 *		controllers:
	 *			- Page_Controller
	 *
	 * @config
	 * @var array
	 */
	private static $controllers = array();

	/**
	 * @config
	 * @var array
	 */
	private static $includes = array();

	/**
	 *  Example of excluding forms that definitely do not need
	 *  anti-spam protection.
	 *
	 *	SeedFormProtectionExtension:
	 *		# A listing form that uses GET methods.
	 *		excludes:
	 *			- 'ListFilterForm'
	 *
	 * @config
	 * @var array
	 */
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
