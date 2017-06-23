<?php
class SeedTaxonomyTermExtension extends DataExtension {

	private static $api_access = true;

	private static $belongs_many_many = array(
		'Pages' => 'SiteTree'
	);

    public function updateCMSFields(FieldList $fields) {
        $childrenField = $fields->dataFieldByName('Children');
        $fields->removeByName(array('Pages', 'Children'));
        if ($this->owner->CanBeTagged)
        {
            $field = $this->owner->createReverseTagField('Pages');
            if ($field)
            {
                $fields->removeByName($field->getName());
                $fields->addFieldToTab('Root.Main', $field);
            }
        }
        // Move $Children to end of main tab.
        if ($childrenField) {
            $fields->addFieldToTab('Root.Main', $childrenField);
        }
    }

    /**
     * Setup the appropriate field to manage tagging for a belongs_many_many
     * relationship.
     */
    public function createReverseTagField($name) {
        $belongs_many_many = $this->owner->config()->belongs_many_many;
        if (!isset($belongs_many_many[$name]))
        {
            return null;
        }
        $className = $belongs_many_many[$name];
        $s = singleton(($className === 'SiteTree') ? 'Page' : $className);
        $visibleName = $s->plural_name();
        $field = null;
        if ($className::has_extension('Hierarchy'))
        {
            $field = TreeMultiselectField::create($name, 'Tagged '.$visibleName, $className);
        }
        else
        {
            $records = array();
            foreach ($className::get() as $record)
            {
                if ($record->canView())
                {
                    $records[] = $record;
                }
            }
            $field = ListboxField::create($name, 'Tagged '.$visibleName, $className::get()->map()->toArray())->setMultiple(true);
        }
        if ($field)
        {
            $field->setRightTitle($visibleName.' ('.$className.') using this tag');
        }
        return $field;
    }
	
	/** 
     * Set title to show in a default TreeDropdownField.
     *
     * @return string
     */
    public function getTreeTitle() {
        return $this->owner->Name;
    }

    /**
     * Default behaviour of silverstripe-seed to only allow tagging
     * of taxonomies with parents. See 'get_source'.
     *
     * @return boolean
     */
    public function getCanBeTagged() {
        return ($this->owner->ParentID != 0);
    }

	/**
     * Get field to add/remove taxonomies applied to the data object and
     * sets up 'quickaddnew'
     *
     * @return ListboxField
     */
    public static function create_field($name, $title = null) {
        // Get taxonomies to select
        $sourceCallback = function() {
            return call_user_func(array(__CLASS__, 'get_source'));
        };
        $result = ListboxField::create($name, $title, $sourceCallback(), null, null, true);
        $result->useAddNew(
                'TaxonomyTerm', 
                $sourceCallback, 
                FieldList::create(
                    TextField::create('Name', 'Title'),
                    DropdownField::create('ParentID', 'Parent', TaxonomyTerm::get()->filter('ParentID', 0)->map()->toArray())->setEmptyString('')
                )
        );
        return $result;
    }

    /**
     * Get the map of taxonomies for dropdown fields / etc
     * NOTE: In its own function as 'useAddNew' requires the source be a callback function.
     *
     * @return ListboxField
     */
    public static function get_source() {
        // Get taxonomies to select
        $source = array();
        foreach (TaxonomyTerm::get()->exclude('ParentID', 0) as $term) 
        {
            $source[$term->ID] = $term->getTaxonomyName() . ': '.$term->Title;
        }
        asort($source);
        return $source;
    }
}
