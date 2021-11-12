import {HttpClient} from './httpClient.coffee'
import {range} from './utils.coffee'

export class Views

    constructor: ->
        @http = new HttpClient
        @http.on "load", @transferComplete
        @storage = window.localStorage
        @views = @getViews()
        window.addEventListener "load", @init, false

    init: =>
        @posts = document.querySelectorAll '.post'

        if @posts
            options =
                rootMargin: '0px 0px 50px 0px'
                threshold: 1

            @observer = new IntersectionObserver @storeView, options

            for post in @posts
                paragraph = post.querySelector 'p:last-of-type'
                @observer.observe paragraph || post.querySelector 'div:last-of-type'

            return null

    storeView: ([entry]) =>
        if entry.isIntersecting
            @observer.unobserve entry.target
            post = entry.target.closest '.post'

            restUri = window.lithe.rest.root + '/posts/' + post.dataset.id + '/views'

            if not @seen post.dataset.id
                @view post.dataset.id
                @http.post restUri
            else
                @http.get restUri

    getViews: ->
        postViews = @storage.getItem 'postViews'
        return if postViews then JSON.parse postViews else []

    seen: (postId) ->
        return @views.includes postId

    view: (postId) ->
        @views.shift() while @views.length > 49
        @views.push postId
        @storage.setItem 'postViews', JSON.stringify @views

    transferComplete: (response) =>
        counter = document.querySelector '#post-' + response.post_id + ' .entry-views-count'
        return if not counter
        previous = Number counter.innerHTML
        difference = response.views - previous
        # don't animate more than 256 views difference or with no difference if view count
        return counter.innerHTML = response.views if difference >= 256 or difference == 0
        @animateCounter counter, range difference, previous + 1

    animateCounter: (element, views) ->
        cursor = 0
        animationInterval = setInterval =>
            element.innerHTML = views[cursor]
            clearInterval animationInterval if typeof views[++cursor] == 'undefined'
        , 20
