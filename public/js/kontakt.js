$(document).ready(function () {
  switch (getLanguage()) {
    case 'de':
      $('.encrypt-btn').html('Verschlüsseln und senden');
      break;
    case 'en':
      $('.encrypt-btn').html('encrypt and send');
      break;
    case 'es':
      // $(".encrypt-btn").html(""); TODO
      break;
  }
  $('.contact').submit(function () {
    return encrypt(this);
  });
});
// based on https://github.com/encrypt-to/secure.contactform.php
/* The MIT License (MIT)
Copyright (c) 2013 Jan Wiegelmann

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.*/
function encrypt () {
  if (window.crypto && window.crypto.getRandomValues) {
    var message = document.getElementById('message');
    if (message.value.indexOf('-----BEGIN PGP MESSAGE-----') !== -1 && message.value.indexOf('-----END PGP MESSAGE-----') !== -1) {
      // encryption done
    } else {
      var pub_key = openpgp.key.readArmored(document.getElementById('pubkey').innerHTML).keys[0];
      var plaintext = message.value;
      var ciphertext = openpgp.encryptMessage([pub_key], plaintext);
      message.value = ciphertext;
      return true;
    }
  } else {
    switch (getLanguage()) {
      case 'de':
        alert('Fehler: Ihr Browser wird nicht unterstützt. Bitte installieren Sie einen aktuellen Browser wie z.B. Mozilla Firefox.');
        break;
      case 'en':
        alert('Error: Your browser is not supported. Please install an up to date browser like Mozilla Firefox.');
        break;
      case 'es':
        // alert(""); TODO
        break;
    }
    return false;
  }
}

function getLanguage () {
  var metaData = document.getElementsByTagName('meta');
  for (var m in metaData) {
    if (metaData[m]['httpEquiv'] == 'language') {
      return metaData[m]['content'];
    }
  }
}
