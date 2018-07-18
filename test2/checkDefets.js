var mystream = require("./mystream.js");
var validate = require('./validate.js');
var { JSDOM } = require("jsdom");

exports.checkDefets = function(input, output, rules) { 
   var process = function(data) {
     var dom = new JSDOM(data, { includeNodeLocations: true });
     var document = dom.window.document;
     var print = function(data) {
       if (typeof output == 'object') {
           mystream.write(output, data);
       } else {
           console.log(data);
       }
     }

     rules.forEach(function(rule) {
        switch (rule.type) {
          case 'checkElementAttr':
            validate.checkElementAttr(document, rule.tag, rule.attr, print);
            break;
          case 'checkElementCount':
            validate.checkElementCount(document, rule.tag, rule.count, print);
            break;
          case 'checkHead':
            validate.checkHead(document, rule.meta, print);
            break;
        }
    });
   };

   if (typeof input == 'string') {
       process(input);
   } else {
       mystream.read(input, process);
   }
}
