function checkFormBeforePrinting () {
  var requiredElements = document.querySelectorAll("[required]");
  var passed = true;
  for (i = 0; i < requiredElements.length; i++) {
    if(requiredElements[i].value == "") {
      passed = false;
      requiredElements[i].style.backgroundColor = "#ff9999";
    }
    else {
      requiredElements[i].style.backgroundColor = "#ffffff";
    }
  }
  if(passed) {
    window.print();
  }
}