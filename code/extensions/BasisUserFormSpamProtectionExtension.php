<?php

class BasisUserFormSpamProtectionExtension extends Extension
{
	public function updateForm()
	{
		$data = $this->owner->controller->data();
		if(!$data->DisableHoneypotProtection && $this->owner->hasExtension('FormSpamProtectionExtension')) {
			$this->owner->enableSpamProtection();
		}
	}
}
