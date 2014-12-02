<?php

class MediaHolder extends DatedUpdateHolder {
	
	private static $description = '<strong>Holds:</strong> Media Releases, News, Publications, Speeches <strong>or Custom Media</strong>';

	private static $db = array(
		'MediaType' => 'VARCHAR(255)'
	);

	private static $allowed_children = array(
		'MediaHolder',
		'MediaPage'
	);

	private static $default_child = 'MediaPage';

	private static $update_name = 'Media';

	private static $update_class = 'MediaPage';

	private static $icon = 'sis/images/icons/sitetree_images/media_listing.png';

	public $pageIcon =  'images/icons/sitetree_images/media_listing.png';
	

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Main', DropdownField::create(
			'MediaType',
			'Media Type',
			ArrayLib::valuekey(array_merge(array('' => ''), Config::inst()->get('MediaPage', 'media_types')))
		), 'Title');

		$mediaIsSet = MediaPage::get()->filter(array(
			'ParentID' => $this->ID,
			'MediaType:not' => '',
		))->count();

		if($mediaIsSet){
			$fields->makeFieldReadOnly('MediaType');
		}

		// allow customisation of the cms fields displayed

		$this->extend('updateMediaHolderFields', $fields);

		return $fields;
	}

	// check if there is another media holder within this media holder

	public function checkMediaHolder() {
		return $this->AllChildren()->where("ClassName = 'MediaHolder'");
	}

	/**
	 *	Find all site's Media items, based on some filters.
	 *	Omitting parameters will prevent relevant filters from being applied. The filters are ANDed together.
	 *
	 *	@param $className The name of the class to fetch.
	 *	@param $parentID The ID of the holder to extract the Media items from.
	 *	@param $tagID The ID of the tag to filter the Media items by.
	 *	@param $dateFrom The beginning of a date filter range.
	 *	@param $dateTo The end of the date filter range. If empty, only one day will be searched for.
	 *	@param $year Numeric value of the year to show.
	 *	@param $monthNumber Numeric value of the month to show.
	 *
	 *	@returns DataList | PaginatedList
	 */
	public static function AllUpdates($className = 'MediaPage', $parentID = null, $tagID = null, $dateFrom = null,
		$dateTo = null, $year = null, $monthNumber = null) {

		return parent::AllUpdates($className, $parentID, $tagID, $dateFrom, $dateTo, $year, $monthNumber)->Sort('Date', 'DESC');
	}

}

class MediaHolder_Controller extends DatedUpdateHolder_Controller {

	private $allowed_actions = array(
		'rss'
	);

	public function index() {

		parent::index();

		// if a custom template for the specific page type has been defined, use this
		if($this->MediaType) $templates[] = $this->data()->ClassName . '_' . $this->MediaType;
		$templates[] = $this->data()->ClassName;
		$templates[] = 'Page';

		return $this->renderWith($templates);
	}

	// can probably be removed as this does not take filtering into account, 
	// use DatedUpdateHolder_Controller->FilteredUpdates($limit) instead
	public function getPaginatedChildren($limit = 5, $reverse = false) {
		$children = $this->data()->AllChildren();
		if($reverse) {
			$children = $children->reverse();
		}
		return PaginatedList::create(
			$children,
			$this->getRequest()
		)->setPageLength($limit);
	}

	public function rss() {
		$rss = new RSSFeed($this->Updates()->sort('Created DESC')->limit(20), $this->Link(), $this->getSubscriptionTitle());
		$rss->setTemplate('MediaHolder_rss');
		return $rss->outputToBrowser();
	}

}
