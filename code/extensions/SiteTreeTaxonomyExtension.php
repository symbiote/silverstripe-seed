<?php

class SiteTreeTaxonomyExtension extends DataExtension {

	private static $many_many = array(
		'Terms' => 'TaxonomyTerm'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab('Root.Main', BasisTaxonomyTermExtension::create_field('Terms', 'Terms'), 'Content');
	}
}
