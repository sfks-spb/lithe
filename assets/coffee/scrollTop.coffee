import {Features} from './features.coffee'

export class ScrollTop

    constructor: (@selector, offset) ->
        @offset = offset || 200
        document.addEventListener "DOMContentLoaded", @init, false

    init: () =>
        @button = document.querySelector @selector

        if @button
            @button.addEventListener "click", @goTop, false
            document.addEventListener "scroll", @scroll, if Features.passiveListener then { passive: true } else false
            @scroll()

    scroll: () =>
        if document.documentElement.scrollTop > @offset then @showButton() else @hideButton()

    hideButton: () ->
        @button.classList.remove 'active' if @button

    showButton: () ->
        @button.classList.add 'active' if @button

    goTop: () =>
        document.documentElement.scrollTop = 0

