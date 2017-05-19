<?php

/**
 *	Remove the blasphemy that is known as sync files!
 */

class AssetAdminSyncFilesExtension extends Extension {

	private static $enable_sync_files_button = false;

	public function updateEditForm($form) {
		$enable = Config::inst()->get('AssetAdmin', 'enable_sync_files_button');
		if (!$enable){
			$form->Fields()->removeByName('SyncButton');
		}
	}

}
