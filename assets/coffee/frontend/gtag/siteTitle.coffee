import {GTag} from './gTag.coffee'

export class SiteTitle extends GTag

    constructor: ->
        super 'Site Title'
        button = document.querySelector '#site-title a'
        button.addEventListener 'click', @siteTitleClick, false if button
        compact = document.querySelector '.compact-logo'
        compact.addEventListener 'click', @siteTitleClick, false if compact

    siteTitleClick: (event) =>
        event.preventDefault()
        link = event.currentTarget
        super.event 'Click',
            link.getAttribute('title') or 'Site Logo'
            'event_callback': super.withTimeout =>
                document.location = link.getAttribute 'href'
