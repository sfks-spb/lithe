export class Media

    constructor: (@uploadButton, @removeButton) ->
        @buttons = {}
        document.addEventListener 'DOMContentLoaded', @init, false

    init: =>
        @metabox = document.querySelector @metaboxId()
        @buttons.upload = @metabox.querySelector @uploadButton
        @buttons.remove = @metabox.querySelector @removeButton

        if @buttons.upload
            @buttons.upload.addEventListener 'click', =>
                if not @frame
                    @frame = wp.media @settings()
                    @frame.on 'select', =>
                        @onUpload @frame.state().get('selection').first().toJSON()

                @frame.open()

        if @buttons.remove
            @buttons.remove.addEventListener 'click', =>
                @onRemove()
