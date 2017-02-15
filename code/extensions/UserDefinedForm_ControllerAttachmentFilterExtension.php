<?php

/**
 * Fixes security risk of emailing attachments to publicly submitted email addresses
 *
 * @author stephen
 */
class UserDefinedForm_ControllerAttachmentFilterExtension extends Extension {

	public function updateEmail($email, $recipient, $emailData) {
		if ($recipient->SendEmailToFieldID) {
			$emailNoAttachment = $emailData;
			$email->setField('attachments', array());
			$emailNoAttachment['Fields'] = $emailNoAttachment['Fields']->exclude('ClassName', 'SubmittedFileAttachmentField');
			$email->populateTemplate($emailNoAttachment);
		}
	}

}
