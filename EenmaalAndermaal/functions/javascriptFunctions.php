<script type="text/javascript">
function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  var b = document.getElementsByClassName("before");
  var t = document.getElementsByClassName("progressbarText");
  var a = document.getElementsByClassName("after");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) {
    return false;
  }
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Clear progress
  if (n == '-1') {
    b[currentTab].classList.remove('progressbarFinish');
    t[currentTab].classList.remove('textFinish');
    a[currentTab].classList.remove('progressbarFinish');
  }
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("auctionSubmit").click();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, t, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  t = x[currentTab].getElementsByTagName("textarea");
  s = x[currentTab].getElementsByTagName("select");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    if (y[i].required == true) {
      // If a field is empty...
      if (y[i].value == "") {
        // add an "invalid" class to the field:
        y[i].className += " invalid";
        // and set the current valid status to false
        valid = false;
      }
    }
  }
  // A loop that checks every textarea field in the current tab:
  for (i = 0; i < t.length; i++) {
    // If a field is empty...
    if (t[i].value == "") {
      // add an "invalid" class to the field:
      t[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // A loop that checks every select field in the current tab:
  for (i = 0; i < s.length; i++) {
    // If a field is empty...
    if (s[i].value == "") {
      // add an "invalid" class to the field:
      s[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
    document.getElementsByClassName("before")[currentTab + 1].className += " progressbarFinish";
    document.getElementsByClassName("progressbarText")[currentTab + 1].className += " textFinish";
    document.getElementsByClassName("after")[currentTab + 1].className += " progressbarFinish";
  }
  return valid; // return the valid status
}

// Function image upload
function readURL(input, field) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $(field).css('background-image', 'url('+e.target.result +')');
    }
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
