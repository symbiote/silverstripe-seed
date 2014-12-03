<?php
/**
 * **BasePage** is the foundation which can be used for constructing your own pages.
 * By default it is hidden from the CMS - we rely on developers creating their own
 * `Page` class in the `mysite/code` which will extend from the **BasePage**.
 */

class BasePage extends SiteTree {

	private static $icon = 'sis/images/icons/sitetree_images/page.png';

	// Hide this page type from the CMS. hide_ancestor is slightly misnamed, should really be just "hide"
	private static $hide_ancestor = 'BasePage';

	private static $api_access = array(
		'view' => array('Locale', 'URLSegment', 'Title', 'MenuTitle', 'Content', 'MetaDescription', 'ExtraMenu', 'Sort', 'Version', 'ParentID', 'ID'),
		'edit' => array('Locale', 'URLSegment', 'Title', 'MenuTitle', 'Content', 'MetaDescription', 'ExtraMenu', 'Sort', 'Version', 'ParentID', 'ID')
	);

	private static $many_many = array(
		'Terms' => 'TaxonomyTerm'
	);

	private static $plural_name = 'Base Pages';

	public $pageIcon = 'images/icons/sitetree_images/page.png'; // ?


	public function RelatedPages() {
		// TODO get items from related pages blocks assigned to this page
	}


	public function requireDefaultRecords() {
		if (Director::isDev()) {
			$loader = new FixtureLoader();
			$loader->loadFixtures();
		}
	}


	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$taxonomySourceFunction = function(){
			$source = TaxonomyTerm::get()->exclude('ParentID', 0);
			$result = array();
			if($source->count()){
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

		// Taxonomies
		/*$components = GridFieldConfig_RelationEditor::create();
		$components->removeComponentsByType('GridFieldAddNewButton');
		$components->removeComponentsByType('GridFieldEditButton');
		$autoCompleter = $components->getComponentByType('GridFieldAddExistingAutocompleter');
		$autoCompleter->setResultsFormat('$Name ($TaxonomyName)');*/

		// $dataColumns = $components->getComponentByType('GridFieldDataColumns');
		// $dataColumns->setDisplayFields(array(
		// 	'Name' => 'Term',
		// 	'TaxonomyName' => 'Taxonomy'
		// ));

		// $fields->addFieldToTab(
		// 	'Root.Tags',
		// 	new GridField(
		// 		'Terms',
		// 		'Terms',
		// 		$this->Terms(),
		// 		$components
		// 	)
		// );

		return $fields;
	}

}

class BasePage_Controller extends ContentController {

	private static $allowed_actions = array(

	);


	/**
	 * Site search form
	 */
	// public function SearchForm() {
	// 	$searchText =  _t('SearchForm.SEARCH', 'Search');

	// 	if($this->owner->request && $this->owner->request->getVar('Search')) {
	// 		$searchText = $this->owner->request->getVar('Search');
	// 	}

	// 	$fields = new FieldList(
	// 		new TextField('Search', false, $searchText)
	// 	);
	// 	$actions = new FieldList(
	// 		new FormAction('results', _t('SearchForm.GO', 'Go'))
	// 	);

	// 	$form = new SearchForm($this->owner, 'SearchForm', $fields, $actions);
	// 	$form->setFormAction('search/SearchForm');

	// 	return $form;
	// }

	/**
	 * Process and render search results.
	 *
	 * @param array $data The raw request data submitted by user
	 * @param SearchForm $form The form instance that was submitted
	 * @param SS_HTTPRequest $request Request generated for this action
	 */
	// public function results($data, $form, $request) {
	// 	$start = isset($data['start']) ? $data['start'] : 0;
	// 	$limit = self::$results_per_page;
	// 	$results = new ArrayList();
	// 	$suggestion = null;
	// 	$keywords = $form->getSearchQuery();

	// 	if($keywords) {
	// 		$query = new SearchQuery();
	// 		$query->classes = self::$classes_to_search;
	// 		$query->search($keywords);
	// 		$query->exclude('SiteTree_ShowInSearch', 0);

