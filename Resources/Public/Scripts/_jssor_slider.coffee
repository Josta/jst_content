$ ->
	sliders = $('.jssor-slider-wrapper')
	return if (sliders.length == 0)

	# for options see http://www.jssor.com/development/reference-options.html
	
	value = {
		# aspect
		stretch: 0, contain: 1, cover: 2, actual_size: 4, contain_large: 5,
		# occurrence
		never: 0, hover: 1, always: 2,
		# alignment
		none: 0, horizontal: 1, vertical: 2, both: 3,
		# easing
		quad: $JssorEasing$.$EaseOutQuad, quint: $JssorEasing$.$EaseOutQuint,
		# order
		random: 0, sequence: 1,
	}
	
	options = {		
		$FillMode: value.cover,
		$AutoPlay: true,
		$PlayOrientation: value.horizontal,
		$DragOrientation: value.horizontal,
		#$SlideWidth: 600,
		#$SlideHeight: 300,
		$SlideEasing: value.quint,
		$SlideDuration: 1200,
		$Idle: 4000,
		
		$BulletNavigatorOptions: {
			$Class: $JssorBulletNavigator$,
			$ChanceToShow: value.always,
			$AutoCenter: value.horizontal,
			$Rows: 1,
		},
		$ArrowNavigatorOptions: {
			$Class: $JssorArrowNavigator$,
			$ChanceToShow: value.hover,
			$AutoCenter: value.vertical,
		},
		$ThumbnailNavigatorOptions: {
			$Class: $JssorThumbnailNavigator$,
			$ChanceToShow: value.always,
			$AutoCenter: value.horizontal,
			$Cols: 3,
			$Rows: 1,
			$Orientation: value.horizontal,
		}
		$SlideshowOptions: {
			$Class: $JssorSlideshowRunner$,
			$Transitions: [
				{$Duration: 1200, $Opacity: 2} 	# Fade
			],
			$TransitionsOrder: value.sequence,
			$ShowLink: true
		},
	}
	
	sliders.each ->
		# don't show sliders without images
		if ($('.slide', @).length == 0)
			return;
	
		# init slider
		container = $(@).parent()
		slider = new $JssorSlider$(@, options)

		window.sliderTest = slider;

		#responsive code
		scaleSlider = ->
			wd = container.width()
			if (wd)
				ow = slider.$OriginalWidth()
				oh = slider.$OriginalHeight()
				ht = container.height()

				# cover
				#if (ow / oh > wd / ht)
				#	slider.$ScaleHeight(ht)
				#else 
				#	slider.$ScaleWidth(wd)

				# contain
				if (ow / oh < wd / ht)
						slider.$ScaleHeight(ht)
				else 
					slider.$ScaleWidth(wd)

				#slider.$ScaleWidth(wd);
				#el = $(slider.$Elmt)
				#el.css(height: ht + 'px')
				#el.children('div').css(transform: 'none', height: ht + 'px', width: wd + 'px')
			else
				window.setTimeout(ScaleSlider, 30)	
		scaleSlider()
		$(window).bind("load", scaleSlider)
		$(window).bind("resize", scaleSlider)
		$(window).bind("orientationchange", scaleSlider)
