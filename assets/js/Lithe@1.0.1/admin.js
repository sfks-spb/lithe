(function () {
    'use strict';

    var Media = class Media {
      constructor(uploadButton, removeButton) {
        this.init = this.init.bind(this);
        this.uploadButton = uploadButton;
        this.removeButton = removeButton;
        this.buttons = {};
        document.addEventListener('DOMContentLoaded', this.init, false);
      }

      init() {
        this.metabox = document.querySelector(this.metaboxId());
        this.buttons.upload = this.metabox.querySelector(this.uploadButton);
        this.buttons.remove = this.metabox.querySelector(this.removeButton);
        if (this.buttons.upload) {
          this.buttons.upload.addEventListener('click', () => {
            if (!this.frame) {
              this.frame = wp.media(this.settings());
              this.frame.on('select', () => {
                return this.onUpload(this.frame.state().get('selection').first().toJSON());
              });
            }
            return this.frame.open();
          });
        }
        if (this.buttons.remove) {
          return this.buttons.remove.addEventListener('click', () => {
            return this.onRemove();
          });
        }
      }

    };

    var boundMethodCheck = function(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new Error('Bound instance method accessed before binding'); } };

    var trainerPhoto = class trainerPhoto extends Media {
      constructor() {
        super('.button.upload', '.button.remove');
        this.init = this.init.bind(this);
        this.onUpload = this.onUpload.bind(this);
        this.onRemove = this.onRemove.bind(this);
      }

      init() {
        boundMethodCheck(this, trainerPhoto);
        super.init();
        this.container = this.metabox.querySelector('.trainer-photo-container');
        return this.input = this.metabox.querySelector('[name=photo_id]');
      }

      metaboxId() {
        return '#trainer-photo.postbox';
      }

      settings() {
        return {
          title: litheAdmin_l10n.trainers_photo,
          button: {
            text: litheAdmin_l10n.use_this_photo
          },
          multiple: false
        };
      }

      onUpload(attachment) {
        boundMethodCheck(this, trainerPhoto);
        this.container.innerHTML = '<img src="' + attachment.url + '" alt="" style="width: 100%; max-width: 400px;">';
        this.input.value = attachment.id;
        return this.buttons.remove.style = '';
      }

      onRemove() {
        boundMethodCheck(this, trainerPhoto);
        this.container.innerHTML = '';
        this.input.value = '';
        return this.buttons.remove.style = 'display: none;';
      }

    };

    ({
      trainerPhoto: new trainerPhoto()
    });

})();
