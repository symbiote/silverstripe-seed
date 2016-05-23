<?php

/**
 *	https://github.com/silverstripe/silverstripe-userforms/issues/333
 */

class UserDefinedFormEmailRecipientExtension extends DataExtension {

    public function nonProtectedGetFormParent() {
        $formID = $this->owner->FormID
            ? $this->owner->FormID
            : Session::get('CMSMain.currentPage');
        return UserDefinedForm::get()->byID($formID);
    }

    public function updateCMSFields(FieldList $fields) {

        $form = $this->nonProtectedGetFormParent();

        // add back email fields to send a confirmation to
        $extraEmailFromFields = EditableEmailField::get()->filter('ParentID', $form->ID);
        $source = $fields->dataFieldByName('SendEmailToFieldID')->getSource();
        foreach($extraEmailFromFields->map('ID','Title')->toArray() as $key => $val){
            if( ! $source->offsetExists($key)){ $source->unshift($key,$val); }
        }
        $fields->dataFieldByName('SendEmailToFieldID')->setSource($source);
    }

}
