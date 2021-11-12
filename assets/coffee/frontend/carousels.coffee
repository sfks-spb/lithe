export class Carousels

    constructor: ->
        document.addEventListener "DOMContentLoaded", @init, false

    init: =>
        carousels = {}
        carousels[id] = jQuery("#owl-" + id).owlCarousel options for id, options of Carousels.settings when Carousels.settings[id]
        return carousels

Carousels.settings =
    home:
        items: 1
        loop: true
        lazyLoad: true
        lazyLoadEager: 1
        nav: false
        autoplay: true
        autoplayTimeout: 7000
        animateOut: "fadeOut"

    sponsors:
        loop: true
        nav: false
        dots: false
        autoplay: true
        autoplayTimeout: 3000
        autoplayHoverPause: true
        responsive:
            [ lithe.breakpoints['chihuahua'] ]:
                items: 1
            [ lithe.breakpoints['corgi'] ]:
                items: 2
            [ lithe.breakpoints['aussie'] ]:
                items: 3
