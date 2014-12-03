<?php
/**
 * A menu item that is attached to a CustomMenuBlock
 * @author Shea Dawson <shea@silverstripe.com.au>
 * @package ba-sis
 **/
class CustomMenuBlockItem extends Link {
	
	private static $singular_name = 'Menu Item';
	private static $plural_name = 'Menu Items';

	private static $db = array(
		'Sort' => 'Int'
	);

	private static $has_one = array(
		'Block' => 'CustomMenuBlock'
	);

	private static $has_many = array(
		'Children' => 'CustomMenuBlockItem'
	);

	private static $extensions = array(
		"Hierarchy",
	);

	private static $default_sort = 'Sort';

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName('BlockID');
		$fields->removeByName('ParentID');
		$fields->removeByName('Children');
		$fields->removeByName('Sort');

		if($this->ID && !$this->ParentID){
			$config = GridFieldConfig_RelationEditor::create()
				->addComponent(new GridFieldOrderableRows());
	        $grid = GridField::create('Children', 'Children', $this->Children(), $config);

	        $config->getComponentByType('GridFieldDataColumns')
	        	->setDisplayFields(array(
					'Title' => 'Menu Item Title',
					'Children.Count' => 'Num Children'
				));

			$fields->addFieldToTab('Root.Main', HeaderField::create('ItemsHeader', 'Children'));
        	$fields->addFieldToTab('Root.Main', $grid);
		}

		return $fields;
	}


	public function getParent(){
		if($this->ParentID){
			return CusomtMenuBlockItem::get()->byID($this->ParentID);
		}
	}


	protected function onBeforeWrite(){
		parent::onBeforeWrite();

		if(!$this->BlockID){
			$this->BlockID = $this->Parent()->BlockID;
		}
	}


	/**
	 * Validate
	 * @return ValidationResult
	 **/
	protected function validate(){
		$valid = true;
		$message = null;
		$result = ValidationResult::create($valid, $message);
		return $result;
	}


	/**
	 * Overridden to use this->class instead of base class
	 */
	public function stageChildren($showAll = false) {
		$baseClass = $this->class;
		$staged = $baseClass::get()
			->filter('ParentID', (int)$this->owner->ID)
			->exclude('ID', (int)$this->owner->ID);

		$this->owner->extend("augmentStageChildren", $staged, $showAll);
		return $staged;
	}
}