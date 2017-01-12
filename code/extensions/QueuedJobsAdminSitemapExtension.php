<?php

/**
 *	This extension removes the "generate google sitemap" job, since this conflicts with the google sitemap module.
 *	@author Nathan Glasl <nathan@silverstripe.com.au>
 */

class QueuedJobsAdminSitemapExtension extends Extension {

	public function updateEditForm($form) {

		$jobType = $form->Fields()->dataFieldByName('JobType');
		if($jobType) {
			$source = $jobType->getSource();
			unset($source['GenerateGoogleSitemapJob']);
			$jobType->setSource($source);
		}
	}

}