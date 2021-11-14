import {GTag} from './gTag.coffee'

export class Infobar extends GTag

    constructor: ->
        super 'Infobar'
        links = document.querySelectorAll '.infobar-link > a'
        link.addEventListener 'click', @detailsClick, false for link in links if links

    detailsClick: (event) =>
        event.preventDefault()
        link = event.currentTarget
        label = document.querySelector '.infobar-content'
        super.event 'Click',
            label.innerText || 'Infobar Link',
            'event_callback': super.withTimeout =>
                document.location = link.getAttribute 'href'
