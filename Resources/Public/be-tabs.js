(function($) {
	$(function() {
		
		$('.jstcontent-be-tabs').each(function() {
			var tabs = $(this).children();
			var tabareas = $(this).siblings('.grid-visibility-toggle').find('.flux-grid-column').addClass('jstcontent-be-tabarea');
			$(this).siblings('.grid-visibility-toggle').find('col:not(:first-child)').css('display', 'none');
			
			tabs.each(function(index) {
				$(this).click(function() {
					tabs.add(tabareas).removeClass('active');			
					$(this).add(tabareas.eq(index)).addClass('active');		
				});
			});
			
			tabs.first().add(tabareas.first()).addClass('active');
		});
		
	});
})(TYPO3.jQuery);