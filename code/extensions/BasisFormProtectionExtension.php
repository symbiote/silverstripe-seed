<?php

class BasisFormProtectionExtension extends Extension
{
	private static $excludes = array();

	public function updateForm()
	{
		if(!$this->owner->isExcluded() && $this->owner->hasExtension('FormSpamProtectionExtension')) {
			$this->owner->enableSpamProtection();
		}
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
