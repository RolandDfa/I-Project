<?php
// If first time on page
if (empty($_GET['error'])) {
  $_SESSION['category'] = '';
  $_SESSION['title'] = '';
  $_SESSION['description'] = '';
  $_SESSION['location'] = '';
  $_SESSION['days'] = '';
  $_SESSION['paymethod'] = '';
  $_SESSION['payinstruction'] = '';
  $_SESSION['price'] = '';
  $_SESSION['sendcost'] = '';
  $_SESSION['sendinstruction'] = '';
}
?>

<!-- progressbar -->
<ul id="progressbar">
  <li class="active"><div class="before progressbarFinish"><i class="fas fa-list-alt"></i></div><p class="progressbarText textFinish">Categorie kiezen</p><div class="after progressbarFinish"></div></li>
  <li><div class="before"><i class="fas fa-comment-alt"></i></div><p class="progressbarText">Artikelbeschrijving</p><div class="after"></div></li>
  <li><div class="before"><i class="fas fa-images"></i></div><p class="progressbarText">Foto's uploaden</p><div class="after"></div></li>
  <li><div class="before"><i class="fas fa-clipboard-check"></i></div><p class="progressbarText">Controleren</p><div class="after"></div></li>
  <li style="display: none;"><div class="before"><i class="fas fa-clipboard-check"></i></div><p class="progressbarText"></p><div class="after"></div></li>
</ul>

