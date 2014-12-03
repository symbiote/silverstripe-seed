<?php

class EventPage extends DatedUpdatePage {

	private static $description = 'Describes an event occurring on a specific date.';

	static $default_parent = 'EventHolder';

	static $can_be_root = false;

	static $icon = 'sis/images/icons/sitetree_images/event_page.png';

	public $pageIcon =  'images/icons/sitetree_images/event_page.png';

	private static $db = array(
		'StartTime' => 'Time',
		'EndDate' => 'Date',
		'EndTime' => 'Time',
		'Location' => 'Text'
	);

	private static $casting = array(
		"Date" => "Date"
	);

	/**
	 * Add the default for the Date being the current day.
	 */
	public function populateDefaults() {
		if(!isset($this->Date) || $this->Date === null) {
			$this->Date = SS_Datetime::now()->Format('Y-m-d');
		}

		if(!isset($this->StartTime) || $this->StartTime === null) {
			$this->StartTime = '09:00:00';
		}

		if(!isset($this->EndTime) || $this->EndTime === null) {
			$this->EndTime = '17:00:00';
		}

		parent::populateDefaults();
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->removeByName("Date");

		$timeFields = array(
			DateField::create('Date',_t('SIS.DATE','Start date'))
				->setConfig('showcalendar', true)
				->setConfig('dateformat', Member::currentUser()->getDateFormat()),

			DateField::create('EndDate',_t('SIS.ENDDATE','End date'))
				->setConfig('showcalendar', true)
				->setConfig('dateformat', Member::currentUser()->getDateFormat()),

			TimePickerField::create('StartTime', _t('SIS.STARTTIME','Start time')),
			TimePickerField::create('EndTime', _t('SIS.ENDTIME','End time'))
		);

		$fields->addFieldsToTab('Root.Main', $timeFields, 'Content');

		// $dateTimeFields[] = $dateField = new DateField('Date', '');
		// $dateField->setConfig('showcalendar', true);
		// $dateField->setConfig('dateformat', Member::currentUser()->getDateFormat());

		// $dateTimeFields[] = $startTimeField = new TimeField('StartTime', '&nbsp;&nbsp;Start Time');
		// $dateTimeFields[] = $endTimeField = new TimeField('EndTime');
		// // Would like to do this, but the width of the form field doesn't scale based on the time
		// // format. OS ticket raised: http://open.silverstripe.org/ticket/8260
		// //$startTimeField->setConfig('timeformat', Member::currentUser()->getTimeFormat());
		// //$endTimeField->setConfig('timeformat', Member::currentUser()->getTimeFormat());
		// $startTimeField->setConfig('timeformat', 'h:ma');
		// $endTimeField->setConfig('timeformat', 'h:ma');

		// $fields->addfieldToTab('Root.Main', $dateTimeField = new FieldGroup('Date and time', $dateTimeFields), 'Abstract');

		// $fields->addfieldToTab('Root.Main', $locationField = new TextareaField('Location'), 'Abstract');
		//$locationField->setRows(4);

		return $fields;
	}


	public function onBeforeWrite(){
		parent::onBeforeWrite();
		if(!$this->EndDate){
			$this->EndDate = $this->Date;
		}
	}


	public function getCMSValidator(){
		return EventPageValidator::create(
			'Date'
		);
	}


	public function NiceLocation() {
		return (nl2br(Convert::raw2xml($this->Location), true));
	}
}

class EventPage_Controller extends DatedUpdatePage_Controller {

}



class EventPageValidator extends RequiredFields{

	/**
	 * validate that the events end date is equal to or greater than the startdate
	 * @todo for some reason this is just not working.
	 * @return boolean
	 **/
	public function php($data){
		$valid = parent::php($data);

		if($valid === false){
			return $valid;
		}

		$fields 	= $this->form->Fields();
		$startDate 	= isset($data['Date']) ? $data['Date'] : null;
		$endDate 	= isset($data['EndDate']) ? $data['EndDate'] : null;

		if($endDate && $endDate < $startDate){
			$this->validationError('EndDate', 'End Date must be equal to or greater than Start Date', 'required');
			$valid = false;
		}
		
		return $valid;
	}
}
