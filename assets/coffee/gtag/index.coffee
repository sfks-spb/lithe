import {Forms} from './forms.coffee'
import {Infobar} from './infobar.coffee'
import {ScrollTop} from './scrolltop.coffee'
import {Themes} from './themes.coffee'
import {Comments} from './comments.coffee'

export GTag =
    forms: new Forms
    infobar: new Infobar
    scrollTop: new ScrollTop
    themes: new Themes
    comments: new Comments
