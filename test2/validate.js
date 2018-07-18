function hasAttr(attributes, attr)
{
    for (var j = 0; j < attributes.length; j++) {
        if (attributes[j].name == attr) {
             return true;

        }
    }
    return false;
}

exports.checkElementAttr = function(document, element, attr, output)
{
    var myNodeList = document.querySelectorAll(element);
    var notfound = 0;
    for (var i = 0; i < myNodeList.length; i++) {
        if (!hasAttr(myNodeList[i].attributes, 'alt')) {
            notfound++;
        }
    }

    if (notfound > 0) {
        output("There are " + notfound + " <" + element +"> tag without "+ attr +" attribute");
    }
}

exports.checkElementCount = function(document, element, count, output)
{
    var myNodeList = document.querySelectorAll(element);
    if (myNodeList.length > count) {
        output("This HTML has more than " + count + " <" + element + "> tag");
    }
}

exports.checkHead = function(document, meta, output)
{
    if (document.head.getElementsByTagName("title").length == 0) {
        output("This HTML without <title> tag");
    }

    meta.forEach(function(name) {
        var search = 'meta[name="' + name + '"]';
        var element = document.head.querySelector(search);
        if (element == null) {
            output('This HTML without <meta name="' + name + '"> tag');
        }
    });
}