	// 		// Artificially lower the amount of results to prevent too high resource usage.
	// 		// on subsequent canView check loop.
	// 		$query->limit(100);

	// 		try {
	// 			$result = singleton(self::$search_index_class)->search(
	// 				$query,
	// 				$start,
	// 				$limit,
	// 				array(
	// 					'hl' => 'true',
	// 					'spellcheck' => 'true',
	// 					'spellcheck.collate' => 'true'
	// 				)
	// 			);

	// 			$results = $result->Matches;
	// 			$suggestion = $result->Suggestion;
	// 		} catch(Exception $e) {
	// 			SS_Log::log($e, SS_Log::WARN);
	// 		}
	// 	}

	// 	// Clean up the results.
	// 	foreach($results as $result) {
	// 		if(!$result->canView()) $results->remove($result);
	// 	}

	// 	$rssUrl = $this->Link('SearchForm?Search=' . $keywords . '&format=rss');
	// 	RSSFeed::linkToFeed($rssUrl, 'Search results for "' . $keywords . '"');

	// 	$data = array(
	// 		'PdfLink' => '',
	// 		'Results' => $results,
	// 		'Suggestion' => $suggestion,
	// 		'Query' => $form->getSearchQuery(),
	// 		'Title' => _t('SearchForm.SearchResults', 'Search Results'),
	// 		'RSSLink' => $rssUrl
	// 	);

	// 	$templates = array('Page_results', 'Page');
	// 	if ($request->getVar('format') == 'rss') {
	// 		array_unshift($templates, 'Page_results_rss');
	// 	}

	// 	return $this->owner->customise($data)->renderWith($templates);
	// }

	/**
	 * Provide scripts as needed by the *default* theme.
	 * Override this function if you are using a custom theme based on the *default*.
	 */
	protected function getBaseScripts() {
		$themeDir = SSViewer::get_theme_folder();

		return array(
			THIRDPARTY_DIR .'/jquery/jquery.js',
			// THIRDPARTY_DIR .'/jquery-ui/jquery-ui.js',
			// "$themeDir/js/lib/modernizr.js",
			// "$themeDir/js/bootstrap-transition.2.3.1.js",
			// 'themes/module_bootstrap/js/bootstrap-collapse.js',
			// "$themeDir/js/bootstrap-carousel.2.3.1.js",
			// "$themeDir/js/general.js",
			// "$themeDir/js/express.js",
		);
	}

	/**
	 * Provide stylesheets, as needed by the *default* theme assumed by this recipe.
	 * Override this function if you are using a custom theme based on the *default*.
	 */
	protected function getBaseStyles() {
		$themeDir = SSViewer::get_theme_folder();

		// return array(
		// 	'all' => array(
		// 		"$themeDir/css/layout.css",
		// 		"$themeDir/css/typography.css"
		// 	),
		// 	'screen' => array(
		// 		"$themeDir/css/responsive.css"
		// 	),
		// 	'print' => array(
		// 		"$themeDir/css/print.css"
		// 	)
		// );
	}

	public function init() {
		parent::init();
	
		// Include base scripts that are needed on all pages
		Requirements::combine_files('scripts.js', $this->getBaseScripts());

		// // Include base styles that are needed on all pages
		// $styles = $this->getBaseStyles();

		// // Combine by media type.
		// Requirements::combine_files('styles.css', $styles['all']);
		// Requirements::combine_files('screen.css', $styles['screen'], 'screen');
		// Requirements::combine_files('print.css', $styles['print'], 'print');

		// // Extra folder to keep the relative paths consistent when combining.
		// Requirements::set_combined_files_folder(ASSETS_DIR . '/_combinedfiles/sis-' . SSViewer::current_theme());
	}

	
	// get the search page for the current site

	public function getSearchPage() {
		if(class_exists('Multisites')) {
			$currentSiteID = Multisites::inst()->getCurrentSiteId();
			return ExtensibleSearchPage::get()->filter('SiteID', $currentSiteID)->first();
		}
		else {
			return null;
		}
	}
}
