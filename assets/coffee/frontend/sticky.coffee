export class Sticky

    constructor: (@selector) ->
        document.addEventListener "DOMContentLoaded", @init, false

    init: =>
        @stickies = document.querySelectorAll @selector

        if @stickies
            options =
                threshold: [1]
                rootMargin: ( if document.querySelector('#wpadminbar') then '-33px 0px 0px 0px' else '-1px 0px 0px 0px' )

            @observer = new IntersectionObserver @processIntersections, options
            @observer.observe sticky for sticky in @stickies

        return null

    processIntersections: ([entry]) =>
        entry.target.classList.toggle 'stuck', ( entry.intersectionRatio < 1 )
