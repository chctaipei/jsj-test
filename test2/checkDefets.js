"use strict";

var mystream = require("./mystream.js");
var validate = require('./validate.js');
var { JSDOM } = require("jsdom");

exports.checkDefets = function(input, output, rules) { 
   var execute = function(data) {
     var dom = new JSDOM(data, { includeNodeLocations: true });
     var document = dom.window.document;
     var print = function(data) {
       if (typeof output == 'object') {
           mystream.write(output, data);
       } else {
           process.stdout.write(data);
           // console.log(data);
       }
     }

     if (typeof rules.length == 'undefined') {
         rules = [rules];
     }

     rules.forEach(function(rule) {
        switch (rule.type) {
          case 'checkElementAttr':
            var notfound = validate.checkElementAttr(document, rule.tag, rule.attr);
            if (notfound > 0) {
                print("There are " + notfound + " <" + rule.tag +"> tag without "+ rule.attr + " attribute\n");
            }
            break;
          case 'checkElementCount':
            if (validate.checkElementCount(document, rule.tag, rule.count) == false) {
                print("This HTML has more than " + rule.count + " <" + rule.tag + "> tag\n");
            }
            break;
          case 'checkHead':
            if (validate.checkHeadTitle(document) == false) {
                print("This HTML without <title> tag\n");
            }

            var notFound = validate.checkHeadMeta(document, rule.meta);
            notFound.forEach(function(name) {
                print('This HTML without <meta name="' + name + '"> tag' + "\n");
            });
            break;
        }
    });
   };

   if (typeof input.read == 'function') {
       mystream.read(input, execute);
   } else if (typeof input == 'string') {
       execute(input);
   } else {
       console.log("invalid input");
   }
}
