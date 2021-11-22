import {GTag} from './gTag.coffee'

export class TrainerContact extends GTag

    constructor: ->
        super 'Contact'
        list = document.querySelector '#venues-list'
        if list
            list.addEventListener 'click', (event) =>
                return if not event.target
                link = event.target
                if link.className == 'trainer-phone-link' or link.className == 'trainer-social-link'
                    event.preventDefault()
                    trainer = link.closest('.trainer-item').querySelector('h4').textContent
                    @phoneClick link, trainer if link.className == 'trainer-phone-link'
                    @socialClick link, trainer if link.className == 'trainer-social-link'

    phoneClick: (link, trainer) ->
        super.event 'Click',
            trainer + ' (' + link.textContent + ')',
            'event_callback': super.withTimeout =>
                document.location = link.getAttribute 'href',
            'value': 50

    socialClick: (link, trainer) ->
        super.event 'Click',
            trainer + ' (' + link.getAttribute('href') + ')',
            'event_callback': super.withTimeout =>
                document.location = link.getAttribute 'href',
            'value': 10
