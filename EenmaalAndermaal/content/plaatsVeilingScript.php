<?php
session_start();

// Database connection
require('../connectie.php');
require('../functions/functions.php');

// Post auctionForm
if (isset($_POST['auctionSubmit'])) {
  $category = $_POST['categorie'];
  $title = cleanInput($_POST['title']);
  $description = cleanInput($_POST['description']);
  $location = cleanInput($_POST['location']);
  $days = $_POST['days'];
  $paymethod = $_POST['paymethod'];
  if (isset($_POST['payinstruction'])) {
    $payinstruction = cleanInput($_POST['payinstruction']);
  } else {
    $payinstruction = "";
  }
  $price = cleanInput($_POST['sendcost']);
  if (isset($_POST['payinstruction'])) {
    $sendcost = cleanInput($_POST['sendcost']);
  } else {
    $sendcost = "";
  }
  if (isset($_POST['sendcost'])) {
    $sendcost = cleanInput($_POST['sendcost']);
  } else {
    $sendcost = "";
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

  $validTitle = preg_match("/^[a-zA-Z0-9\s]+$/",$title);
  $validLocation = preg_match("/^[a-zA-Z\s]+$/",$location);
  if ($payinstruction != '') {
    $validPayinstruction = preg_match("/^[a-zA-Z0-9\s]+$/",$payinstruction);
  } else {
    $validPayinstruction = true;
  }
  $validPrice = preg_match("/^[0-9]+$/",$price);
  if ($sendcost != '') {
    $validSendcost = preg_match("/^[0-9]+$/",$sendcost);
  } else {
    $validSendcost = true;
  }
  if ($sendinstruction != '') {
    $validSendinstruction = preg_match("/^[a-zA-Z0-9\s]+$/",$sendinstruction);
  } else {
    $validSendinstruction = true;
  }
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
        $beginTijdstip = date("h:i:s");

        $sqlBestand = "INSERT INTO Voorwerp(titel, beschrijving, startprijs, betalingswijzenaam, Betalingsinstructie, plaatsnaam, landnaam, looptijd, looptijdbeginDag, looptijdbeginTijdstip, Verzendkosten, Verzendinstructies, verkopernaam, veilingGesloten) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $queryInsert = $dbh->prepare($sqlBestand);
        $queryInsert->execute(array($title, $description, $price, $paymethod, $payinstruction, $location, "Nederland", $days, $beginDag, $beginTijdstip, $sendcost, $sendinstruction, $_SESSION['username'], 0));
      } catch (PDOException $e) {
        echo "Fout met de database: {$e->getMessage()} ";
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
        echo "Fout met de database: {$e->getMessage()} ";
      }

      // Upload voorwerp in rubriek
      try {
        $sqlRubriek = "INSERT INTO Voorwerp_in_rubriek(voorwerpnummer, RubriekOpLaagsteNiveau) VALUES(?,?)";
        $queryInsertRubriek = $dbh->prepare($sqlRubriek);
        $queryInsertRubriek->execute(array($voorwerpnummer, $category));
      } catch (PDOException $e) {
        echo "Fout met de database: {$e->getMessage()} ";
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
              echo "Fout met de database: {$e->getMessage()} ";
            }
          }
        }
      }

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

      // Header to auction page
      header("Location: ../index.php?page=veiling&id=$voorwerpnummer");

    }

  }

}
?>
