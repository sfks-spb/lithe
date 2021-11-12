export class Switcheroo
    constructor: ->
        @map = {}
        @inputs = [ '_wpcf7', '_wpcf7_unit_tag', '_wpcf7_container_post' ]

        document.addEventListener "DOMContentLoaded", @init, false

    init: =>
        @processSwicheroo form.querySelector "[data-switcheroo]" for form in document.querySelectorAll '.wpcf7-form'
        return null

    processSwicheroo: (switcheroo) =>
        switcherooInput = switcheroo.querySelector "select"
        return false if not switcheroo or not switcherooInput

        switcherooInput.addEventListener "change", @performSwitcheroo, false

        for part in switcheroo.dataset.switcheroo.split ","
            token = part.split ":"
            @map[token[0]] = token[1]

        return null

    performSwitcheroo: (event) =>
        switcheroo = event.target
        key = switcheroo.value
        return false if typeof @map[key] == "undefined"

        form = switcheroo.closest ".wpcf7-form"
        return false if not form

        data = @getFormData form
        formId = @map[key]
        unitTag = "wpcf7-f" + formId + "-p" + data._wpcf7_container_post.value + "-o1"

        parent = form.closest ".wpcf7"
        parent.id = unitTag

        data._wpcf7.value = formId
        data._wpcf7_unit_tag.value = unitTag

        form.action = window.location.href + "#" + unitTag
        form.wpcf7.id = formId
        form.wpcf7.unitTag = unitTag

        return null

    getFormData: (form) =>
        data = {}
        data[input] = form.querySelector 'input[name="' + input + '"]' for input in @inputs
        return data