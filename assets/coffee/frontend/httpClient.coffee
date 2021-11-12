export class HttpClient

    constructor: ->
        @xhr = new XMLHttpRequest

    on: (event, callback, type) ->
        type ?= "json"

        @xhr.addEventListener event, (e) =>
            if e.target.responseText
                response = if type == "json" then JSON.parse e.target.responseText else e.target.responseText
                callback response, e
        , false

    post: (url, data, headers) ->
        @ajax "post", url, @buildParams data, headers

    get: (url, data, headers) ->
        params = @buildParams data
        queryString = if params then '?' + params.toString() else ''
        @ajax "get", url + queryString, {}, headers

    put: (url, data, headers) ->
        @ajax "put", url, @buildParams data, headers

    delete: (url, data, headers) ->
        @ajax "delete", url, @buildParams data, headers

    ajax: (method, url, data, headers) ->
        @xhr.open method.toUpperCase(), url
        headers = Object.assign HttpClient.defaults.headers[method], headers
        @xhr.setRequestHeader key, value for key, value of headers
        @xhr.send data

    buildParams: (obj) ->
        params = new URLSearchParams
        params.append k, obj[k] for k of obj
        return params

HttpClient.defaults =
    headers:
        post:
            "Content-type": "application/x-www-form-urlencoded"
        get: {}
        put:
            "Content-type": "application/x-www-form-urlencoded"
        delete: {}
