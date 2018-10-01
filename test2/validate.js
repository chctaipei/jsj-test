function hasAttr(attributes, attr)
{
    for (var j = 0; j < attributes.length; j++) {
        if (attributes[j].name == attr) {
             return true;
        }
    }
    return false;
}

exports.checkElementAttr = function(document, element, attr)
{
    var myNodeList = document.querySelectorAll(element);
    var notfound = 0;
    for (var i = 0; i < myNodeList.length; i++) {
        if (!hasAttr(myNodeList[i].attributes, 'alt')) {
            notfound++;
        }
    }

    return notfound;
}

exports.checkElementCount = function(document, element, count)
{
    var myNodeList = document.querySelectorAll(element);
    if (myNodeList.length > count) {
        return false;
    }
    return true;
}

exports.checkHeadTitle = function(document, meta)
{
    if (document.head.getElementsByTagName("title").length == 0) {
        return false;
    }
    return true;
}

exports.checkHeadMeta = function(document, meta)
{
    var notFound = [];
    meta.forEach(function(name) {
        var search = 'meta[name="' + name + '"]';
        var element = document.head.querySelector(search);
        if (element == null) {
            notFound.push(name);
            // output('This HTML without <meta name="' + name + '"> tag' + "\n");
        }
    });
    return notFound;
}
