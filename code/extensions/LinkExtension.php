<?php

class LinkExtension extends DataExtension
{

    private static $db = array(
        'Description' => 'Varchar(256)',
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->insertAfter(TextareaField::create('Description', 'Description'), 'Title');
    }
}
