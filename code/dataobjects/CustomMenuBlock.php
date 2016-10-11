<?php

if (!class_exists('Block')) {
	return;
}

/**
 * A block that allows end users to manually build a custom (flat or nested) menu  
 * @author Shea Dawson <shea@silverstripe.com.au>
 * @package ba-sis
 **/
class CustomMenuBlock extends Block {
	
    private static $singular_name = 'Custom Menu Block';
    private static $plural_name = 'Custom Menu Blocks';

	private static $db = array(
		
	);

	private static $has_many = array(
		'Items' => 'CustomMenuBlockItem'
	);

	public function getCMSFields(){
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab('Root', 'Items');
        
		if ($this->ID) {
			$config = GridFieldConfig_RecordEditor::create()
				->addComponent(new GridFieldOrderableRows());
            
			$grid = GridField::create('Items', 'Items', $this->Items()->filter('ParentID', 0), $config);	

			$config->getComponentByType('GridFieldDataColumns')
				->setDisplayFields(array(
					'Title' => 'Menu Item Title',
					'Children.Count' => 'Num Children'
				));

			$fields->addFieldToTab('Root.Main', HeaderField::create('ItemsHeader', 'Menu Items'));
			$fields->addFieldToTab('Root.Main', $grid);
		}

        return $fields;
    }


    /**
     * Method for use in templates to loop over menu items
     * @return DataList
     **/
    public function MenuItems(){
    	return $this->Items()->filter('ParentID', 0);
    }
}
