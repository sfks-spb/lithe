export class GTag

    constructor: (@eventCategory, @events) ->
        window.dataLayer = window.dataLayer || []
        @handleEvents() if @events

    handleEvents: ->
        for eventHandler, events of @events
            for eventName in Object.keys(events)
                document.addEventListener eventName, (event) =>
                    @[eventHandler] event.detail, event
                , false

        return null

    dataLayerPush: ->
        if 'object' == typeof window.dataLayer and 'function' == typeof window.dataLayer.push
            window.dataLayer.push arguments

    event: (eventAction, eventLabel, eventArgs) ->
        eventArgs = eventArgs || {}
        eventArgs['event_category'] = @eventCategory
        eventArgs['event_label'] = eventLabel

        @dataLayerPush 'event', eventAction, eventArgs

    withTimeout: (callback, timeout) ->
        called = false
        fn = () =>
            if not called
                called = true
                callback()
        setTimeout fn, (timeout || 1000)
        return fn
