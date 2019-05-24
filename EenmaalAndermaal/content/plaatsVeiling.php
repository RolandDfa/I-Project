<!-- progressbar -->
<ul id="progressbar">
  <li class="active"><div class="before progressbarFinish"><i class="fas fa-list-alt"></i></div><p class="progressbarText textFinish">Categorie kiezen</p><div class="after progressbarFinish"></div></li>
  <li><div class="before"><i class="fas fa-comment-alt"></i></div><p class="progressbarText">Artikelbeschrijving</p><div class="after"></div></li>
  <li><div class="before"><i class="fas fa-images"></i></div><p class="progressbarText">Foto's uploaden</p><div class="after"></div></li>
  <li><div class="before"><i class="fas fa-clipboard-check"></i></div><p class="progressbarText">Controleren</p><div class="after"></div></li>
  <li style="display: none;"><div class="before"><i class="fas fa-clipboard-check"></i></div><p class="progressbarText"></p><div class="after"></div></li>
</ul>

<!-- MultiStep Form -->
<form id="regForm" action="">
  <div class="tab">
    <h2><b>Categorie kiezen</b></h2>
    <div class="row">
      <div class="col-lg-6">
        <!-- Categorie -->
        <div class="form-group">
          <label for="categorie"><h4><b>Categorie</b></h4></label>
          <p><select id="categorie" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="categorie" required>
            <option value="">- - -</option>
            <?php
            // Get the headings from the database
            try {
              $data = $dbh->query("SELECT rubrieknaam FROM Rubriek WHERE parent = -1 ORDER BY rubrieknaam asc");
              while ($row = $data->fetch()) {
                echo '<option value="'.$row['rubrieknaam'].'">'.$row['rubrieknaam'].'</option>';
              }
            } catch (PDOException $e) {
              echo "Kan rubrieken niet laden".$e->getMessage();
            }
            ?>
          </select></p>
        </div>
      </div>
      <div class="col-lg-6"></div>
    </div>
  </div>
  <div class="tab">
    <h2><b>Titel en beschrijving</b></h2>
    <div class="row">
      <div class="col-lg-6">
        <!-- Titel -->
        <div class="form-group">
          <label for="title"><h4><b>Titel</b></h4></label>
          <p><input type="text" id="title" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="title" placeholder="Titel van de veiling" required></p>
        </div>
        <!-- Beschrijving -->
        <div class="form-group">
          <label for="discription"><h4><b>Beschrijving</b></h4></label>
          <p><textarea id="discription" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="discription" rows="8" cols="80" placeholder="Beschrijving van het product"></textarea></p>
        </div>
        <!-- Looptijd -->
        <div class="form-group">
          <label for="days"><h4><b>Looptijd</b></h4></label>
          <p><select id="days" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="days" required>
            <option value="">- - -</option>
            <option value="1">1</option>
            <option value="3">3</option>
            <option value="5">5</option>
            <option value="7">7</option>
            <option value="10">10</option>
          </select></p>
        </div>
      </div>
      <div class="col-lg-6">
        <!-- Betalingswijze -->
        <div class="form-group">
          <label for="paymethod"><h4><b>Betalingswijze</b></h4></label>
          <p><select id="paymethod" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="paymethod" required>
            <option value="">- - -</option>
            <option value="Back/Giro">Back/Giro</option>
            <option value="Contant">Contant</option>
            <option value="Anders">Anders</option>
          </select></p>
        </div>
        <!-- Betalingsinstructie -->
        <div id="anders" class="form-group">
          <label for="payinstruction"><h4><b>Betalingsinstructie</b></h4></label>
          <p><input type="text" id="payinstruction" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="payinstruction" placeholder=""></p>
        </div>
        <!-- Start prijs -->
        <div class="form-group">
          <label for="price"><h4><b>Start prijs</b></h4></label>
          <p><input type="number" id="price" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="price" placeholder="€" required></p>
        </div>
        <!-- Verzendkosten -->
        <div class="form-group">
          <label for="sendcost"><h4><b>Verzendkosten</b></h4></label>
          <p><input type="number" id="sendcost" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="sendcost" placeholder="€" required></p>
        </div>
      </div>
    </div>
  </div>
  <div class="tab">
    <h2><b>Foto's uploaden</b></h2>
    <div class="avatar-upload">
        <div class="avatar-edit">
            <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
            <label for="imageUpload"></label>
        </div>
        <div class="avatar-preview">
            <div id="imagePreview">
            </div>
        </div>
    </div>
  </div>
  <div class="tab">
    <h2><b>Controleren</b></h2>
    <p><input placeholder="Username..." oninput="this.className = ''" name="uname"></p>
    <p><input placeholder="Password..." oninput="this.className = ''" name="pword" type="password"></p>
  </div>
  <div style="overflow:auto;">
    <div style="float:right;">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Vorige</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Volgende</button>
    </div>
  </div>
  <div style="text-align:center;margin-top:40px;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
  </div>
</form>
<!-- /.MultiStep Form -->

<script type="text/javascript">
// Multi-Step Form
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the crurrent tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Plaatsen";
  } else {
    document.getElementById("nextBtn").innerHTML = "Volgende";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

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
    document.getElementById("regForm").submit();
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

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}

$('#paymethod').on('change', function() {
  if(this.value == "Anders") {
    $('#anders').show();
    $("#payinstruction").prop('required',true);
  } else {
    $('#anders').hide();
    $("#payinstruction").prop('required',false);
  }
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#imageUpload").change(function() {
    readURL(this);
});
</script>
