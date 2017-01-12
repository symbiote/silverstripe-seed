<?php
/**
 * Adds new global settings.
 */

class SISSite extends DataExtension {

	private static $db = array(
		'GACode' => 'Varchar(16)', // This remains for backward compatibility.
		'FacebookURL' => 'Varchar(256)', // multitude of ways to link to Facebook accounts, best to leave it open.
		'TwitterUsername' => 'Varchar(16)', // max length of Twitter username 15
		'AddThisProfileID' => 'Varchar(32)'
	);

	private static $has_one = array(
		'FavIcon' => 'File',
		'AppleTouchIcon144' => 'File',
		'AppleTouchIcon114' => 'File',
		'AppleTouchIcon72' => 'File',
		'AppleTouchIcon57' => 'File'
	);

	public function updateSiteCMSFields(FieldList $fields) {

		$fields->addFieldToTab('Root.SocialMedia', $facebookURL = new TextField('FacebookURL', 'Facebook UID or username'));
		$facebookURL->setRightTitle('Facebook link (everything after the "http://facebook.com/", eg http://facebook.com/<strong>username</strong> or http://facebook.com/<strong>pages/108510539573</strong>)');

		$fields->addFieldToTab('Root.SocialMedia', $twitterUsername = new TextField('TwitterUsername', 'Twitter username'));
		$twitterUsername->setRightTitle('Twitter username (eg, http://twitter.com/<strong>username</strong>)');

		$fields->addFieldToTab('Root.SocialMedia', $addThisID = new TextField('AddThisProfileID', 'AddThis Profile ID'));
		$addThisID->setRightTitle('Profile ID to be used all across the site (in the format <strong>ra-XXXXXXXXXXXXXXXX</strong>)');

		$fields->addFieldToTab('Root.Logos/Icons', $favIconField = new UploadField('FavIcon', 'Favicon, in .ico format, dimensions of 16x16, 32x32, or 48x48'));
		$favIconField->getValidator()->setAllowedExtensions(array('ico'));
		$favIconField->setConfig('allowedMaxFileNumber', 1);

		$fields->addFieldToTab('Root.Logos/Icons', $atIcon144 = new UploadField('AppleTouchIcon144', 'Apple Touch Web Clip and Windows 8 Tile Icon (dimensions of 144x144, PNG format)'));
		$atIcon144->getValidator()->setAllowedExtensions(array('png'));
		$atIcon144->setConfig('allowedMaxFileNumber', 1);

		$fields->addFieldToTab('Root.Logos/Icons', $atIcon114 = new UploadField('AppleTouchIcon114', 'Apple Touch Web Clip Icon (dimensions of 114x114, PNG format)'));
		$atIcon114->getValidator()->setAllowedExtensions(array('png'));
		$atIcon114->setConfig('allowedMaxFileNumber', 1);

		$fields->addFieldToTab('Root.Logos/Icons', $atIcon72 = new UploadField('AppleTouchIcon72', 'Apple Touch Web Clip Icon (dimensions of 72x72, PNG format)'));
		$atIcon72->getValidator()->setAllowedExtensions(array('png'));
		$atIcon72->setConfig('allowedMaxFileNumber', 1);

		$fields->addFieldToTab('Root.Logos/Icons', $atIcon57 = new UploadField('AppleTouchIcon57', 'Apple Touch Web Clip Icon (dimensions of 57x57, PNG format)'));
		$atIcon57->getValidator()->setAllowedExtensions(array('png'));
		$atIcon57->setConfig('allowedMaxFileNumber', 1);
	}

}
