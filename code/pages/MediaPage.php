<?php

class MediaPage extends DatedUpdatePage {

	private static $description = 'Media Release, News, Publication, Speech <strong>or Custom Media</strong>';

	private static $default_parent = 'MediaHolder';

	private static $can_be_root = false;

	private static $defaults = array(
		'ShowInMenus' => 0
	);

	private static $allowed_children = 'none';

	private static $icon = 'sis/images/icons/sitetree_images/media.png';

	private static $db = array(
		'MediaType' => 'VARCHAR(255)',
		'ExternalLink' => 'VARCHAR(255)',
		'Author' => 'VARCHAR(255)',
		'Location' => 'VARCHAR(255)',
		'Contact' => 'VARCHAR(255)',
		'ContactNumber' => 'VARCHAR(255)',
		'Speaker' => 'VARCHAR(255)'
	);

	private static $has_one = array(
		'AudioFile' => 'File'
	);

	private static $many_many = array(
		'Images' => 'Image',
		'Attachments' => 'File'
	);

	private static $media_types = array(
		'News',
		'MediaRelease',
		'Publication',
		'Speech'
	);

	public $pageIcon =  'images/icons/sitetree_images/media.png';

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$mediaFolder = sprintf("media-%s", strtolower($this->MediaType));

		// make sure the media page type matches the parent media holder

		$fields->addFieldToTab('Root.Main', ReadonlyField::create(
			'Type',
			'Type',
			$this->MediaType
		), 'Title');

		// display a notification if the media holder has mixed children

		$parent = $this->getParent();
		if($parent && $parent->AllChildren()->where("ClassName = 'MediaHolder'")->exists()) {
			Requirements::css('sis/css/media.css');
			$fields->addFieldToTab('Root.Main', LiteralField::create(
				'MediaNotification',
				"<p class='media notification'><strong>Mixed {$this->MediaType} Holder</strong></p>"
			), 'Type');
		}

		$fields->addFieldToTab('Root.Main', TextField::create(
			'ExternalLink'
		)->setRightTitle('An <strong>optional</strong> redirect URL to the media source'), 'URLSegment');


		// add the news and publication attributes

		if(in_array($this->MediaType, array('News', 'Publication'))) {
			$fields->addFieldToTab('Root.Main', TextField::create('Author'), 'Abstract');
		}

		// add the media release attributes

		if($this->MediaType === 'MediaRelease') {
			$fields->addFieldToTab('Root.Main', TextField::create('Contact'), 'Abstract');
			$fields->addFieldToTab('Root.Main', TextField::create('ContactNumber'), 'Abstract');
		}

		// add the speech attributes

		if(in_array($this->MediaType, array('Speech'))) {
			$fields->addFieldToTab('Root.Main', TextField::create('Location'), 'Abstract');
			$fields->addFieldToTab('Root.Main', TextField::create('Speaker'), 'Abstract');
			// $fields->addFieldToTab('Root.Main', UploadField::create('AudioFile', 'Audio File')
			// 	->setFolderName($mediaFolder)
			// 	->useMultisitesFolder()
			// ,'Content');
		}

		// add images to appropriate media types

//		if(in_array($this->MediaType, array('News', 'MediaRelease', 'Speech'))) {
			$fields->addFieldToTab('Root.Images', $images = UploadField::create('Images')
				->setFolderName($mediaFolder)
				->useMultisitesFolder()
			);
			$images->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif', 'bmp'));
		
//		}
		$fields->addFieldToTab('Root.Attachments', $attachments = UploadField::create('Attachments')
			->setFolderName($mediaFolder)
			->useMultisitesFolder()
		);

		// allow customisation of the cms fields displayed

		$this->extend('updateCMSFields', $fields);

		return $fields;
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();

		// clean up an external url, making sure it exists/is available

		if($this->ExternalLink) {
			if(stripos($this->ExternalLink, 'http') === false) {
				$this->ExternalLink = 'http://' . $this->ExternalLink;
			}
			$file_headers = @get_headers($this->ExternalLink);
			if(!$file_headers || strripos($file_headers[0], '404 Not Found')) {
				$this->ExternalLink = null;
			}
		}

		// link this page to the parent media holder
		if(!$this->MediaType){
			$this->MediaType = $this->getParent()->MediaType;
		}
		
	}
	
	/**
	 *	Render a summary of this page in a template, for MediaHolder page listings
	 *	@return String
	 **/

	public function ListingSummary(){
		$templates[] = 'MediaPage_' . $this->MediaType . '_ListingSummary';
		$templates[] = 'MediaPage_ListingSummary';
		return $this->renderWith($templates);
	}
}

class MediaPage_Controller extends DatedUpdatePage_Controller {

	public function index() {

		parent::index();

		// if a custom template for the specific page type has been defined, use this
		if($this->MediaType) $templates[] = $this->data()->ClassName . '_' . $this->MediaType;
		$templates[] = $this->data()->ClassName;
		$templates[] = 'Page';

		return $this->renderWith($templates);
	}

}
