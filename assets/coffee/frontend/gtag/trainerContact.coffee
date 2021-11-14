import {GTag} from './gTag.coffee'

export class TrainerContact extends GTag

    constructor: ->
        super 'Contact'
        list = document.querySelector '#venues-list'
        if list
            list.addEventListener 'click', (event) =>
                return if not event.target
                if event.target.className == 'trainer-phone-link' or event.target.className == 'trainer-social-link'
                    event.preventDefault()
                    trainer = event.target.closest('.trainer-item').querySelector('h4').textContent
                    @phoneClick event.target, trainer if event.target.className == 'trainer-phone-link'
                    @socialClick event.target, trainer if event.target.className == 'trainer-social-link'

    phoneClick: (link, trainer) ->
        super.event 'Phone Click',
            trainer,
            'event_callback': super.withTimeout =>
                document.location = link.getAttribute 'href'

    socialClick: (link, trainer) ->
        super.event 'Social Click',
            trainer,
            'event_callback': super.withTimeout =>
                document.location = link.getAttribute 'href'
