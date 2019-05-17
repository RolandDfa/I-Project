<?php
$telnr2 ="";
try{
  $sql = "SELECT gebruikersnaam, voornaam, achternaam, adresregel, postcode, plaatsnaam, land, kvkNummer, geboorteDag, mailbox FROM Gebruiker WHERE gebruikersnaam = :id";
  $query = $dbh->prepare($sql);
  if(!$query) {
    echo "oops error";
    exit();
  }
  else {
    $query->execute(array(':id' => $_SESSION['username']));
    $data = $query->fetchAll(PDO::FETCH_BOTH);
  }
  // var_dump($data);
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}
try{
  $sql2 = "SELECT Telefoon FROM Gebruikerstelefoon WHERE gebruikersnaam = :id";
  $query2 = $dbh->prepare($sql2);
  if(!$query2) {
    echo "oops error 2";
    exit();
  }
  else {
    $query2->execute(array(':id' => $_SESSION['username']));
    $data2 = $query2->fetchAll(PDO::FETCH_BOTH);
  }
  var_dump($data2);
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}
for ($i = 0; $i < sizeof($data); $i++) {
  foreach ($data[$i] as $key => $value) {
    switch ($key) {
      case 'gebruikersnaam':
      $username = $value;
      break;
      case 'voornaam':
      $firstname = $value;
      break;
      case 'achternaam':
      $lastname = $value;
      break;
      case 'adresregel':
      $address = $value;
      break;
      case 'postcode':
      $zipcode = $value;
      break;
      case 'plaatsnaam':
      $city = $value;
      break;
      case 'land':
      $country = $value;
      break;
      case 'kvkNummer':
      $kvknr = $value;
      break;
      case 'geboorteDag':
      $birthDate = $value;
      break;
      case 'mailbox':
      $email = $value;
      break;
      default:
      break;
    }
  }
}

?>

<H2>Account informatie</H2>

<div>
  <li><b>Account</b></li>
  <li><a href="index.php?page=home">Betalingen</a></li>
  <li><a href="index.php?page=home">Berichten</a></li>
</div>

<div id="pagecontent" class="row">
  <div class="col-lg-2"><!-- White space --></div>
  <div class="col-lg-8">

    <div class="container">
      <h2>Mijn gegevens</h2>
      <p>Persoons gegevens</p>
      <p>Voornaam: <?php echo $firstname; ?></p>
      <p>Achternaam: <?php echo $lastname; ?></p>
      <p>Geboortedatum: <?php echo $birthDate; ?></p>
      <p>Adres: <?php echo $address; ?></p>
      <p>Postcode: <?php echo $zipcode; ?></p>
      <p>Plaatsnaam: <?php echo $city; ?></p>
      <p>Land: <?php echo $country; ?></p><br>
      <p>Overige gegevens</p>
      <p>Gebruikersnaam: <?php echo $username ?></p>
      <p>Email: <?php echo $email; ?></p>
      <p>Kvkummer: <?php echo $kvknr; ?></p>
      <div class="registerLine"><!-- Line --></div>
      <?php if (!isset($_POST['changeInfo1']))
      { ?>
        <form class="changeButton" method="post" action="">
          <button type="submit" name="changeInfo1" class="btn btnGreenery btn-block">Gegevens aanpassen/Updaten</button>
        </form>
      <?php } ?>
    </div>
  </div>
  <div class="col-lg-2"><!-- White space --></div>
</div>

<?php
if (isset($_POST['changeInfo1'])) {
  ?>
  <div id="pagecontent" class="row">
    <div class="col-lg-2"><!-- White space --></div>
    <div class="col-lg-8">

      <div class="container">
        <form class="registerForm" method="post" action="">
          <h2>Gegevens aanpassen</h2>
          <div class="row form-group"></div>
          <p>Persoons gegevens</p>
          <p>Voornaam: <?php echo $firstname; ?></p>
          <p>Achternaam: <?php echo $lastname; ?></p>
          <p>Geboortedatum: <?php echo $birthDate; ?></p>
          <div class="row form-group">
            <label for="address" class="col-lg-4 alignRight control-label">Adres *</label>
            <div class="col-lg-8">
              <input type="text" id="address" class="form-control" name="address" pattern="[a-zA-Z0-9 ]{1,255}" title="Uw adres" value="<?php echo $address; ?>" required>
            </div>
          </div>
          <!-- Postcode -->
          <div class="row form-group">
            <label for="zipcode" class="col-lg-4 alignRight control-label">Postcode *</label>
            <div class="col-lg-8">
              <input type="text" id="zipcode" class="form-control" name="zipcode" pattern="(?=.*\d{4})(?=.*[A-Z]).{6}" title="Uw postcode zonder spatie" value="<?php echo $zipcode; ?>" required>
            </div>
          </div>
          <!-- City -->
          <div class="row form-group">
            <label for="city" class="col-lg-4 alignRight control-label">Plaatsnaam *</label>
            <div class="col-lg-8">
              <input type="text" id="city" class="form-control" name="city" pattern="[a-zA-Z]{1,25}" title="Plaatsnaam" value="<?php echo $city; ?>" required>
            </div>
          </div>
          <!-- Country -->
          <div class="row form-group">
            <label for="country" class="col-lg-4 alignRight control-label">Land *</label>
            <div class="col-lg-8">
              <input type="text" id="country" class="form-control" name="country" pattern="[a-zA-Z]{1,50}" title="Land" value="<?php echo $country; ?>" required>
            </div>
          </div>
          <!-- Phonenumber -->
          <div class="row form-group">
            <label for="telnr" class="col-lg-4 alignRight control-label">Telefoonnummer *</label>
            <div class="col-lg-8">
              <input type="text" id="telnr" class="form-control" name="telnr" pattern="[0-9]{1,15}" title="Telefoonnummer" value="<?php echo $telnr; ?>" required>
            </div>
          </div>
          <!-- Phonenumber 2 -->
          <div class="row form-group">
            <label for="telnr2" class="col-lg-4 alignRight control-label">Telefoonnummer 2</label>
            <div class="col-lg-8">
              <input type="text" id="telnr2" class="form-control" name="telnr2" pattern="[0-9]{1,15}" title="2e Telefoonnummer" value="<?php echo $telnr2;  ?>">
            </div>
          </div>
          <button type="submit" name="changeInfo" class="btn btnGreenery btn-block">Gegevens aanpassen/Updaten</button>
        </form>
      </div>
    </div>
    <div class="col-lg-2"><!-- White space --></div>
  </div>
  <div class="registerLine"><!-- Line --></div>
  <?php
}







?>
