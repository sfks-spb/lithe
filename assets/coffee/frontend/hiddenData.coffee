import {httpClient} from './httpClient.coffee'

export default class HiddenData

    constructor: (@selector) ->
        @http = new httpClient
        document.addEventListener "DOMContentLoaded", @attach

    attach: =>
        @fields = document.querySelectorAll @selector

        if @fields
            for field in @fields
                if field.dataset.realtime then @observeRealtime field else @observe field

    unhide: (field) ->
        query =
            _ajax_nonce: window.lithe.ajaxnonce,
            action: "lithe_unhide_data",
            key: field.dataset.hiddenKey

        boundGetData = @getData.bind field
        @http.on "load", boundGetData
        @http.post window.lithe.ajaxurl, query

    observe: (field, interval) ->
        at = field.dataset.at
        till = field.dataset.till
        now = Math.floor new Date().getTime() / 1000

        if till and now > till
            clearInterval interval if interval
            field.classList.add "hidden"
            setTimeout ->
                field.innerHTML = "";
            , 2000

        if not at or now > at
            clearInterval interval if interval and not till
            @unhide field if hidden
            hidden = false

    observeRealtime: (field) ->
        callback = ->
            @observe field, observeInterval

        callback = callback.bind @

        observeInterval = setInterval ->
            callback()
        , 1000


    getData: (event) ->
        response = event.target.response
        json = JSON.parse response

        if not json.success then return

        this.innerHTML = json.data
