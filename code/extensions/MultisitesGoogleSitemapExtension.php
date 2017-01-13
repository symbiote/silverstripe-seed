<?php

class MultisitesGoogleSitemapExtension extends Extension {

	public function alterDataList(&$instances, $class) {

		$cSite = Multisites::inst()->getCurrentSite();
		$instances = $instances->filter('SiteID', $cSite->ID);
	}

}
