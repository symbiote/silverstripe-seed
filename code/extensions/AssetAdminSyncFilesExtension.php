<?php

/**
 *	Remove the blasphemy that is known as sync files!
 */

class AssetAdminSyncFilesExtension extends Extension
{

    public function updateEditForm($form)
    {
        $form->Fields()->removeByName('SyncButton');
    }
}
