import {GTag} from './gTag.coffee'

export class ScrollTop extends GTag

    constructor: ->
        super 'Scroll Top'
        button = document.querySelector '#go-top'
        button.addEventListener 'click', @scrollTopClick, false if button

    scrollTopClick: (event) =>
        title = document.querySelector 'h1.entry-title'
        super.event 'Click', if title then title.innerText else 'Scroll Top Button'
