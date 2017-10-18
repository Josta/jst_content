$ ->
	maps = $('.map')
	return if (maps.length == 0)

	# google maps settings
	mapOptions = {
		disableDefaultUI: true,
		zoomControl: true,
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		styles: [
			{
				'featureType': 'poi'
				'elementType': 'labels'
				'stylers': [ { 'visibility': 'off' } ]
			}
			{
				'featureType': 'transit'
				'stylers': [ { 'visibility': 'off' } ]
			}
			{
				'featureType': 'road'
				'elementType': 'labels.icon'
				'stylers': [ { 'visibility': 'off' } ]
			}
		]
	}
	# the google maps api v3 must be loaded prior to this

	createMap = (c) ->

		info = undefined
		overlays = []
		markerData = c.children().detach()
		
		### prevent roboto load ###
		head = $('head').get(0)
		insertBefore = head.insertBefore
		head.insertBefore = (newElement, referenceElement) ->
			if (newElement.href && (
					(newElement.href.indexOf('https://fonts.googleapis.com/css?family=Roboto') == 0) ||
					(newElement.href.indexOf('https://fonts.gstatic.com/s/roboto') == 0)))
				console.info('Prevented Roboto from loading!')
			else
				insertBefore.call(head, newElement, referenceElement)
		
		map = new (google.maps.Map)(c.get(0),
			zoom: parseInt(c.data('zoom'))
			maxZoom: 20
			minZoom: 7
			center: new (google.maps.LatLng)(parseFloat(c.data('lat')), parseFloat(c.data('lon'))))
		
		map.setOptions(mapOptions)
		
		addMarker = (name, desc, lat, lon) ->
			marker = new (google.maps.Marker)(
				title: name
				map: map
				position: new (google.maps.LatLng)(lat, lon))
			overlays.push marker
			#desc = if desc and desc.trim().length > 0 then desc.substr(0, 250) + '...' else desc
			infoWindow = new (google.maps.InfoWindow)(
				maxWidth: 300
				content: desc
			)
			google.maps.event.addListener marker, 'click', ->
				if info
					info.close()
				infoWindow.open map, marker
				info = infoWindow
				
			overlays.push infoWindow
			

		removeMarkers = ->
			while overlays[0]
				marker = overlays.pop()
				marker.setMap(null)
			

		
		
		# limit viewable area
		#google.maps.event.addListener map, 'center_changed', ->
		#	x = map.getCenter().lng()
		#	y = map.getCenter().lat()
		#	maxX = 8.6
		#	maxY = 49.25
		#	minX = 8.2
		#	minY = 48.85
		#	map.panTo(new (google.maps.LatLng)(
		#		if y < minY then minY else if y > maxY then maxY else y, if x < minX then minX else if x > maxX then maxX else x))
		
		
		bounds = new (google.maps.LatLngBounds)
		markerData.each ->
			m = $(@)
			pos = new (google.maps.LatLng)(parseFloat(m.data('lat')), parseFloat(m.data('lon')))
			addMarker m.data('name') or '', m.html() or '', pos.lat(), pos.lng()
			#bounds.extend(pos)
			ext = 0.03
			bounds.extend(new google.maps.LatLng(pos.lat() + ext, pos.lng() + ext))
			bounds.extend(new google.maps.LatLng(pos.lat() - ext, pos.lng() - ext))
			
			
		if markerData.size() > 1
			map.fitBounds(bounds)

	google.maps.visualRefresh = true
	maps.each ->
		createMap($(@))
		
	
