import {Forms} from './forms.coffee'
import {SiteTitle} from './siteTitle.coffee'
import {Infobar} from './infobar.coffee'
import {ScrollTop} from './scrolltop.coffee'
import {Themes} from './themes.coffee'
import {Comments} from './comments.coffee'

export GTag =
    forms: new Forms
    siteTitle: new SiteTitle
    infobar: new Infobar
    scrollTop: new ScrollTop
    themes: new Themes
    comments: new Comments