<!-- MultiStep Form -->
<form id="auctionForm" method="post" action="content/plaatsVeilingScript.php" enctype="multipart/form-data">
  <div class="tab">
    <h2><b>Categorie kiezen</b></h2>
    <div class="row">
      <div class="col-lg-6">
        <!-- Categorie -->
        <div class="form-group">
          <div class="redText">
            <?php
            if (!empty($_GET['error'])) {
              if ($_GET['error'] == 'titel') {
                echo 'Titel mag alleen maar uit letters en cijfers bestaan';
              }
              if ($_GET['error'] == 'plaats') {
                echo 'Plaatsnaam mag alleen maar uit letters bestaan';
              }
              if ($_GET['error'] == 'betaalInstructie') {
                echo 'Betalingsinstructies mag alleen maar uit letter en cijfers bestaan';
              }
              if ($_GET['error'] == 'prijs') {
                echo 'Startprijs mag alleen maar uit cijfers bestaan en mag niet in de min zijn';
              }
              if ($_GET['error'] == 'verzendkosten') {
                echo 'Verzendkosten mag alleen maar uit cijfers bestaan en mag niet in de min zijn';
              }
              if ($_GET['error'] == 'verzendinstructie') {
                echo 'Verzendinstructies mag alleen maar uit letter en cijfers bestaan';
              }
              if ($_GET['error'] == 'exists') {
                echo 'Het bestand bestaat al';
              }
              if ($_GET['error'] == 'extention') {
                echo 'Het bestand is geen png, jpg of jpeg';
              }
              if ($_GET['error'] == 'size') {
                echo 'Het bestand is groter dan 2MB';
              }
            }
            ?>
          </div>
          <label for="categorie"><h4><b>Categorie</b></h4></label>
          <p><select id="categorie" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="categorie" required>
            <option value="">- - -</option>
            <?php
            // Get the categories from the database
            try {
              $data = $dbh->query("SELECT r3.rubrieknaam[rubriek], r2.rubrieknaam [subrubriek], r1.rubrieknaam [subsubrubriek], r1.rubrieknummer [rubrieknummer] from Rubriek r1 inner join Rubriek r2 on r1.parent = r2.rubrieknummer inner join Rubriek r3 on r2.parent = r3.rubrieknummer where r1.rubrieknummer not in (select parent from Rubriek where parent is not null)");
              while ($row = $data->fetch()) {
                echo '<option value="'.$row['rubrieknummer'].'"';if(!empty($_SESSION['category'])){if($_SESSION['category'] == $row['rubrieknummer']){echo ' selected';}}echo'>
                  "'.$row['subsubrubriek'].'" - "'.$row['subrubriek'].'"';
                  if($row['rubriek'] != "Root"){
                    echo ' - "'.$row['rubriek'].'"';
                  }
                echo '</option>';
              }
            } catch (PDOException $e) {
              echo "Kan rubrieken niet laden";
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
          <p><input type="text" id="title" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="title" placeholder="Titel van de veiling" minlength="4" maxlength="50" <?php if(!empty($_SESSION['title'])){echo'value="'.$_SESSION['title'].'"';}?> required></p>
        </div>
        <!-- Beschrijving -->
        <div class="form-group">
          <label for="description"><h4><b>Beschrijving</b></h4></label>
          <p><textarea id="description" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="description" rows="8" cols="80" placeholder="Beschrijving van het artikel" minlength="10" maxlength="1000"><?php if(!empty($_SESSION['description'])){echo $_SESSION['description'];}?></textarea></p>
        </div>
        <!-- Locatie -->
        <div class="form-group">
          <label for="location"><h4><b>Plaatsnaam</b></h4></label>
          <p><input type="text" id="location" class="form-control greeneryBorder col-lg-10" pattern="[a-zA-Z ]{3,25}" minlength="3" maxlength="25" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="location" placeholder="Locatie van het artikel" <?php if(!empty($_SESSION['location'])){echo'value="'.$_SESSION['location'].'"';}?> required></p>
        </div>
        <!-- Looptijd -->
        <div class="form-group">
          <label for="days"><h4><b>Looptijd</b></h4></label>
          <p><select id="days" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="days" required>
            <option value="1" <?php if(!empty($_SESSION['days'])){if($_SESSION['days'] == 1){echo 'selected';}} ?>>1 dag</option>
            <option value="3" <?php if(!empty($_SESSION['days'])){if($_SESSION['days'] == 3){echo 'selected';}} ?>>3 dagen</option>
            <option value="5" <?php if(!empty($_SESSION['days'])){if($_SESSION['days'] == 5){echo 'selected';}} ?>>5 dagen</option>
            <option value="7" <?php if(!empty($_SESSION['days'])){if($_SESSION['days'] == 7){echo 'selected';}} else {echo 'selected';} ?>>7 dagen</option>
            <option value="10" <?php if(!empty($_SESSION['days'])){if($_SESSION['days'] == 10){echo 'selected';}} ?>>10 dagen</option>
          </select></p>
        </div>
      </div>
      <div class="col-lg-6">
        <!-- Betalingswijze -->
        <div class="form-group">
          <label for="paymethod"><h4><b>Betalingswijze</b></h4></label>
          <p><select id="paymethod" class="form-control greeneryBorder col-lg-10" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="paymethod" <?php if(!empty($_SESSION['paymethod'])){echo'value="'.$_SESSION['paymethod'].'"';}?> required>
            <option value="">- - -</option>
            <option value="Bank/Giro" <?php if(!empty($_SESSION['paymethod'])){if($_SESSION['paymethod'] == "Bank/Giro"){echo 'selected';}} ?>>Bank/Giro</option>
            <option value="Contant" <?php if(!empty($_SESSION['paymethod'])){if($_SESSION['paymethod'] == "Contant"){echo 'selected';}} ?>>Contant</option>
            <option value="Anders" <?php if(!empty($_SESSION['paymethod'])){if($_SESSION['paymethod'] == "Anders"){echo 'selected';}} ?>>Anders</option>
          </select></p>
        </div>
        <!-- Betalingsinstructies -->
        <div class="form-group">
          <label for="payinstruction"><h4><b>Betalingsinstructies</b></h4></label>
          <p><input type="text" id="payinstruction" class="form-control greeneryBorder col-lg-10" pattern="[a-zA-Z0-9., ]{5,30}" minlength="5" maxlength="30" name="payinstruction" <?php if(!empty($_SESSION['payinstruction'])){echo'value="'.$_SESSION['payinstruction'].'"';}?> placeholder="Bijv. Ophalen bij verkoper"></p>
        </div>
        <!-- Startprijs -->
        <div class="form-group">
          <label for="price"><h4><b>Startprijs</b></h4></label>
          <p><input type="number" id="price" class="form-control greeneryBorder col-lg-10" step="0.01" min="0.00" max="99999.99" oninput="this.className = 'form-control greeneryBorder col-lg-10'" name="price" placeholder="€" <?php if(!empty($_SESSION['price'])){echo'value="'.$_SESSION['price'].'"';}?> required></p>
        </div>
        <!-- Verzendkosten -->
        <div class="form-group">
          <label for="sendcost"><h4><b>Verzendkosten</b></h4></label>
          <p><input type="number" id="sendcost" class="form-control greeneryBorder col-lg-10" step="0.01" min="0.00" max="999.99" name="sendcost" <?php if(!empty($_SESSION['sendcost'])){echo'value="'.$_SESSION['sendcost'].'"';}?> placeholder="€"></p>
        </div>
        <!-- Verzendinstructies -->
        <div class="form-group">
          <label for="sendinstruction"><h4><b>Verzendinstructies</b></h4></label>
          <p><input type="text" id="sendinstruction" class="form-control greeneryBorder col-lg-10" pattern="[a-zA-Z ]{5,30}" minlength="5" maxlength="30" name="sendinstruction" <?php if(!empty($_SESSION['sendinstruction'])){echo'value="'.$_SESSION['sendinstruction'].'"';}?> placeholder="Bijv. Alleen ophalen bij verkoper"></p>
        </div>
      </div>
    </div>
  </div>
  <div class="tab">
    <h2><b>Foto's uploaden</b></h2>
    <p>De eerste afbeelding is verplicht.</p>
    <div class="row">

      <div class="avatar-upload-first">
        <div class="avatar-edit">
          <input type='file' id="imageUpload1" name="imageUpload1" accept=".png, .jpg, .jpeg" required />
          <label for="imageUpload1"><i class="fas fa-upload"></i></label>
        </div>
        <div class="avatar-preview-first">
          <div id="imagePreview1">
          </div>
        </div>
      </div>
      <div class="avatar-upload">
        <div class="avatar-edit">
          <input type='file' id="imageUpload2" name="imageUpload2" accept=".png, .jpg, .jpeg" />
          <label for="imageUpload2"><i class="fas fa-upload"></i></label>
        </div>
        <div class="avatar-preview">
          <div id="imagePreview2">
          </div>
        </div>
      </div>
      <div class="avatar-upload">
        <div class="avatar-edit">
          <input type='file' id="imageUpload3" name="imageUpload3" accept=".png, .jpg, .jpeg" />
          <label for="imageUpload3"><i class="fas fa-upload"></i></label>
        </div>
        <div class="avatar-preview">
          <div id="imagePreview3">
          </div>
        </div>
      </div>
      <div class="avatar-upload">
        <div class="avatar-edit">
          <input type='file' id="imageUpload4" name="imageUpload4" accept=".png, .jpg, .jpeg" />
          <label for="imageUpload4"><i class="fas fa-upload"></i></label>
        </div>
        <div class="avatar-preview">
          <div id="imagePreview4">
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="tab">
    <h2><b>Controleren</b></h2>
    Weet u zeker dat alle de gegevens goed ingevuld zijn? Klik dan op plaatsen.
  </div>
  <div id="buttonListDiv">
    <div id="buttonList">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Vorige</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Volgende</button>
      <input type="submit" id="auctionSubmit" name="auctionSubmit" style="display: none;">
    </div>
  </div>
  <div id="bulletList">
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

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}

$("#imageUpload1").change(function() {
  readURL(this, "#imagePreview1");
});
$("#imageUpload2").change(function() {
  readURL(this, "#imagePreview2");
});
$("#imageUpload3").change(function() {
  readURL(this, "#imagePreview3");
});
$("#imageUpload4").change(function() {
  readURL(this, "#imagePreview4");
});
</script>
