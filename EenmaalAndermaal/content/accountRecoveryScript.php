<?php
$_SESSION['accountRecovery'] = true;
if(isset($_POST['accountRecoveryButton1'])){
  $loginRecovery = cleanInput($_POST['usernameAccRecovery']);
  if (!empty($login)) {
    try{
      $sql = "SELECT gebruikersnaam, voornaam, achternaam, adresregel, postcode, plaatsnaam, land, kvkNummer, geboorteDag, mailbox, wachtwoord, antwoordTekst FROM Gebruiker inner join Gebruikersstatus WHERE gebruikersnaam = :id";
      $query = $dbh->prepare($sql);
      if(!$query) {
        echo "Error met het uitvoeren van een actie.";
        exit();
      }
      else {
        $query->execute(array(':id' => $loginRecovery));
        $data = $query->fetchAll(PDO::FETCH_BOTH);
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
      $isNaN = $firstname == "NaN" &&  $lastname == "NaN" && $address == "NaN";
      if($isNaN){





      }
    } catch (PDOException $e) {
      echo "Fout met de database: {$e->getMessage()} ";
    }
  }

}
?>
