import {HttpClient} from './httpClient.coffee'

export class Widgets

    constructor: (@selector) ->
        @http = new HttpClient
        document.addEventListener "DOMContentLoaded", @attach, false

    attach: () =>
        @widgets = document.querySelectorAll @selector

        for widget in @widgets
            widgetTransferComplete = @transferComplete.bind widget, this
            @http.on "load", widgetTransferComplete
            @refresh widget

        return

    refresh: (widget) ->
        query =
            _ajax_nonce: window.lithe.ajax.nonce,
            action: "lithe_" + widget.dataset.widgetAction,
            widget_id: widget.dataset.widgetId

        @http.post window.lithe.ajax.url, query

    transferComplete: (widgets, response) ->
        if not response.success
            console.error 'Widgets: ' + response.data
            return

        this.innerHTML = response.data.html
        this.dataset.widgetUpdated = response.data.updated

        if typeof this.dataset.widgetUpdateInterval != 'undefined'
            setTimeout =>
                widgets.refresh this
            , this.dataset.widgetUpdateInterval * 1000

        return
