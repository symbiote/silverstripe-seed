;(function($){
	$(document).ready(function(){
		
		// This needs to be customised - it's the HTML element that will be looked for
		// and content loaded into the formblock after submission
		var returnElement = "#main";
		
		var $forms = $('.udf-block').find('form');
		if ($forms.length){
			$forms.submit(function(){
				$self = $(this);
				$self.parent().load($self.attr('action') + ' ' + returnElement, $self.serialize());
				return false;
			});
		}
	});
})(jQuery);