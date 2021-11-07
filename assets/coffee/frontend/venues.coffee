import {HttpClient} from './httpClient.coffee'
import {random_number} from './utils.coffee'

export class Venues

    constructor: () ->
        @user = {}
        @venuesItems = []

        ymaps.ready () =>
            @objectManager = new ymaps.ObjectManager()
            @objectManager.objects.options.set
                iconLayout: 'default#image'
                iconImageHref: lithe.home + '/wp-content/themes/lithe/assets/js/placemark.png'
                iconImageSize: [40, 45]
                iconImageOffset: [-20, -44]

            @venuesMap = new ymaps.Map 'venues-map',
                center: [59.880, 30.3],
                zoom: 10,
                controls: ['typeSelector', 'zoomControl']
            ,
                restrictMapArea: [
                    [59, 29]
                    [61, 32]
                ]

            @venuesMap.geoObjects.add @objectManager
            @venuesMap.geoObjects.events.add 'click', @redrawVenuesList
            @venuesMap.balloon.events.add 'close', @redrawVenuesList

            @initLists()
            @getVenues()

        @venueList = document.querySelector '#venues-list'
        @venues = new HttpClient
        @venues.on "load", @venuesComplete

    redrawVenuesList: (event) =>
        venueId = event.get 'objectId'
        for item in document.querySelectorAll '.venue-item'
            if venueId and Number(item.dataset.venueId) != venueId
                item.className += ' hidden'
            else
                item.className = 'venue-item'

        return

    initLists: () =>
        lists = document.querySelectorAll '.sports-list'
        list.addEventListener 'change', (event) =>
            sportId = event.target.value
            list.value = sportId
            @getVenues sportId
        , false for list in lists

        return

    getLocation: () =>
        if not @user.coordinates
            ymaps.geolocation.get().then (result) =>
                result.geoObjects.options.set 'preset', 'islands#bluePersonCircleIcon'
                result.geoObjects.get(0).properties.set
                    hintContent: yandexmaps_l10n.youarehere
                @venuesMap.geoObjects.add result.geoObjects
                @user.coordinates = result.geoObjects.get(0).geometry.getCoordinates()
                @calculateDistance()
                @sortVenues()

            return

        @calculateDistance()
        @sortVenues()

    calculateDistance: () =>
        for venue in @venuesItems
            venueCoordinates = venue.dataset.venueCoordinates.split ','
            venueDistance = ymaps.coordSystem.geo.getDistance @user.coordinates, venueCoordinates
            venue.dataset.venueDistance = venueDistance
            distanceContainer = venue.querySelector '.venue-distance'
            distanceContainer.innerHTML = '~ ' + ymaps.formatter.distance(venueDistance) + ' от вас' if distanceContainer

        return

    sortVenues: () ->
        @venuesItems.sort (a, b) =>
            return +a.dataset.venueDistance - +b.dataset.venueDistance

        @venueList.innerHTML = ''
        @venueList.appendChild venue for venue in @venuesItems

        return

    getVenues: (sportId) =>
        sportId = 0 if typeof sportId == 'undefined'
        @venueList.dataset.sportId = sportId
        @venues.get window.lithe.rest.root + '/venues', { 'sport_id': sportId }

    venuesComplete: (response) =>
        if response.meta.placemarks
            @objectManager.removeAll()
            @objectManager.add response.meta.placemarks

        @venueList.innerHTML = ''

        @venuesItems = []

        for venue in response.data
            item = document.createElement 'div'
            item.className = 'venue-item'
            item.dataset.venueId = venue.id
            item.dataset.venueCoordinates = venue.coords
            html = '<div class="venue-item-wrap"><header><h3 class="venue-title">' + venue.name + '</h3>'
            html += '<span class="venue-address">' + venue.address + '</span>'
            html += '<span class="venue-distance"></span></header>'
            html += '<div class="venue-description">' + venue.description + '</div>' if typeof venue.description != 'undefined' and venue.description != ''
            item.innerHTML = html + '</div>'
            @trainers = new HttpClient
            @trainers.on "load", @trainersComplete.bind item.querySelector('.venue-item-wrap'), this
            @getTrainers venue.id
            @venueList.appendChild item
            @venuesItems.push item

        @getLocation()

        return

    getTrainers: (venueId) =>
        sportId = @venueList.dataset.sportId
        @trainers.get window.lithe.rest.root + '/trainers', { 'venue_id': venueId, 'sport_id': sportId }

    trainersComplete: (self, response) ->
        for trainer in response.data
            sports = []
            sports.push ('<li>' + sport.name + '</li>') for sport in trainer.sports

            item = document.createElement 'div'
            item.className = 'trainer-item'
            html = '<header><h4>' + trainer.last_name + ' ' + trainer.first_name + '</h4>'
            html += self.getTrainerPhoto trainer
            html += '<ul class="trainer-sports sport-tags">' + sports.join('') + '</ul></header>'
            html += '<ul class="trainer-contact-info">'
            html += '<li class="trainer-phone"><a href="tel:' + trainer.phone + '"><i class="fas fa-phone fa-fw"></i><span>' + trainer.phone + '</span></a></li>'
            html += '<li class="trainer-social"><a href="' + trainer.social + '"><i class="fab fa-vk fa-fw"></i></a></li>' if trainer.social
            html += '</ul>'
            html += '<div class="trainer-timetable">' + trainer.timetable + '</div>' if typeof trainer.timetable != 'undefined' and trainer.timetable != ''
            item.innerHTML = html
            this.appendChild item

        return

    getTrainerPhoto: (trainer) ->
        container = document.createElement 'span'
        container.className = 'trainer-photo'

        if not trainer.photo
            container.className += ' placeholder-' + random_number(1, 6)
            return container.outerHTML

        container.innerHTML = '<img src="' + trainer.photo.src + '" alt="">'

        if trainer.photo.width < trainer.photo.height
            container.className += ' portrait'

        return container.outerHTML
