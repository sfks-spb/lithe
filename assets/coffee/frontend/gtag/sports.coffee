import {GTag} from './gTag.coffee'

export class Sports extends GTag

    constructor: ->
        super 'Sport'
        selects = document.querySelectorAll 'select.sports-list'
        select.addEventListener 'change', @sportSelected, false for select in selects

    sportSelected: (event) =>
        event.preventDefault()
        select = event.target
        option = select.querySelector 'option[value="' + select.value + '"]'
        super.event 'Selected',
            if option then option.textContent else select.value