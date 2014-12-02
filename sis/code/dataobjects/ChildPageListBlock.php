<?php

/**
 * A Block which allows listing of child pages
 */
class ChildPageListBlock extends Block {

	private static $db = array(
		'AbsoluteSource'	=> 'Boolean',
		'PageTypes'			=> 'MultiValueField',
		'ExcludeItems'		=> 'MultiValueField',
		'EnforceShowInMenu'	=> 'Boolean'
	);

	private static $has_one = array(
		'Source' => 'SiteTree'
	);

	public function getCMSFields(){
		$fields = parent::getCMSFields();
		$fields->removeByName('ExcludeItems');
		$fields->removeByName('SourceID');
		$pageClasses = SiteTree::page_type_classes();
		$pageTypes = array();
		foreach ($pageClasses as $class){
			$pageTypes[$class] = singleton($class)->i18n_singular_name();
		}
		$fields->addFieldsToTab(
			'Root.Main', array(
				CheckboxField::create(
					'EnforceShowInMenu',
					_t('ChildPageListBlock.EXCLUDEITEMSNOTINMENUS', "Exclude items which don't show in menus")
				),
				MultiValueListField::create(
					'PageTypes',
					_t('ChildPageListBock.ONLYINCLUDETYPES','Only include these page types'),
					$pageTypes
				)->setRightTitle('Leave blank to include all types'),
				OptionsetField::create(
					'AbsoluteSource',
					_t('ChildPageListBock.SOURCEOPTION', 'Source option'),
					array(
						false => _t('ChildPageListBock.SOURCECURRENTPAGE', 'Current page being viewed'),
						true => _t('ChildPageListBock.SOURCESPECIFICPAGE', 'A specific page')
					)
				),
				TreeDropdownField::create(
					'SourceID',
					_t('ChildPageListBock.SOURCEPAGE','Source page'),
					'SiteTree'
				)->displayIf("AbsoluteSource")->isEqualTo(1)->end(),
			)
		);
		if ($this->AbsoluteSource){
			$kids = $this->Source()->AllChildren();
			if ($kids && $kids->Count()){
				$fields->addFieldToTab(
					'Root.Main',
					MultiValueListField::create(
						'ExcludeItems',
						_t('ChildPageListBock.EXCLUDECHILDREN', 'Exclude these children'),
						$kids->map('ID', 'Title'
					))->displayIf("AbsoluteSource")->isEqualTo(1)->end()
				);
			}
		}

		return $fields;
	}

	public function onBeforeWrite(){
		parent::onBeforeWrite();
		if ($this->ExcludeItems){
			if ($this->SourceID == 0){
				$this->ExcludeItems = null;
			}
			else {
				$exclude = $this->ExcludeItems->getValues();
				$ids = $exclude ? array_values($exclude) : null;
				$excludes = SiteTree::get()
					->filter(array(
						'ID' => $ids,
						'ParentID' => (int)$this->SourceID
					));
				$valids = array_intersect(($ids ? $ids : array()), $excludes->column('ID'));
				$this->ExcludeItems->setValue($valids);
			}
		}
	}

	public function getItems(){
		$viewables = ArrayList::create();
		$source = $this->AbsoluteSource ? $this->Source() : Controller::curr()->data();
		$items = $this->EnforceShowInMenu ? $source->Children() : $source->AllChildren();

		if (!$items || !$items->Count()){
			return $viewables;
		}
		if ($this->PageTypes){
			$types = $this->obj('PageTypes')->getValues();
			if (count($types)){
				$items = $items->filter(array('ClassName' => array_values($types)));
			}
		}
		if ($this->ExcludeItems && $this->AbsoluteSource && $items->Count()){
			$excludes = $this->obj('ExcludeItems')->getValues();
			if (count($excludes)){
				$items = $items->exclude(array('ID' => array_values($excludes)));
			}
		}
		foreach ($items as $item){
			if ($item->canView()){
				$viewables->push($item);
			}
		}

		return $viewables;
	}
}