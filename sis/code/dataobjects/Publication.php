<?php
class Publication extends DataObject implements RegistryDataInterface{

	private static $singular_name = "Publication";
	private static $plural_name = "Publications";
	
	private static $db = array(
		'Title' => 'Varchar',
		'Description' => 'Text',
	);

	private static $has_one = array(
		'File' => 'File'
	);

	 public function getSearchFields() {
	 	return new FieldList(
	 		new TextField('Title', 'Title'),
	 		new TextField('Description', 'Description')
	 	);
	  }
}
