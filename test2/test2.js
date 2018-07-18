var seo = require("./checkDefets.js");
var fs = require('fs');
var inputStream = fs.createReadStream('test.html');
var outputStream = fs.createWriteStream('test.output', { encoding: 'utf8'});
var rule = 
[
      {
        'type': 'checkElementAttr',
        'tag': 'img',
        'attr': 'alt'
      },
      {
        'type': 'checkElementAttr',
        'tag': 'a',
        'attr': 'ref'
      },
      {
        'type': 'checkElementCount',
        'tag': 'h1',
        'count': 1
      },
      {
        'type': 'checkElementCount',
        'tag': 'strong',
        'count': 2
      },
      {
        'type': 'checkHead',
        'meta': ['description', 'robots']
      }
];

seo.checkDefets(inputStream, outputStream, rule);
seo.checkDefets('<a ref="inputStream">', outputStream, rule);
