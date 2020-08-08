import {Html} from './html.coffee'
import {VkGroups} from './vkGroups.coffee'
import {Widgets} from './widgets.coffee'
import {Carousels} from './carousels.coffee'
import {Collapsible} from './collapsible.coffee'
import {ScrollTop} from './scrollTop.coffee'
import {Sticky} from './sticky.coffee'
import {Views} from './views.coffee'
import {GTag} from './gtag/index.coffee'
import {Venues} from './venues.coffee'

Lithe =
    jsIsAvailable: ->
        Html.untag 'nojs'

    init: ->
        Lithe.jsIsAvailable()
        Lithe.vkGroups    = new VkGroups '.vk-group'
        Lithe.widgets     = new Widgets '[data-widget-action]'
        Lithe.carousels   = new Carousels
        Lithe.collapsible = new Collapsible '.collapsible-toggle'
        Lithe.sticky      = new Sticky '.sticky'
        Lithe.scrollTop   = new ScrollTop '#go-top', 768
        Lithe.views       = new Views
        Lithe.venues      = new Venues if document.querySelector('body.page-template-template-venues')

        return

window.lithe = Object.assign Lithe, window.lithe
window.lithe.init()
