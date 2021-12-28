import {HttpClient} from './httpClient.coffee'
import {HashPath} from './hashPath.coffee'
import {randomNumber} from './utils.coffee'

export class Venues

    constructor: ->
        @user = {}
        @venuesItems = []

        HashPath.get().onChange @init

        ymaps.ready =>
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

            @venuesMap.behaviors.disable 'scrollZoom'
            @venuesMap.geoObjects.add @objectManager
            @venuesMap.geoObjects.events.add 'click', @redrawVenueLists
            @venuesMap.balloon.events.add 'close', @redrawVenueLists

            @init()

        @venueList = document.querySelector '#venues-list'
        @venues = new HttpClient
        @venues.on "load", @venuesComplete

    init: =>
        slug = HashPath.get().search('sport') or 'all'
        @getVenues sportId if sportId = @initLists(slug)

    redrawVenueLists: (event) =>
        venueId = event.get 'objectId'

        for item in document.querySelectorAll '.venue-item'
            if venueId
                item.classList.toggle 'translucent', ( Number(item.dataset.venueId) != venueId )
            else
                item.classList.remove 'translucent'

        return

    initLists: (slug) =>
        ret = false
        lists = document.querySelectorAll '.sports-list'

        for list in lists
            list.addEventListener 'change', =>
                sportId = event.target.value
                option = list.querySelector 'option[value="' + sportId + '"]'
                slug = option.dataset.slug
                HashPath.get().toggle 'sport', slug, (slug != 'all')
                @getVenues sportId

            if slug
                option = list.querySelector 'option[data-slug="' + slug + '"]'
                ret = list.value = option.value if option

        return ret

    getLocation: =>
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

    calculateDistance: =>
        for venue in @venuesItems
            if venue.dataset.venueCoordinates
                venueCoordinates = venue.dataset.venueCoordinates.split ','
                venueDistance = ymaps.coordSystem.geo.getDistance @user.coordinates, venueCoordinates
                venue.dataset.venueDistance = venueDistance
                distanceContainer = venue.querySelector '.venue-distance'
                distanceContainer.innerHTML = '~ ' + ymaps.formatter.distance(venueDistance) + ' ' + lithe_l10n.from_your_position if distanceContainer

        return

    sortVenues: ->
        @venuesItems.sort (a, b) =>
            return +a.dataset.venueDistance - +b.dataset.venueDistance

        @venueList.innerHTML = ''
        @venueList.appendChild venue for venue in @venuesItems

        return

    getVenues: (sportId) =>
        sportId = 0 if typeof sportId == 'undefined'

        @venueList.dataset.sportId = sportId
        @venueList.classList.add 'loading'
        @venues.get window.lithe.rest.root + '/venues', { 'sport_id': sportId }

    venuesComplete: (response) =>
        if response.meta.placemarks
            @objectManager.removeAll()
            @objectManager.add response.meta.placemarks

        @venueList.innerHTML = ''
        @venueList.className = 'single-column' if response.meta.count == 1
        @venueList.className = 'two-columns'   if response.meta.count == 2
        @venueList.className = 'three-columns' if response.meta.count == 3
        @venueList.className = 'four-columns'  if response.meta.count > 3

        @venuesItems = []

        for venue in response.data
            item = document.createElement 'div'
            item.classList.add 'venue-item', 'loading'
            item.dataset.venueId = venue.id
            item.dataset.venueCoordinates = venue.coords
            html = '<div class="venue-item-wrap"><header><h3 class="venue-title">' + venue.name + '</h3>'
            html += '<span class="venue-address">' + venue.address + '</span>'
            html += '<span class="venue-distance"></span>' if venue.coords
            html += '</header>'
            html += '<div class="venue-description">' + venue.description + '</div>' if typeof venue.description != 'undefined' and venue.description != ''
            html += @getTrainerPlaceholder 'primary'
            html += @getTrainerPlaceholder 'secondary'
            item.innerHTML = html + '</div>'

            @trainers = new HttpClient
            @trainers.on "load", @trainersComplete.bind item.querySelector('.venue-item-wrap'), item, this
            @getTrainers venue.id
            @venueList.appendChild item
            @venuesItems.push item

        @venueList.classList.remove 'loading'
        @getLocation()

        return

    getTrainers: (venueId) =>
        sportId = @venueList.dataset.sportId
        @trainers.get window.lithe.rest.root + '/trainers', { 'venue_id': venueId, 'sport_id': sportId }

    trainersComplete: (item, self, response) ->
        item.classList.remove 'loading'

        for trainer in response.data
            sports = []
            sports.push ('<li>' + sport.name + '</li>') for sport in trainer.sports

            item = document.createElement 'div'
            item.classList.add 'trainer-item'
            html = '<header><h4>' + trainer.last_name + ' ' + trainer.first_name + '</h4>'
            html += self.getTrainerPhoto trainer
            html += '<ul class="trainer-sports sport-tags">' + sports.join('') + '</ul></header>'
            html += '<ul class="trainer-contact-info">'
            html += '<li class="trainer-phone"><a class="trainer-phone-link" href="tel:' + trainer.phone + '"><i class="fas fa-phone fa-fw ignores-pointer-events"></i>' + trainer.phone + '</a></li>'
            html += '<li class="trainer-social"><a class="trainer-social-link" href="' + trainer.social + '"><i class="fab fa-vk fa-fw ignores-pointer-events"></i></a></li>' if trainer.social
            html += '</ul>'
            html += '<div class="trainer-timetable">' + trainer.timetable + '</div>' if typeof trainer.timetable != 'undefined' and trainer.timetable != ''
            item.innerHTML = html
            this.appendChild item

        return

    getTrainerPhoto: (trainer) ->
        container = document.createElement 'span'
        container.classList.add 'trainer-photo'

        if not trainer.photo
            container.classList.add 'placeholder-' + randomNumber(1, 6)
            return container.outerHTML

        container.innerHTML = '<img src="' + trainer.photo.src + '" alt="">'

        if trainer.photo.width < trainer.photo.height
            container.classList.add 'portrait'

        return container.outerHTML

    getTrainerPlaceholder: (classNames) ->
        html  = ''
        html += '<div class="trainer-item placeholder ' + classNames + '"><header><span class="trainer-photo"></span></header>'
        html += '<ul class="trainer-contact-info"><li class="trainer-phone"></li><li class="trainer-social"></li></ul></div>'

        return html
