import {HttpClient} from './httpClient.coffee'

export class Venues

    constructor: () ->
        @http = new HttpClient
        @http.on "load", @transferComplete
        document.addEventListener "DOMContentLoaded", @init, false

    init: () =>
        sports = document.querySelectorAll '.sport-item'

        if sports
            sport.addEventListener 'click', @getVenues, false for sport in sports

    getVenues: (event) =>
        event.preventDefault()

        sportId = event.target.dataset.sportId

        http.get window.lithe.rest.root + '/venues', { 'sport_id': sportId, 'include_trainers': 1 } if sportId

    transferComplete: (response) =>
        console.log response