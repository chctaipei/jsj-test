var test = require("./test.js");
var validate = require('./validate.js');
var { JSDOM } = require("jsdom");

var fs = require('fs');
var inputStream = fs.createReadStream('test.html');

test.read(inputStream, function(data) {
    var dom = new JSDOM(data, { includeNodeLocations: true });
    var document = dom.window.document;

    validate.checkElementAttr(document, 'img', 'alt', function(data) { console.log(data);} );
    validate.checkElementAttr(document, 'a', 'rel', function(data) { console.log(data);} );
    validate.checkElementCount(document, 'strong', 3, function(data) { console.log(data);} );
    validate.checkElementCount(document, 'h1', 1, function(data) { console.log(data);} );
    validate.checkHead(document, ['description', 'robots'], function(data) { console.log(data);} )

  });
