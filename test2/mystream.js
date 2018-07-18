exports.read = function(inputStream, func) {
  var data='';
  inputStream.setEncoding('utf8');
  inputStream.on('data', function(chunk) {
      data+=chunk;
  });

  inputStream.on('end', function() {
      func(data);
      // console.log(data);
  });
}

exports.write = function(outputStream, data) {
  outputStream.write(data);
}
