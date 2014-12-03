<?php

class PageThumbnailExtension extends DataExtension {

	private static $has_one = array(
		'PageThumbnail' => 'Image'
	);

	public function updateCMSFields(FieldList $fields) {
		if ($this->owner->Site()->hasFeature('PageThumbnails')){
			$fields->addFieldToTab('Root.Main', $thumb = UploadField::create('PageThumbnail', 'Page Thumbnail'));
			$thumb->getValidator()->allowedExtensions = array('jpg', 'gif', 'png');
		}
	}

}
