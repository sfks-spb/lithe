import {Html} from './html.coffee'
import {Features} from './features.coffee'

export class VkGroups

    constructor: (@id, @group_id, options) ->
        @options = Object.assign VkGroups.defaults, options || {}
        @changeTheme detail: window.lithe.theme if window.lithe
        document.addEventListener "DOMContentLoaded", @observe, false
        document.addEventListener "ThemeChanged", @changeTheme, false
        window.addEventListener "resize", @changeSize, if Features.passiveListener then { passive: true } else false

    observe: =>
        @window_width = window.innerWidth
        @container = document.getElementById @id
        if @container
            @observer = new MutationObserver @processMutations
            @observer.observe @container, childList: true, attributes: false, subtree: false
            @reload()
        else
            console.warn 'VkGroups: container with id "' + @id + '" not found'

    processMutations: (mutations) =>
        for mutation in mutations
            if mutation.type == "childList"
                for node in mutation.addedNodes when node instanceof HTMLElement
                    @element = node
                    @element.addEventListener "load", (event) =>
                        Html.tag "vk-widget-complete"
                    return

    changeTheme: (event) =>
        theme = event.detail
        if VkGroups.themes[theme]
            @options["color" + (i + 1)] = VkGroups.themes[theme][i] for color, i in VkGroups.themes[theme]
            @reload()
        else
            console.warn 'VkGroups: theme "' + @theme + '" not defined'

    changeSize: () =>
        if @window_width != window.innerWidth && @window_width < lithe.breakpoints['aussie']
            clearTimeout @resizeTimeout if @resizeTimeout
            @resizeTimeout = setTimeout =>
                @reload()
            , 500

        @window_width = window.innerWidth

    reload: ->
        if @container
            if VK
                Html.untag "vk-widget-complete"
                @element.remove() if @element
                @container.style = ''
                VK.Widgets.Group @id, @options, @group_id
            else
                console.error "VkGroups: VK OpenAPI not loaded"

VkGroups.defaults =
    mode:     3
    width:    "auto"
    height:   226
    color1:   "ffffff"
    color2:   "2b587a"
    color3:   "5b7fa6"
    no_cover: 1

VkGroups.themes =
    light: [ "fefefe", "2b587a", "5b7fa6" ]
    dark:  [ "3f3f3f", "fefefe", "777777" ]
