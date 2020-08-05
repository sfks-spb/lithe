import {GTag} from './gTag.coffee'

export class Themes extends GTag

    constructor: () ->
        super 'Theme', {
            themeChange: {
                'ThemeChanged': 'Change',
            }
        }

    themeChange: (theme, event) =>
        super.event @events.themeChange[event.type], if theme == 'dark' then 'Dark' else 'Light'

    getFormMeta: (formId) ->
        if window._lithe_wpcf7 and window._lithe_wpcf7.hasOwnProperty formId
            return window._lithe_wpcf7[formId]
