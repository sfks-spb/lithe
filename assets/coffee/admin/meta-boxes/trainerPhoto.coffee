import {Media} from './media.coffee'

export class trainerPhoto extends Media

    constructor: ->
        super '.button.upload', '.button.remove'

    init: =>
        super.init()
        @container = @metabox.querySelector '.trainer-photo-container'
        @input = @metabox.querySelector '[name=photo_id]'

    metaboxId: ->
        return '#trainer-photo.postbox'

    settings: ->
        return
            title: litheAdmin_l10n.trainers_photo
            button:
                text: litheAdmin_l10n.use_this_photo
            multiple: false

    onUpload: (attachment) =>
        @container.innerHTML = '<img src="' + attachment.url + '" alt="" style="width: 100%; max-width: 400px;">'
        @input.value = attachment.id
        @buttons.remove.style = ''

    onRemove: =>
        @container.innerHTML = ''
        @input.value = ''
        @buttons.remove.style = 'display: none;'
