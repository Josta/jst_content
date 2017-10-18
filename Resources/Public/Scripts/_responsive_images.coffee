$ ->
	$('figure.image noscript').each ->
		$($(@).html()).attr(sizes: $(@).closest('.image').width() + 'px').insertAfter(@);