<?php
class AGLSMetadataFieldExtension extends DataExtension
{
    
    private static $db = array(
        'Scheme' => 'Varchar',
        'Lang' => 'Varchar'
    );

    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->Schema()->Name != 'AGLS') {
            $fields->removeByName('Scheme');
            $fields->removeByName('Lang');
        }
    }
    
    public function getExtraTagAttributes()
    {
        if ($this->owner->Schema()->Name == 'AGLS') {
            $attr = array();
            if ($this->owner->Scheme) {
                $attr['scheme'] = Convert::raw2att($this->owner->Scheme);
            }
            if ($this->owner->Lang) {
                $attr['lang'] = Convert::raw2att($this->owner->Lang);
            }
            return $attr;
        }
    }
}
