import {GTag} from './gTag.coffee'

export class Infobar extends GTag

    constructor: () ->
        super 'Infobar'
        links = document.querySelectorAll '.infobar-link'
        if links
            link.addEventListener 'click', @detailsClick, false for link in links

    detailsClick: (event) =>
        label = event.target.previousElementSibling
        super.event 'Click', label.innerText if label
