<?php
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
var_dump($data);

?>

<H2>Account informatie</H2>

<div>
  <li><b>Account</b></li>
  <li><a href="index.php?page=home">Betalingen</a></li>
  <li><a href="index.php?page=home">Berichten</a></li>
</div>

<div>
