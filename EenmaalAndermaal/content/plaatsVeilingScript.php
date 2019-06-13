<?php
session_start();

// Database connection
require('../connectie.php');
require('../functions/functions.php');

// Post auctionForm
if (isset($_POST['auctionSubmit'])) {
  $category = $_POST['categorie'];

  $title = cleanInput($_POST['title']);
  $validTitle = preg_match("/^[a-zA-Z0-9 ]+$/",$title);

  $description = cleanInput($_POST['description']);

  $location = cleanInput($_POST['location']);
  $validLocation = preg_match("/^[a-zA-Z ]+$/",$location);

  $days = $_POST['days'];

  $paymethod = $_POST['paymethod'];

  if ($_POST['payinstruction'] != "") {
    $payinstruction = cleanInput($_POST['payinstruction']);
    $validPayinstruction = preg_match("/^[a-zA-Z0-9 ]+$/",$payinstruction);
  } else {
    $payinstruction = "";
    $validPayinstruction = true;
  }

  $price = cleanInput(str_replace(',', '.',$_POST['price']));
  $validPrice = preg_match("/^[0-9.]+$/",$price);

  if ($_POST['sendcost'] != "") {
    $sendcost = cleanInput(str_replace(',', '.',$_POST['sendcost']));
    $validSendcost = preg_match("/^[0-9.]+$/",$sendcost);
  } else {
    $sendcost = 0;
    $validSendcost = true;
  }

  if ($_POST['sendinstruction'] != "") {
    $sendinstruction = cleanInput($_POST['sendinstruction']);
    $validSendinstruction = preg_match("/^[a-zA-Z0-9 ]+$/",$sendinstruction);
  } else {
    $sendinstruction = "";
    $validSendinstruction = true;
  }

  $_SESSION['category'] = $category;
  $_SESSION['title'] = $title;
  $_SESSION['description'] = $description;
  $_SESSION['location'] = $location;
  $_SESSION['days'] = $days;
  $_SESSION['paymethod'] = $paymethod;
  $_SESSION['payinstruction'] = $payinstruction;
  $_SESSION['price'] = $price;
  $_SESSION['sendcost'] = $sendcost;
  $_SESSION['sendinstruction'] = $sendinstruction;

  $allValid = $validTitle && $validLocation && $validPayinstruction && $validPrice && $validSendcost && $validSendinstruction;

  if (!$allValid) {
    if (!$validTitle) {
      header("Location: ../index.php?page=plaatsVeiling&error=titel");
    }
    if (!$validLocation) {
      header("Location: ../index.php?page=plaatsVeiling&error=plaats");
    }
    if (!$validPayinstruction) {
      header("Location: ../index.php?page=plaatsVeiling&error=betaalInstructie");
    }
    if (!$validPrice) {
      header("Location: ../index.php?page=plaatsVeiling&error=prijs");
    }
    if (!$validSendcost) {
      header("Location: ../index.php?page=plaatsVeiling&error=verzendkosten");
    }
    if (!$validSendinstruction) {
      header("Location: ../index.php?page=plaatsVeiling&error=verzendinstructie");
    }
  } else {
    $validFotos = true;

    // Validate foto's
    $allowed_image_extension = array("png", "jpg", "jpeg");

    for ($i = 0; $i < 4; $i++) {
      $input = "imageUpload".($i + 1);
      if ($_FILES[$input]["name"] != '') {
        // Get image file extension
        $file_extension = pathinfo($_FILES[$input]["name"], PATHINFO_EXTENSION);
        // Validate file input to check if is not empty
        if (!file_exists($_FILES[$input]["tmp_name"])) {
          $validFotos = false;
          header("Location: ../index.php?page=plaatsVeiling&error=exists");
        }  // Validate file input to check if is with valid extension
        else if (!in_array($file_extension, $allowed_image_extension)) {
          $validFotos = false;
          header("Location: ../index.php?page=plaatsVeiling&error=extention");
        }  // Validate image file size
        else if (($_FILES[$input]["size"] > 2000000)) {
          $validFotos = false;
          header("Location: ../index.php?page=plaatsVeiling&error=size");
        }
      }
    }

    // If all is good
    if ($allValid && $validFotos) {
      // Upload voorwerp
      try {
        $beginDag = date("Y-m-d");
        $beginTijdstip = date("H:i:s");

        $sqlVoorwerp = "INSERT INTO Voorwerp(titel, beschrijving, startprijs, Valuta, betalingswijzenaam, Betalingsinstructie, plaatsnaam, landnaam, looptijd, looptijdbeginDag, looptijdbeginTijdstip, Verzendkosten, Verzendinstructies, verkopernaam, veilingGesloten) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $queryInsert = $dbh->prepare($sqlVoorwerp);
        $queryInsert->execute(array($title, $description, $price, "EUR", $paymethod, $payinstruction, $location, "Nederland", $days, $beginDag, $beginTijdstip, $sendcost, $sendinstruction, $_SESSION['username'], 0));
      } catch (PDOException $e) {
        echo "Er ging iets fout met het plaatsen van de veiling";
      }

      // Get voorwerpnr.
      try {
        $sqlNummer = "SELECT voorwerpnummer FROM voorwerp WHERE titel = '$title' and looptijdbeginDag = '$beginDag' and looptijdbeginTijdstip = '$beginTijdstip'";
        $querySelect = $dbh->prepare($sqlNummer);
        $querySelect->execute();
        if ($querySelect->rowCount() != 0) {
          $results = $querySelect->fetchAll();
          foreach( $results as $result ) {
            $voorwerpnummer = $result['voorwerpnummer'];
          }
        }
      } catch (PDOException $e) {
        echo "Er ging iets fout met het ophalen van het voorwerpnummer";
      }

      // Upload voorwerp in rubriek
      try {
        $sqlRubriek = "INSERT INTO Voorwerp_in_rubriek(voorwerpnummer, RubriekOpLaagsteNiveau) VALUES(?,?)";
        $queryInsertRubriek = $dbh->prepare($sqlRubriek);
        $queryInsertRubriek->execute(array($voorwerpnummer, $category));
      } catch (PDOException $e) {
        echo "Er ging iets fout met het plaatsen van het voorwerp in de rubriek";
      }

      // Uplaod foto's
      for ($i = 0; $i < 4; $i++) {
        $input = "imageUpload".($i + 1);
        if ($_FILES[$input]["name"] != '') {
          $path = str_replace('content', 'upload', dirname(__FILE__));
          $target = $path . "/" . basename($_FILES[$input]["name"]);
          if (move_uploaded_file($_FILES[$input]["tmp_name"], $target)) {
            try {
              $sqlBestand = "INSERT INTO Bestand(bestandsnaam, Voorwerp) VALUES(?,?)";
              $queryInsert = $dbh->prepare($sqlBestand);
              $queryInsert->execute(array($_FILES[$input]["name"], $voorwerpnummer));
            } catch (PDOException $e) {
              echo "Er ging iets fout met plaatsen van de afbeelding";
            }
          }
        }
      }

      // Header to auction page
      header("Location: ../index.php?page=veiling&id=$voorwerpnummer&succes=true");

    }

  }

}
?>
