$ ->

	# unlock iframes on click
	$('.youtubevideo [data-target-frame]').click ->
		$($(@).data('target-frame')).each ->
			$(@).attr(src: $(this).data('frame-src'))
		$(@).hide()
