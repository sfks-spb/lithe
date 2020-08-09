import {GTag} from './gTag.coffee'

export class Comments extends GTag

    constructor: () ->
        super 'Comments'
        @form = document.querySelector '#commentform'
        @form.addEventListener 'submit', @commentFormSubmit, false if @form

    commentFormSubmit: (event) =>
        event.preventDefault();
        title = document.querySelector 'h1.entry-title'
        super.event 'Comment',
            if title then title.innerText else ''
            'event_callback': super.withTimeout () =>
                @form.submit()
