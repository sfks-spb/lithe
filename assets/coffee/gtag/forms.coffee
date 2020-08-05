import {GTag} from './gTag.coffee'

export class Forms extends GTag

    constructor: () ->
        super 'Contact Form', {
            wpcf7: {
                'wpcf7mailsent': 'Send',
                'wpcf7mailfailed': 'Error',
                'wpcf7spam': 'Spam',
                'wpcf7submit': 'Submit'
            }
        }

    wpcf7: (form, event) =>
        formMeta = @getFormMeta form.contactFormId

        if formMeta
            @eventCategory = 'Registration Form' if formMeta.isRegistrationForm
            super.event @events.wpcf7[event.type], formMeta.title

    getFormMeta: (formId) ->
        if window._lithe_wpcf7 and window._lithe_wpcf7.hasOwnProperty formId
            return window._lithe_wpcf7[formId]
