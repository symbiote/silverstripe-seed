<?php

class BasisFormProtectionExtension extends Extension
{
	private static $excludes = array();

	public function updateForm()
	{
		if ($this->owner->controller instanceof LeftAndMain ||
			$this->owner->controller instanceof TaskRunner ||
			$this->owner->controller instanceof Security ||
			$this->owner->controller instanceof DevelopmentAdmin ||
			$this->owner->controller instanceof DevBuildController ||
			$this->owner->controller instanceof DatabaseAdmin ||
			$this->owner->controller instanceof GridFieldDetailForm_ItemRequest) {
			return;
		}
		
		if($this->owner->hasExtension('FormSpamProtectionExtension') && !$this->owner->isExcluded()) {
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
