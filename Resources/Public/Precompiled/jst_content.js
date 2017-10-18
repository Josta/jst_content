

$(function() {
	return $('figure.image noscript').each(function() {
		return $($(this).html()).attr({
			sizes: $(this).closest('.image').width() + 'px'
		}).insertAfter(this);
	});
});



$(function() {
	return $('.equal-height').matchHeight();
});



$(function() {
	var createMap, mapOptions, maps;
	maps = $('.map');
	if (maps.length === 0) {
		return;
	}
	mapOptions = {
		disableDefaultUI: true,
		zoomControl: true,
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		styles: [
			{
				'featureType': 'poi',
				'elementType': 'labels',
				'stylers': [
					{
						'visibility': 'off'
					}
				]
			}, {
				'featureType': 'transit',
				'stylers': [
					{
						'visibility': 'off'
					}
				]
			}, {
				'featureType': 'road',
				'elementType': 'labels.icon',
				'stylers': [
					{
						'visibility': 'off'
					}
				]
			}
		]
	};
	createMap = function(c) {
		var addMarker, bounds, head, info, insertBefore, map, markerData, overlays, removeMarkers;
		info = void 0;
		overlays = [];
		markerData = c.children().detach();
		/* prevent roboto load
		*/

		head = $('head').get(0);
		insertBefore = head.insertBefore;
		head.insertBefore = function(newElement, referenceElement) {
			if (newElement.href && ((newElement.href.indexOf('https://fonts.googleapis.com/css?family=Roboto') === 0) || (newElement.href.indexOf('https://fonts.gstatic.com/s/roboto') === 0))) {
				return console.info('Prevented Roboto from loading!');
			} else {
				return insertBefore.call(head, newElement, referenceElement);
			}
		};
		map = new google.maps.Map(c.get(0), {
			zoom: parseInt(c.data('zoom')),
			maxZoom: 20,
			minZoom: 7,
			center: new google.maps.LatLng(parseFloat(c.data('lat')), parseFloat(c.data('lon')))
		});
		map.setOptions(mapOptions);
		addMarker = function(name, desc, lat, lon) {
			var infoWindow, marker;
			marker = new google.maps.Marker({
				title: name,
				map: map,
				position: new google.maps.LatLng(lat, lon)
			});
			overlays.push(marker);
			infoWindow = new google.maps.InfoWindow({
				maxWidth: 300,
				content: desc
			});
			google.maps.event.addListener(marker, 'click', function() {
				if (info) {
					info.close();
				}
				infoWindow.open(map, marker);
				return info = infoWindow;
			});
			return overlays.push(infoWindow);
		};
		removeMarkers = function() {
			var marker, _results;
			_results = [];
			while (overlays[0]) {
				marker = overlays.pop();
				_results.push(marker.setMap(null));
			}
			return _results;
		};
		bounds = new google.maps.LatLngBounds;
		markerData.each(function() {
			var ext, m, pos;
			m = $(this);
			pos = new google.maps.LatLng(parseFloat(m.data('lat')), parseFloat(m.data('lon')));
			addMarker(m.data('name') || '', m.html() || '', pos.lat(), pos.lng());
			ext = 0.03;
			bounds.extend(new google.maps.LatLng(pos.lat() + ext, pos.lng() + ext));
			return bounds.extend(new google.maps.LatLng(pos.lat() - ext, pos.lng() - ext));
		});
		if (markerData.size() > 1) {
			return map.fitBounds(bounds);
		}
	};
	google.maps.visualRefresh = true;
	return maps.each(function() {
		return createMap($(this));
	});
});



$(function() {
	var options, sliders, value;
	sliders = $('.jssor-slider-wrapper');
	if (sliders.length === 0) {
		return;
	}
	value = {
		stretch: 0,
		contain: 1,
		cover: 2,
		actual_size: 4,
		contain_large: 5,
		never: 0,
		hover: 1,
		always: 2,
		none: 0,
		horizontal: 1,
		vertical: 2,
		both: 3,
		quad: $JssorEasing$.$EaseOutQuad,
		quint: $JssorEasing$.$EaseOutQuint,
		random: 0,
		sequence: 1
	};
	options = {
		$FillMode: value.cover,
		$AutoPlay: true,
		$PlayOrientation: value.horizontal,
		$DragOrientation: value.horizontal,
		$SlideEasing: value.quint,
		$SlideDuration: 1200,
		$Idle: 4000,
		$BulletNavigatorOptions: {
			$Class: $JssorBulletNavigator$,
			$ChanceToShow: value.always,
			$AutoCenter: value.horizontal,
			$Rows: 1
		},
		$ArrowNavigatorOptions: {
			$Class: $JssorArrowNavigator$,
			$ChanceToShow: value.hover,
			$AutoCenter: value.vertical
		},
		$ThumbnailNavigatorOptions: {
			$Class: $JssorThumbnailNavigator$,
			$ChanceToShow: value.always,
			$AutoCenter: value.horizontal,
			$Cols: 3,
			$Rows: 1,
			$Orientation: value.horizontal
		},
		$SlideshowOptions: {
			$Class: $JssorSlideshowRunner$,
			$Transitions: [
				{
					$Duration: 1200,
					$Opacity: 2
				}
			],
			$TransitionsOrder: value.sequence,
			$ShowLink: true
		}
	};
	return sliders.each(function() {
		var container, scaleSlider, slider;
		if ($('.slide', this).length === 0) {
			return;
		}
		container = $(this).parent();
		slider = new $JssorSlider$(this, options);
		window.sliderTest = slider;
		scaleSlider = function() {
			var ht, oh, ow, wd;
			wd = container.width();
			if (wd) {
				ow = slider.$OriginalWidth();
				oh = slider.$OriginalHeight();
				ht = container.height();
				if (ow / oh < wd / ht) {
					return slider.$ScaleHeight(ht);
				} else {
					return slider.$ScaleWidth(wd);
				}
			} else {
				return window.setTimeout(ScaleSlider, 30);
			}
		};
		scaleSlider();
		$(window).bind("load", scaleSlider);
		$(window).bind("resize", scaleSlider);
		return $(window).bind("orientationchange", scaleSlider);
	});
});



$(function() {
	return $('.youtubevideo [data-target-frame]').click(function() {
		$($(this).data('target-frame')).each(function() {
			return $(this).attr({
				src: $(this).data('frame-src')
			});
		});
		return $(this).hide();
	});
});

