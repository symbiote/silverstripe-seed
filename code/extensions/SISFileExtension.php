<?php
class SISFileExtension extends DataExtension {
	
	private static $db = array(
		'Description' => 'Text'
	);


	public function updateCMSFields(FieldList $fields){
		if($this->owner->ClassName != 'Folder'){
			$fields->addFieldToTab('Root.Main', TextAreaField::create('Description'));	
		}
	}

}