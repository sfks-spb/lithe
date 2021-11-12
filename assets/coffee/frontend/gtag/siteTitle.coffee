import {GTag} from './gTag.coffee'

export class SiteTitle extends GTag

    constructor: ->
        super 'Site Title'
        @button = document.querySelector '#site-title a'
        @button.addEventListener 'click', @siteTitleClick, false if @button

    siteTitleClick: (event) =>
        event.preventDefault();
        super.event 'Click',
            @button.getAttribute('title') || 'Site Logo'
            'event_callback': super.withTimeout =>
                document.location = @button.getAttribute 'href'
