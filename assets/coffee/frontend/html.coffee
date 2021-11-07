export Html =
    tag: (classname) ->
        Html.node.classList.add classname

    untag: (classname) ->
        Html.node.classList.remove classname

    has: (classname) ->
        return Html.node.classList.contains classname

Html.node = document.html || document.getElementsByTagName('html')[0]
