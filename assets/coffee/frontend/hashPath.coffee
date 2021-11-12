export HashPath =
    instance: null

    get: ->
        @instance ?= new _HashPath

class _HashPath

    constructor: ->
        @path = null
        window.addEventListener 'hashchange', =>
            @path = @update()
            @changeCallback() if @changeCallback
        , false

    onChange: (@changeCallback) ->

    update: =>
        path = {}
        hashString = window.location.hash
        return path if hashString.length < 2 or not hashString.indexOf '/'

        hashArray = hashString.substring(1).split '/'
        index = 0

        while index < hashArray.length
            key = hashArray[index]
            value = hashArray[index + 1]
            path[key] = value
            index += 2

        return path

    removeLocationHash: ->
        noHashURL = window.location.href.replace /#.*$/, ''
        window.history.replaceState '', document.title, noHashURL

    getPath: =>
        @path ?= @update()

    setPath: (@path) => @syncPath()

    resetPath: => @setPath({})

    syncPath: =>
        hashString = ''
        hashString += '/' + key + '/' + value for key, value of @getPath()
        if hashString.length == 0
            @removeLocationHash()
        else
            window.location.hash = hashString.substring(1)

    search: (query) =>
        path = @getPath()
        return path[query] if path[query] or false

    set: (key, value) =>
        path = @getPath()
        path[key] = value
        @setPath path

    toggle: (key, value, toggle) =>
        if typeof toggle == 'undefined'
            if @search key then @remove key else @set key, value

        if toggle == false then @remove key else @set key, value

    remove: (key) =>
        path = @getPath()
        delete path[key]
        @setPath path