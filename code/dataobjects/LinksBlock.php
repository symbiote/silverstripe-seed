<?php

class LinksBlock extends Block {

	private static $singular_name = 'Links Block';
	private static $plural_name = 'Links Blocks';

	private static $many_many = array(
		'Links' => 'Link'
	);

	private static $many_many_extraFields = array(
		'Links' => array(
			'Sort' => 'Int'
		)
	);


	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeFieldFromTab('Root', 'Links');
		$fields->addFieldToTab('Root.Main', HeaderField::create('LinksHeader', 'Quick Links'));
		if ($this->ID) {
			$fields->addFieldToTab(
				'Root.Main', 
				GridField::create('Links', 'Links', $this->getItems(), 
				GridFieldConfig_RelationEditor::create()
					->addComponent(new GridFieldOrderableRows())
					->removeComponentsByType('GridFieldAddExistingAutocompleter')
					->addComponent(new GridFieldAddExistingSearchButton())
			));
		}

		return $fields;
	}


	public function getItems(){
		return $this->Links()->sort('Sort');
	}
}
