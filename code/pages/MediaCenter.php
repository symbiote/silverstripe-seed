<?php
class MediaCenter extends DatedUpdateHolder {

	private static $singular_name = "Media Center";
	private static $plural_name = "Media Centers";

	private static $update_class = 'DatedUpdatePage';
	
	static $db = array(
		
	);

	static $many_many = array(
		'Sources' => 'MediaHolder'
	);

	static $many_many_extraFields = array(
		'Sources' => array(
			'Order' => 'Int'
		)
	);


	public function MediaSources(){
		return $this->Sources()->sort('Order');
	}


	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$config = GridFieldConfig_RelationEditor::create()
			->removeComponentsByType('GridFieldAddNewButton')
			->removeComponentsByType('GridFieldAddExistingAutocompleter')
			->removeComponentsByType('GridFieldEditButton')
			->addComponent(new GridFieldAddExistingSearchButton())
			->addComponent(new GridFieldOrderableRows('Order'));

		$fields->addFieldToTab(
			'Root.MediaSources', 
			GridField::create('Sources', 'Media Sources', $this->MediaSources(), $config)
		);

		return $fields;
	}

	/**
	 * 
	 */
	public function Updates($tagID = null, $dateFrom = null, $dateTo = null, $year = null, $monthNumber = null) {
		$className = Config::inst()->get($this->ClassName, 'update_class');

		$sources = $this->MediaSources()->column('ID');

		if(!empty($sources)){
			return static::AllUpdates($className, $sources, $tagID, $dateFrom, $dateTo, $year, $monthNumber);
		}else{
			return false;
		}
	}


	/**
	 * 
	 */
	public static function AllUpdates($className = 'MediaPage', $parentID = null, $tagID = null, $dateFrom = null,
		$dateTo = null, $year = null, $monthNumber = null) {

		return parent::AllUpdates($className, $parentID, $tagID, $dateFrom, $dateTo, $year, $monthNumber)->Sort('Date', 'DESC');
	}

}

class MediaCenter_Controller extends DatedUpdateHolder_Controller {
	
	public function init(){
		parent::init();
	}
}