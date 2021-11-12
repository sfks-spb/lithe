export Features =

    passiveListener: =>
        passiveSupported = false
        try
            window.addEventListener 'test', null, Object.defineProperty {}, 'passive', { get: -> passiveSupported = true }
        catch err

        return passiveSupported
