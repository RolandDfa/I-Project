<?php

try{
  $userAdressQuery = "SELECT voornaam, achternaam, adresregel, postcode, plaatsnaam, land, gebruikersStatus FROM Gebruiker WHERE gebruikersnaam = :id";
  $userAdressStmt = $dbh->prepare($userAdressQuery);
  $userAdressStmt->execute(array(':id' => $_SESSION['username']));
  if($userAdressStmt->rowCount()!=0){
    $users = $userAdressStmt->fetchAll();
    foreach ($users as $user) {
      $naam = $user['voornaam'].' '.$user['achternaam'];
      $adresregel = $user['adresregel'];
      $postcode = $user['postcode'].' '.$user['plaatsnaam'];
      $land = $user['land'];
      echo $user['gebruikersStatus'];
      if($user['gebruikersStatus'] == 3){
        $registrerenVerkoperSucces = true;
      }else{
        $registrerenVerkoperSucces = false;
      }
    }
  }
}catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}
?>

<div class="pageWrapper">
<?php if(!$registrerenVerkoperSucces){ ?>
  <h1>Verkoopaccount registreren</h1>
  <p>Om veilingen te kunnen plaatsen moet u uw account verifiëren.<br>Dit kan kan op 2 manieren:
    <ul>
      <li>Door middel van het invullen van uw creditcardnummer.</li>
      <li>Door middel van het versturen van een code met de post, die vervolgens op de website ingevuld moet worden.</li>
    </ul>
  </p>
  <form class="registerSellerCreditcard" method="post" action="">
    <button type="submit" name="registerSellerCreditcard" class="btn btnGreenery btn-block">Klik hier om te verifiëren door middel van creditcardnummer</button>
  </form>
  <?php
  if (isset($_POST['registerSellerCreditcard'])) {
    ?>
    <div class="container">
      <form class="registerSellerForm" method="post" action="">
        <div class="row form-group"></div>
        <div class="row form-group">
          <label for="bank" class="col-lg-4 alignRight control-label">Bank *</label>
          <div class="col-lg-8">
            <input type="text" id="bank" class="form-control" name="bank"  title="Uw bank" value="" placeholder="bijv. Rabobank" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="rekeningnummer" class="col-lg-4 alignRight control-label">Rekeningnummer *</label>
          <div class="col-lg-8">
            <input type="text" id="rekeningnummer" class="form-control" name="rekeningnummer" title="Uw rekeningnummer" value="" placeholder="bijv. NL12BANK0123456789" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="creditcardnummer" class="col-lg-4 alignRight control-label">Creditcardnummer *</label>
          <div class="col-lg-8">
            <input type="text" id="creditcardnummer" class="form-control" name="creditcardnummer"  title="Creditcardnummer" value="" placeholder="bijv. 1234-5678-8765-4321" required>
          </div>
        </div>
        <button type="submit" name="submitSellerCreditcard" class="btn btnGreenery btn-block">Verifiëren</button>
      </form>
    </div>
  </div>
  <?php
}
?>

<form class="registerSellerPost" method="post" action="">
  <button type="submit" name="registerSellerPost" class="btn btnGreenery btn-block">Klik hier om te verifiëren door middel van post</button>
</form>
<?php
if (isset($_POST['registerSellerPost'])) {
  ?>
  <div class="container">
    <form class="registerSellerForm" method="post" action="">
      <?=$naam.'<br>'.$adresregel.'<br>'.$postcode.'<br>'.$land ?>
      <div class="row form-group">
        <label for="bank" class="col-lg-4 alignRight control-label">Bank *</label>
        <div class="col-lg-8">
          <input type="text" id="bank" class="form-control" name="bank"  title="Uw bank" value="" placeholder="bijv. Rabobank" required>
        </div>
      </div>
      <div class="row form-group">
        <label for="rekeningnummer" class="col-lg-4 alignRight control-label">Rekeningnummer *</label>
        <div class="col-lg-8">
          <input type="text" id="rekeningnummer" class="form-control" name="rekeningnummer"  title="Uw rekeningnummer" value="" placeholder="bijv. NL12BANK0123456789" required>
        </div>
      </div>
      <button type="submit" name="submitSellerPost" class="btn btnGreenery btn-block">Klik hier om de code te verzende naar het bovenstaande adres.</button>
    </form>
  </div>
<?php  }} ?>
</div>

<?php

if(isset($_POST['submitSellerCreditcard'])){
  try{
    $seller = "INSERT INTO Verkoper (gebruiker, bank, bankrekening, controleOptie, creditcard, valid) VALUES (?,?,?,?,?,?)";
    $queryInsert = $dbh->prepare($seller);
    $queryInsert->execute(array($_SESSION['username'], cleanInput($_POST['bank']), cleanInput($_POST['rekeningnummer']), 'Creditcard', cleanInput($_POST['creditcardnummer']), 1));

    $seller = "UPDATE Gebruiker set gebruikersStatus = 3 where gebruikersnaam = ? and valid = 1";
    $queryInsert = $dbh->prepare($seller);
    $queryInsert->execute(array($_SESSION['username']));
    $_SESSION["userstate"] = 3;
    $registrerenVerkoperSucces = true;
  }
  catch (PDOException $e) {
    echo "Fout met de database: {$e->getMessage()} ";
    $registrerenVerkoperSucces = false;
  }
}

if(isset($_POST['submitSellerPost'])){
  try{
    $seller = "INSERT INTO Verkoper (gebruiker, bank, bankrekening, controleOptie, valid) VALUES (?,?,?,?,?)";
    $queryInsert = $dbh->prepare($seller);
    $queryInsert->execute(array($_SESSION['username'], cleanInput($_POST['bank']), cleanInput($_POST['rekeningnummer']), 'Post', 0));

    $seller = "INSERT INTO Email_validatie VALUES (?,?,?)";
    $queryInsert = $dbh->prepare($seller);
    $queryInsert->execute(array($_SESSION['username'], generateRandomString(8), date('Y-m-d', strtotime("+7 day"))));
    $registrerenVerkoperSucces = true;
  }
  catch (PDOException $e) {
    echo "Fout met de database: {$e->getMessage()} ";
    $registrerenVerkoperSucces = false;
  }
}
?>
