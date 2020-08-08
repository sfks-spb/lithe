import {Features} from './features.coffee'

export class VkGroups

    constructor: (@selector, options) ->
        @options = Object.assign VkGroups.defaults, options || {}
        @changeTheme detail: window.lithe.theme if window.lithe
        document.addEventListener "DOMContentLoaded", @init, false
        document.addEventListener "ThemeChanged", @changeTheme, false

    init: =>
        @sidebars = document.querySelectorAll '.widget-area'

        if @sidebars
            sidebar.dataset.sidebarWidth = sidebar.clientWidth for sidebar in @sidebars
            observer = new MutationObserver @processMutations
            @containers = document.querySelectorAll '.vk-group'
            for container in @containers
                observer.observe container, childList: true, attributes: false, subtree: false
            @reloadAll()
            window.addEventListener "resize", @changeSize, if Features.passiveListener then { passive: true } else false

    processMutations: (mutations) =>
        for mutation in mutations when mutation.type == "childList"
                for node in mutation.addedNodes when node instanceof HTMLElement
                    node.addEventListener "load", () =>
                        node.closest('.sidebar-widget').classList.add 'vk-widget-loaded'

        return

    changeTheme: (event) =>
        theme = event.detail
        if VkGroups.themes[theme]
            @options["color" + (i + 1)] = VkGroups.themes[theme][i] for color, i in VkGroups.themes[theme]
            @reloadAll()
        else
            console.warn 'VkGroups: theme "' + @theme + '" not defined'

    changeSize: =>
        if @sidebars
            clearTimeout @resizeTimeout if @resizeTimeout
            @resizeTimeout = setTimeout =>
                for sidebar in @sidebars when sidebar.clientWidth != Number sidebar.dataset.sidebarWidth
                    @reloadSidebar sidebar
                return
            , 300

    reloadAll: ->
        @reload container for container in @containers if @containers

    reloadSidebar: (sidebar) ->
        containers = sidebar.querySelectorAll '.vk-group'
        @reload container for container in containers if containers
        sidebar.dataset.sidebarWidth = sidebar.clientWidth

    reload: (container) ->
        if typeof VK == 'object'
            container.closest('.sidebar-widget').classList.remove 'vk-widget-loaded'

            setTimeout =>
                iframe = container.querySelector 'iframe'
                iframe.remove() if iframe
                container.style = ''
                VK.Widgets.Group container.id, @options, container.dataset.groupId
            , 300

            return
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
