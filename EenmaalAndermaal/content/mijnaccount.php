<?php
$hasToValidate = false;

$telnr2 ="";
try{
  $sql = "SELECT gebruikersnaam, voornaam, achternaam, adresregel, postcode, plaatsnaam, land, kvkNummer, geboorteDag, mailbox, gebruikersStatus_omschrijving, gebruikersStatus FROM Gebruiker inner join Gebruikersstatus on Gebruiker.gebruikersStatus = Gebruikersstatus.gebruikersStatus_id WHERE gebruikersnaam = :id";
  $query = $dbh->prepare($sql);
  if(!$query) {
    echo "oops error";
    exit();
  }
  else {
    $query->execute(array(':id' => $_SESSION['username']));
    $data = $query->fetchAll(PDO::FETCH_BOTH);
  }
  //var_dump($data);
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
      case 'gebruikersStatus':
      $_SESSION['userstate'] = $value;
      $statusGebruiker = $value;
      break;
      case 'gebruikersStatus_omschrijving':
      $status = $value;
      break;
      default:
      break;
    }
  }
}
try{
  $sql2 = "SELECT volgnr, Telefoon FROM Gebruikerstelefoon WHERE gebruikersnaam = :id ORDER BY volgnr ASC";
  $query2 = $dbh->prepare($sql2);
  if(!$query2) {
    echo "oops error 2";
  }
  else {
    $query2->execute(array(':id' => $username));
    $data2 = $query2->fetchAll(PDO::FETCH_BOTH);
  }
  // var_dump($data2);
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}

