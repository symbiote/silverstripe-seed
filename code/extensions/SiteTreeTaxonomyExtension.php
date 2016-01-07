<?php

class SiteTreeTaxonomyExtension extends DataExtension
{

    private static $many_many = array(
        'Terms' => 'TaxonomyTerm'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $taxonomySourceFunction = function () {

            $source = TaxonomyTerm::get()->exclude('ParentID', 0);
            $result = array();
            if ($source->count()) {
                foreach ($source as $term) {
                    $result[$term->ID] = $term->getTaxonomyName() . ": $term->Title";
                }
            }
            asort($result);
            return $result;
        };

        $taxonomySource = $taxonomySourceFunction();

        $fields->addFieldToTab(
            'Root.Main',
            ListBoxField::create('Terms', 'Terms', $taxonomySource, null, null, true)
                ->useAddNew(
                    'TaxonomyTerm',
                    $taxonomySourceFunction,
                    FieldList::create(
                        TextField::create('Name', 'Title'),
                        DropdownField::create('ParentID', 'Parent', TaxonomyTerm::get()->filter('ParentID', 0)->map()->toArray())
                            ->setEmptyString('')
                    )
                ),
            'Content'
        );
    }
}
