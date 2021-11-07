import {Html} from './html.coffee'

export class Collapsible

    constructor: (@selector) ->
        document.addEventListener "DOMContentLoaded", @attach, false

    attach: () =>
        @toggles = document.querySelectorAll @selector
        toggle.addEventListener 'change', @changed, false for toggle in @toggles
        return

    changed: (event) =>
        cb = event.target
        classname = cb.id + '-expanded'
        if cb.checked then Html.tag classname else Html.untag classname