$countTell = count($data2) > 1;
if ($countTell) {
  $tel1 = $data2[0];
  $telnr = $tel1['Telefoon'];
  $tel1Volgnr = $tel1['volgnr'];
  $tel2 = $data2[1];
  $telnr2 = $tel2['Telefoon'];
  $tel2Volgnr = $tel2['volgnr'];
}
else {
  $tel1 = $data2[0];
  $telnr = $tel1['Telefoon'];
  $tel1Volgnr = $tel1['volgnr'];
}
if($_SESSION['userstate'] != 3){
  try{
    $userstateQuery = "SELECT Verkoper.valid, Email_validatie.code, Email_validatie.valid_until FROM Verkoper inner join Email_validatie on Verkoper.gebruiker = Email_validatie.gebruikersnaam WHERE controleOptie = 'Post' and valid = 0 and gebruiker = ?";
    $userstateStmt = $dbh->prepare($userstateQuery);
    $userstateStmt->execute(array($_SESSION['username']));
    if($userstateStmt->rowCount()!=0){
      $userstate = $userstateStmt->fetchAll();
      foreach ($userstate as $states) {
        $einddatumValid = $states['valid_until'];
        $validateCode = $states['code'];
        $hasToValidate = true;
      }
    }else{
      $hasToValidate = false;
    }
  }catch (PDOException $e) {
    echo "Fout met de database: {$e->getMessage()} ";
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
      <p>Telefoonnummer: <?php echo $telnr ?></p>
      <p>2e Telefoonnummer: <?php echo $telnr2 ?></p>
      <p>Kvkummer: <?php echo $kvknr; ?></p>
      <br>
      <p>Gebruikersstatus: <?=$status ?></p>
      <div class="registerLine"><!-- Line --></div>



      <?php
      if(isset($_POST['verifieren'])){
        if($validateCode == $_POST['code']){
          if($einddatumValid >= date('Y-m-d')){

            $codeGoedOfFout = 'Code komt overeen. U bent nu een verkoper.';
            $_SESSION['userstate'] = 3;
            try{
              $seller = "UPDATE Gebruiker SET gebruikersStatus = 3 where gebruikersnaam = ?";
              $queryInsert = $dbh->prepare($seller);
              $queryInsert->execute(array($_SESSION['username']));

              $seller = "UPDATE Verkoper SET Valid = 1";
              $queryInsert = $dbh->prepare($seller);
              $queryInsert->execute();

              $registrerenVerkoperSucces = true;
            }
            catch (PDOException $e) {
              echo "Fout met de database: {$e->getMessage()} ";
              $registrerenVerkoperSucces = false;
            }

          }else{
            $codeGoedOfFout = 'Code is verlopen, vraag een nieuwe aan.';
          }
        }else{
          $codeGoedOfFout = 'Code komt niet overeen. Probeer het opnieuw.';
        }
      }else{
        $codeGoedOfFout = '';
      }
      if($hasToValidate){
        ?>
        <h3>Verkoopaccount verifiëren door middel van verstuurde code</h3>
        <p>U heeft kortgeleden uw account geverifieerd als Verkoopaccount. Als laatste stap dient u nog een code in te vullen die verstuurt is met de post. Vul de code hier onder in:</p>
        <form action="" method="post">
          <div class="form-group">
            <input type="text" class="form-control" name="code" placeholder="AbCd1234" required />
          </div>
          <div class="form-group">
            <input type="submit" class="btnSubmit" name="verifieren" value="Verifieer" />
          </div>
        </form>
        <br>
        <?php
        echo $codeGoedOfFout;
      }

      if($_SESSION['userstate']!=3){
        ?>

        <form class="registerSeller" method="post" action="index.php?page=plaatsVeiling">
          <button type="submit" name="registerSeller" class="btn btnGreenery btn-block">Klik hier om te registreren als verkoper</button>
        </form>

      <?php } ?>
      <br>
      <?php if (!isset($_POST['changeInfo']))
      { ?>
        <form class="changeButton" method="post" action="">
          <button type="submit" name="changeInfo" class="btn btnGreenery btn-block">Naar gegevens aanpassen/Updaten</button>
        </form>
      <?php } ?>
    </div>
  </div>
  <div class="col-lg-2"><!-- White space --></div>

</div>


<div id="pagecontent" class="row">
  <div class="col-lg-2"><!-- White space --></div>
  <div class="col-lg-8">

    <div class="container">
      <?php
      if (isset($_POST['changeInfo'])) {
        ?>
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
          <button type="submit" name="submitInfo" class="btn btnGreenery btn-block">Gegevens aanpassen/Updaten</button>
        </form>
        <?php
      }
      ?>


      <?php
      try{
        $userAdressQuery = "SELECT titel, voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE verkopernaam = ?";
        $userAdressStmt = $dbh->prepare($userAdressQuery);
        $userAdressStmt->execute(array($_SESSION['username']));
        if($userAdressStmt->rowCount()!=0){
          echo '<br>
          <h2>Mijn veilingen</h2><div class="row contentWrapper">';
          $users = $userAdressStmt->fetchAll();
          foreach ($users as $result) {
            $voorwerpnummer = $result['voorwerpnummer'];
            echo '<div class="cardItem">
            <a href="index.php?page=veiling&id='.$result['voorwerpnummer'].'">
            <div class="card shadow-sm">
            <div class="cardImage">';


            $imagesquery = "SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = ?";
            $imagesStmt = $dbh->prepare($imagesquery);
            $imagesStmt->execute(array($voorwerpnummer));
            if($imagesStmt->rowCount()!=0){
              $images = $imagesStmt->fetchAll();
              foreach ($images as $image) {
                echo '<img class="rounded-top" src="../pics/'.$image['bestandsnaam'].'" width="100%" height="220" alt="'.$result['titel'].'">';
              }
            }else{
              echo '<img class="rounded-top" src="images/image_placeholder.jpg" width="100%" height="220" alt="'.$result['titel'].'">';
            }


            echo '</div>
            <div class="cardTitle">
            <div class="cardHeader">'.
            $result['titel'].'
            </div>
            <div class="cardPrice">';



            $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = ? ORDER BY bodbedrag ASC";
            $priceStmt = $dbh->prepare($pricequery);
            $priceStmt->execute(array($voorwerpnummer));
            if($priceStmt->rowCount()!=0){
              $prices = $priceStmt->fetchAll();
              foreach ($prices as $price) {
                echo 'Hoogste bod: &euro; '.str_replace('.', ',', $price['bodbedrag']);
              }
            }
            else{
              echo 'Nog geen bod';
            }


            echo '</div>
            <div class="cardFooter">
            Sluit '.date_format(date_create($result['looptijdeindeDag']), "d-m-Y").' om '.date('H:i.s',strtotime($result['looptijdeindeTijdstip'])).' uur
            </div>';

            echo '
            </div>
            </div>
            </a>
            </div>';

          }
          echo '</div>';
        }
      }catch (PDOException $e) {
        echo "Fout met de database: {$e->getMessage()} ";
      }
      ?>

      <?php
      try{
        $userAdressQuery = "SELECT titel, voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE voorwerpnummer IN (SELECT distinct voorwerp from Bod where gebruiker = ?)";
        $userAdressStmt = $dbh->prepare($userAdressQuery);
        $userAdressStmt->execute(array($_SESSION['username']));
        if($userAdressStmt->rowCount()!=0){
          echo '<br>
          <h2>Mijn Biedingen</h2><div class="row contentWrapper">';
          $users = $userAdressStmt->fetchAll();
          foreach ($users as $result) {
            $voorwerpnummer = $result['voorwerpnummer'];
            echo '<div class="cardItem">
            <a href="index.php?page=veiling&id='.$result['voorwerpnummer'].'">
            <div class="card shadow-sm">
            <div class="cardImage">';


            $imagesquery = "SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = ?";
            $imagesStmt = $dbh->prepare($imagesquery);
            $imagesStmt->execute(array($voorwerpnummer));
            if($imagesStmt->rowCount()!=0){
              $images = $imagesStmt->fetchAll();
              foreach ($images as $image) {
                echo '<img class="rounded-top" src="../pics/'.$image['bestandsnaam'].'" width="100%" height="220" alt="'.$result['titel'].'">';
              }
            }else{
              echo '<img class="rounded-top" src="images/image_placeholder.jpg" width="100%" height="220" alt="'.$result['titel'].'">';
            }


            echo '</div>
            <div class="cardTitle">
            <div class="cardHeader">'.
            $result['titel'].'
            </div>
            <div class="cardPrice">';



            $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = ? ORDER BY bodbedrag DESC";
            $priceStmt = $dbh->prepare($pricequery);
            $priceStmt->execute(array($voorwerpnummer));
            if($priceStmt->rowCount()!=0){
              $prices = $priceStmt->fetchAll();
              foreach ($prices as $price) {
                echo 'Hoogste bod: &euro; '.str_replace('.', ',', $price['bodbedrag']).'<br>';
              }
            }
            else{
              echo 'Nog geen bod';
            }


            $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = ? and gebruiker = ? ORDER BY bodbedrag DESC ";
            $priceStmt = $dbh->prepare($pricequery);
            $priceStmt->execute(array($voorwerpnummer, $_SESSION['username']));
            if($priceStmt->rowCount()!=0){
              $prices = $priceStmt->fetchAll();
              foreach ($prices as $price) {
                echo 'Mijn bod: &euro; '.str_replace('.', ',', $price['bodbedrag']);
              }
            }
            else{
              echo 'Nog geen bod';
            }


            echo '</div>
            <div class="cardFooter">
            Sluit '.date_format(date_create($result['looptijdeindeDag']), "d-m-Y").' om '.date('H:i.s',strtotime($result['looptijdeindeTijdstip'])).' uur
            </div>';

            echo '
            </div>
            </div>
            </a>
            </div>';

          }
          echo '</div>';
        }
      }catch (PDOException $e) {
        echo "Fout met de database: {$e->getMessage()} ";
      }

      try{
        $userAdressQuery = "SELECT titel, voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE kopernaam = ?";
        $userAdressStmt = $dbh->prepare($userAdressQuery);
        $userAdressStmt->execute(array($_SESSION['username']));
        if($userAdressStmt->rowCount()!=0){
          echo '<br>
          <h2>Mijn gewonnen veilingen</h2><div class="row contentWrapper">';
          $users = $userAdressStmt->fetchAll();
          foreach ($users as $result) {
            $voorwerpnummer = $result['voorwerpnummer'];
            echo '<div class="cardItem">
            <a href="index.php?page=veiling&id='.$result['voorwerpnummer'].'">
            <div class="card shadow-sm">
            <div class="cardImage">';


            $imagesquery = "SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = ?";
            $imagesStmt = $dbh->prepare($imagesquery);
            $imagesStmt->execute(array($voorwerpnummer));
            if($imagesStmt->rowCount()!=0){
              $images = $imagesStmt->fetchAll();
              foreach ($images as $image) {
                echo '<img class="rounded-top" src="../pics/'.$image['bestandsnaam'].'" width="100%" height="220" alt="'.$result['titel'].'">';
              }
            }else{
              echo '<img class="rounded-top" src="images/image_placeholder.jpg" width="100%" height="220" alt="'.$result['titel'].'">';
            }


            echo '</div>
            <div class="cardTitle">
            <div class="cardHeader">'.
            $result['titel'].'
            </div>
            <div class="cardPrice">';



            $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = ? ORDER BY bodbedrag DESC";
            $priceStmt = $dbh->prepare($pricequery);
            $priceStmt->execute(array($voorwerpnummer));
            if($priceStmt->rowCount()!=0){
              $prices = $priceStmt->fetchAll();
              foreach ($prices as $price) {
                echo 'Hoogste bod: &euro; '.str_replace('.', ',', $price['bodbedrag']);
              }
            }
            else{
              echo 'Nog geen bod';
            }


            echo '</div>
            <div class="cardFooter">
            Sluit '.date_format(date_create($result['looptijdeindeDag']), "d-m-Y").' om '.date('H:i.s',strtotime($result['looptijdeindeTijdstip'])).' uur
            </div>';

            echo '
            </div>
            </div>
            </a>
            </div>';

          }
          echo '</div>';
        }
      }catch (PDOException $e) {
        echo "Fout met de database: {$e->getMessage()} ";
      }

       ?>
    </div>
  </div>
  <div class="col-lg-2"><!-- White space --></div>
</div>

<?php
if (isset($_POST['submitInfo'])) {
  $address = cleanInput($_POST['address']);
  $zipcode = cleanInput($_POST['zipcode']);
  $city  = cleanInput($_POST['city']);
  $country  = cleanInput($_POST['country']);
  $telnr  = cleanInput($_POST['telnr']);
  $telnr2  = cleanInput($_POST['telnr2']);

  $validAddress = !preg_match("/^[a-zA-Z0-9]$/",$address);
  $validZipcode = !preg_match("/^[A-Z0-9]$/",$zipcode);
  $validCity = !preg_match("/^[a-zA-Z]$/",$city);
  $validCountry = !preg_match("/^[a-zA-Z]$/",$country);
  $validTelnr = !preg_match("/^[0-9]$/",$telnr);
  $validTelnr2 = !preg_match("/^[0-9]$/",$telnr2);

  $allValid = $validAddress && $validZipcode && $validCity && $validTelnr && $validTelnr2;

  if (!$allValid) {
    echo "Vul geldige waarden in";
  } else {
    try {
      $sqlUpdate = "UPDATE Gebruiker SET adresregel=?, postcode=?, plaatsnaam=?, land=? WHERE gebruikersnaam=?";
      $queryInsert = $dbh->prepare($sqlUpdate);
      $queryInsert->execute(array($address, $zipcode, $city, $country, $username));

      $sqlUpdateTellnr = "UPDATE Gebruikerstelefoon SET Telefoon=? WHERE volgnr = $tel1Volgnr";
      $queryInsertTellnr = $dbh->prepare($sqlUpdateTellnr);
      $queryInsertTellnr->execute(array($telnr));

      if (empty($tel2Volgnr)) {
        $sqlInsertTellnr2 = "INSERT INTO Gebruikerstelefoon(gebruikersnaam, Telefoon) VALUES(?,?)";
        $queryInsertTellnr2 = $dbh->prepare($sqlInsertTellnr2);
        $queryInsertTellnr2->execute(array($username, $telnr2));
      }else{
        $sqlUpdateTellnr2 = "UPDATE Gebruikerstelefoon SET Telefoon=? WHERE volgnr=$tel2Volgnr";
        $queryInsertTellnr2 = $dbh->prepare($sqlUpdateTellnr2);
        $queryInsertTellnr2->execute(array($telnr2));
      }
    } catch (PDOException $e) {
      echo "Fout met de database: {$e->getMessage()} ";
    }
  }
} else {

}






?>
