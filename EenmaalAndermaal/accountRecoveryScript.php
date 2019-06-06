<?php

session_start();

// Database connection
require('connectie.php');
require('functions/functions.php');

// Init variables
$errorMes = "";
$returntekst = "";
$errorData = "";
$errorPassword = "";
$errorUsername = "";
$firstnameRecovery = "";
$lastnameRecovery = "";
$addressRecovery = "";
if(isset($_POST['accountRecoveryButton1'])){
  $loginRecovery = cleanInput($_POST['usernameAccRecovery']);
  if (!empty($loginRecovery)) {
    try{
      $sql = "SELECT gebruikersnaam, voornaam, achternaam, adresregel, postcode FROM Gebruiker WHERE gebruikersnaam = :id";
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
            $firstnameRecovery = $value;
            break;
            case 'achternaam':
            $lastnameRecovery = $value;
            break;
            case 'adresregel':
            $addressRecovery = $value;
            break;
            case 'postcode':
            $zipcode = $value;
            break;
            default:
            break;
          }
        }
      }
    } catch (PDOException $e) {
      echo "Er is iets fout gegaan met de database."; //{$e->getMessage()} ";
    }
      $isNaN = $firstnameRecovery == "NaN" &&  $lastnameRecovery == "NaN" && $addressRecovery == "NaN";
      if($isNaN){
        ?>
        <!DOCTYPE html>
        <html>
        <head>
          <!-- Responsive -->
          <meta name="viewport" content="width=device-width, initial-scale=1">

          <!-- Bootstrap link -->
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

          <!-- Icons -->
          <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

          <!-- CSS -->
          <link rel="stylesheet" type="text/css" href="css/style.css">

          <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
          <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
          <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
          <link rel="manifest" href="images/favicon/site.webmanifest">
          <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
          <meta name="msapplication-TileColor" content="#da532c">
          <meta name="theme-color" content="#ffffff">
          <title>EenmaalAndermaal</title>
        </head>
        <body>
          <div id="body">

            <!-- Main menu -->
            <nav id="mainNav" class="navbar border-bottom border-secondary shadow-sm">
              <?php
              require('menu/menu.php');
              ?>
            </nav>

            <!-- Categorie menu -->
            <div id="categorieRow" class="row">
              <div class="col-lg-2"><!-- White space --></div>
              <div class="col-lg-8">
                <nav id="categorieNav" class="navbar border-left border-right border-bottom border-secondary">
                  <?php
                  require('menu/menuCategorien.php');
                  ?>
                </nav>
              </div>
              <div class="col-lg-2"><!-- White space --></div>
            </div>

            <div id="pagecontent" class="row">
              <div class="col-lg-2"><!-- White space --></div>
              <div class="col-lg-8">

                <div class="container">
                  <form class="registerForm" method="post" action="">
                    <h2>Herstel uw gegevens.</h2>
                    <div class="row form-group"></div>

                    <!-- Firstname -->
                    <div class="row form-group">
                      <label for="nameRecovery" class="col-lg-4 alignRight control-label">Voornaam *</label>
                      <div class="col-lg-8">
                        <input type="text" id="nameRecovery" class="form-control" name="nameRecovery" pattern="[A-Za-z]{3,50}" title="Uw voornaam" value="<?php echo isset($_POST['nameRecovery']) ? $_POST['nameRecovery'] : '' ?>" placeholder="Jan" required>
                      </div>
                    </div>
                    <!-- Lastname -->
                    <div class="row form-group">
                      <label for="lastnameRecovery" class="col-lg-4 alignRight control-label">Achternaam *</label>
                      <div class="col-lg-8">
                        <input type="text" id="lastnameRecovery" class="form-control" name="lastnameRecovery" pattern="[A-Za-z ]{3,50}" title="Uw achternaam" value="<?php echo isset($_POST['lastnameRecovery']) ? $_POST['lastnameRecovery'] : '' ?>" placeholder="Harris" required>
                      </div>
                    </div>
                    <!-- BirthDate -->
                    <div class="row form-group">
                      <label for="birthdateRecovery" class="col-lg-4 alignRight control-label">Geboortedatum *</label>
                      <div class="col-lg-8">
                        <input type="date" id="birthdateRecovery" class="form-control" name="birthdateRecovery" value="<?php echo isset($_POST['birthdateRecovery']) ? $_POST['birthdateRecoveryRecovery'] : '' ?>" required>
                      </div>
                    </div>
                    <!-- Address -->
                    <div class="row form-group">
                      <label for="addressRecovery" class="col-lg-4 alignRight control-label">Adres *</label>
                      <div class="col-lg-8">
                        <input type="text" id="addressRecovery" class="form-control" name="addressRecovery" pattern="[a-zA-Z0-9 ]{3,255}" title="Uw adres" value="<?php echo isset($_POST['addressRecovery']) ? $_POST['addressRecovery'] : '' ?>" placeholder="willemStraat 45" required>
                      </div>
                    </div>
                    <!-- Postcode -->
                    <div class="row form-group">
                      <label for="zipcodeRecovery" class="col-lg-4 alignRight control-label">Postcode *</label>
                      <div class="col-lg-8">
                        <input type="text" id="zipcodeRecovery" class="form-control" name="zipcodeRecovery" pattern="(?=.*\d{4})(?=.*[A-Z]).{6}" title="Uw postcode zonder spatie" value="<?php if(isset($_POST['zipcodeRecovery'])){ echo $_POST['zipcodeRecovery'];} else {echo $zipcode;} ?>" placeholder="7007HS" required>
                      </div>
                    </div>
                    <!-- City -->
                    <div class="row form-group">
                      <label for="cityRecovery" class="col-lg-4 alignRight control-label">Plaatsnaam *</label>
                      <div class="col-lg-8">
                        <input type="text" id="cityRecovery" class="form-control" name="cityRecovery" pattern="[a-zA-Z]{3,25}" title="Plaatsnaam" value="<?php echo isset($_POST['cityRecovery']) ? $_POST['cityRecovery'] : '' ?>" placeholder="Doesburg" required>
                      </div>
                    </div>
                    <!-- Country -->
                    <div class="row form-group">
                      <label for="countryRecovery" class="col-lg-4 alignRight control-label">Land *</label>
                      <div class="col-lg-8">
                        <input type="text" id="countryRecovery" class="form-control" name="countryRecovery" pattern="[a-zA-Z]{3,50}" title="Land" value="<?php echo isset($_POST['countryRecovery']) ? $_POST['countryRecovery'] : '' ?>" placeholder="Nederland" required>
                      </div>
                    </div>
                    <!-- Phonenumber -->
                    <div class="row form-group">
                      <label for="telnrRecovery" class="col-lg-4 alignRight control-label">Telefoonnummer *</label>
                      <div class="col-lg-8">
                        <input type="text" id="telnrRecovery" class="form-control" name="telnrRecovery" pattern="[0-9]{10,15}" title="Telefoonnummer" value="<?php echo isset($_POST['telnrRecovery']) ? $_POST['telnrRecovery'] : '' ?>" placeholder="0612344455" required>
                      </div>
                    </div>
                    <!-- Phonenumber 2 -->
                    <div class="row form-group">
                      <label for="telnr2Recovery" class="col-lg-4 alignRight control-label">Telefoonnummer 2</label>
                      <div class="col-lg-8">
                        <input type="text" id="telnr2Recovery" class="form-control" name="telnr2Recovery" pattern="[0-9]{10,15}" title="2e Telefoonnummer" value="<?php echo isset($_POST['telnr2Recovery']) ? $_POST['telnr2Recovery'] : '' ?>" placeholder="0314364999">
                      </div>
                    </div>
                    <!-- KVK number -->
                    <div class="row form-group">
                      <label for="kvkNummerRecovery" class="col-lg-4 alignRight control-label">KVK nummer *</label>
                      <div class="col-lg-8">
                        <input type="text" id="kvkNummerRecovery" class="form-control" name="kvkNummerRecovery" pattern="[0-9]{8}" title="kvkNummer" value="<?php echo isset($_POST['kvkNummerRecovery']) ? $_POST['kvkNummerRecovery'] : '' ?>" placeholder="12345678" required>
                        <div class="redText">
                          <?php
                          if ($errorData) {
                            echo "Foute waarden ingevoerd [$errorMes]";
                          }
                          ?>
                        </div>
                      </div>
                    </div>

                    <div class="row form-group">
                      <div class="col-lg-12">
                        <div class="registerLine"><!-- Line --></div>
                      </div>
                    </div>
                    <h4>Inloggegevens</h4>

                    <!-- Username -->
                    <div class="row form-group">
                      <label for="username" class="col-lg-4 control-label">Gebruikersnaam :</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" name="usernameRecovery" pattern="[a-zA-Z0-9]{3,50}" maxlength="50" value="<?php echo $username ?>" readonly>
                      </div>
                    </div>
                    <!-- Email -->
                    <div class="row form-group">
                      <label for="email" class="col-lg-4 control-label">Emailadres *</label>
                      <div class="col-lg-8">
                        <input type="email" class="form-control" name="emailRecovery" placeholder="example@student.han.nl" required>
                      </div>
                    </div>
                    <!-- Password -->
                    <div class="row form-group">
                      <label for="passwordRecovery" class="col-lg-4 alignRight control-label">Wachtwoord *</label>
                      <div class="col-lg-8">
                        <input type="password" id="passwordRecovery" class="form-control" name="passwordRecovery" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" title="vul minimaal een kleine letter, een cijfer en een hoofd letter in. het wachtwoord moet tussen 8 en 15 lang zijn."  required>
                      </div>
                    </div>
                    <!-- Repeat password -->
                    <div class="row form-group">
                      <label for="passwordRepeatRecovery" class="col-lg-4 alignRight control-label">Herhaal wachtwoord *</label>
                      <div class="col-lg-8">
                        <input type="password" id="passwordRepeatRecovery" class="form-control" name="passwordRepeatRecovery" required>
                        <div class="redText">
                          <?php
                          if ($errorPassword) {
                            echo "Wachtwoorden komen niet overeen";
                          }
                          ?>
                        </div>
                      </div>
                    </div>

                    <div class="row form-group">
                      <div class="col-lg-12">
                        <div class="registerLine"><!-- Line --></div>
                      </div>
                    </div>
                    <h4>Veiligheidsvraag</h4>

                    <!-- Security question -->
                    <div class="row form-group">
                      <label for="securityQRecovery" class="col-lg-4 alignRight control-label">Veiligheidsvraag *</label>
                      <div class="col-lg-8">
                        <select id="securityQRecovery" class="form-control" name="securityQRecovery" required>
                          <option value="">- - -</option>
                          <?php
                          try{
                            $data = $dbh->query("SELECT vraagnummer, tekstvraag FROM Vraag");
                            while($row = $data->fetch()){
                              echo '<option value="'.$row['vraagnummer'].'">'.$row['tekstvraag'].'</option>';
                            }
                          }
                          catch (PDOException $e){
                            echo "Kan rubrieken niet laden";//.$e->getMessage();
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <!-- Answer -->
                    <div class="row form-group">
                      <label for="securityARecovery" class="col-lg-4 alignRight control-label">Antwoord *</label>
                      <div class="col-lg-8">
                        <input type="text" id="securityARecovery" class="form-control" name="securityARecovery" pattern="[a-zA-Z0-9]{1,255}" required>
                      </div>
                    </div>

                    <button type="submit" name="accountRecoveryButton2" class="btn btnGreenery btn-block">Gegevens herstellen</button>
                  </form>
                </div>

              </div>
              <div class="col-lg-2"><!-- White space --></div>
            </div>

          </div>

          <!-- Footer -->
          <footer>
            <div class="row marginLeft marginRight">
              <div class="col-lg-2"><!-- White space --></div>
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-lg-4">
                    <h5>Veilingen</h5>
                    <ul>
                      <li><a class="linkFooter" href="index.php?page=overzicht">Alle veilingen</a></li>
                      <li><a class="linkFooter" href="index.php?page=home">Populair</a></li>
                      <li><a class="linkFooter" href="index.php?page=home">Zoeken</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-4">
                    <h5>Social media</h5>
                    <ul class="social-network social-circle">
                      <li><a href="" class="icoFacebook" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                      <li><a href="" class="icoTwitter" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                      <li><a href="" class="icoYoutube" title="Youtube"><i class="fab fa-youtube"></i></a></li>
                      <li><a href="" class="icoPinterest" title="Pinterest"><i class="fab fa-pinterest-p"></i></a></li>
                    </ul>
                  </div>
                  <div class="col-lg-4">
                    <h5>Mijn EenmaalAndermaal</h5>
                    <ul>
                      <?php
                      if (!isset($_SESSION["username"])) {
                        echo '<li><a class="linkFooter" href="index.php?page=inloggen">Inloggen</a></li>
                        <li><a class="linkFooter" href="index.php?page=registreren">Gratis registreren</a></li>';
                      } else {
                        echo '<li><a class="linkFooter" href="index.php?page=mijnaccount">Mijn account</a></li>
                        <li><a class="linkFooter" href="logout.php">Uitloggen</a></li>';
                      }
                      ?>
                    </ul>
                  </div>
                </div>
                <div class="col-lg-12 footerLine"><!-- Footer line --></div>
              </div>
              <div class="col-lg-2"><!-- White space --></div>
            </div>
            <div class="col-lg-12">
              <a class="linkFooter" href="index.php?page=gebruikersvoorwaarden">Gebruikersvoorwaarden</a> <span class="footerBreak">|</span> <a  class="linkFooter" href="index.php?page=privacybeleid">Privacybeleid</a> <span class="footerBreak">|</span> &copy; 2019 iConcepts
            </div>
          </footer>

          <!-- Back to top -->
          <button onclick="topFunction()" id="toTopButton" title="Go to top"><i class="fas fa-chevron-up"></i></button>

          <?php
          // Javascript functions
          require('functions/javascriptFunctions.php');
          ?>

        </body>
        </html>


        <?php
      } else {
        header("Location: index.php?page=accountRecovery&error=1");
      }
    }
  } else {
  }
    if(isset($_POST['accountRecoveryButton2'])){
      $name = cleanInput($_POST['nameRecovery']);
      $lastname = cleanInput($_POST['lastnameRecovery']);
      $birthDate = cleanInput($_POST['birthdateRecovery']);
      $address = cleanInput($_POST['addressRecovery']);
      $zipcode = cleanInput($_POST['zipcodeRecovery']);
      $city  = cleanInput($_POST['cityRecovery']);
      $country  = cleanInput($_POST['countryRecovery']);
      $telnr  = cleanInput($_POST['telnrRecovery']);
      $telnr2  = cleanInput($_POST['telnr2Recovery']);
      $kvknr = cleanInput($_POST['kvkNummerRecovery']);
      $email = $_POST['emailRecovery'];
      $username = $_POST['usernameRecovery'];
      $password = cleanInput($_POST['passwordRecovery']);
      $passwordRepeat = cleanInput($_POST['passwordRepeatRecovery']);
      $securityQ = $_POST['securityQRecovery'];
      $securityA = cleanInput($_POST['securityARecovery']);

      $validName = !preg_match("/^[a-zA-Z]$/",$name);
      $validLastName = !preg_match("/^[a-zA-Z]$/",$lastname);
      $validBirthDate = !preg_match("/^[0-9]$/",$birthDate);
      $validAddress = !preg_match("/^[a-zA-Z0-9]$/",$address);
      $validZipcode = !preg_match("/^[A-Z0-9]$/",$zipcode);
      $validCity = !preg_match("/^[a-zA-Z]$/",$city);
      $validCountry = !preg_match("/^[a-zA-Z]$/",$country);
      $validTelnr = !preg_match("/^[0-9]$/",$telnr);
      $validTelnr2 = !preg_match("/^[0-9]$/",$telnr2);
      $validKvknr = !preg_match("/^[0-9]$/",$kvknr);
      $validUsername = !preg_match("/^[a-zA-Z0-9]$/",$username);
      $validSecurityA = !preg_match("/^[a-zA-Z0-9]$/",$securityA);
      $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
      $allValid = $validEmail && $validName && $validLastName && $validBirthDate && $validAddress && $validZipcode && $validCity && $validTelnr && $validTelnr2 && $validKvknr && $validUsername && $validSecurityA;

      if (!$allValid) {
        if ($validName) {
          $returntekst = $returntekst . "&Name=".$name;
        } else {
          $errorMes = $errorMes . "+unvalidName";
        }
        if ($validEmail) {
          $returntekst = $returntekst . "&LastName=".$lastname;
        } else {
          $errorMes = $errorMes . "+unvalidLastName";
        }
        if ($validLastName) {
          $returntekst = $returntekst . "&LastName=".$lastname;
        } else {
          $errorMes = $errorMes . "+unvalidLastName";
        }

        if ($validBirthDate) {
          $returntekst = $returntekst . "&BirthDate=".$birthDate;
        } else {
          $errorMes = $errorMes . "+unvalidBirthDate";
        }

        if ($validAddress) {
          $returntekst = $returntekst . "&Address=".$address;
        } else {
          $errorMes = $errorMes . "+unvalidAddress";
        }

        if ($validZipcode) {
          $returntekst = $returntekst . "&Zipcode=".$zipcode;
        } else {
          $errorMes = $errorMes . "+unvalidZipcode";
        }

        if ($validCity) {
          $returntekst = $returntekst . "&City=".$city;
        } else {
          $errorMes = $errorMes . "+unvalidCity";
        }

        if ($validTelnr) {
          $returntekst = $returntekst . "&Telnr=".$telnr;
        } else {
          $errorMes = $errorMes . "+unvalidTelnr";
        }

        if ($validTelnr2) {
          $returntekst = $returntekst . "&Telnr2=".$telnr2;
        } else {
          $errorMes = $errorMes . "+unvalidTelnr2";
        }

        if ($validKvknr) {
          $returntekst = $returntekst . "&Kvk=".$kvknr;
        } else {
          $errorMes = $errorMes . "+unvalidKvknr";
        }

        if ($validUsername) {
          $returntekst = $returntekst ."&Username=".$username;
        } else {
          $errorMes = $errorMes . "+unvalidUsername";
        }

        if ($validSecurityA) {
          $returntekst = $returntekst ."&securityA=";
        } else {
          $errorMes = $errorMes . "+unvalidSecurityA";
        }
        $errorData = true;

      } else if ($password != $passwordRepeat){
        $errorPassword = true;
      } else {
        $hashedWW = hash('sha256', $password);
        $hashedSecurityA = hash('sha256', $securityA);
        try {
          $sqlUpdate = "UPDATE Gebruiker SET voornaam=?, achternaam=?, adresregel=?, postcode=?, plaatsnaam=?, land=?, kvkNummer=?, geboorteDag=?, mailbox=?, wachtwoord=?, vraag=?, antwoordTekst=?, gebruikersStatus=?, valid=? WHERE gebruikersnaam=?";
          $queryUpdate = $dbh->prepare($sqlUpdate);

          $queryUpdate->execute(array($name, $lastname, $address, $zipcode, $city, $country, $kvknr, $birthDate, $email, $hashedWW, $securityQ, $hashedSecurityA, 2, 1, $username));

          $sqlUpdateTellnr = "INSERT INTO Gebruikerstelefoon(gebruikersnaam, Telefoon) VALUES(?,?)";
          $queryUpdateTellnr = $dbh->prepare($sqlUpdateTellnr);

          $queryUpdateTellnr->execute(array($username, $telnr));

          if (!empty($telnr2)) {
            $sqlUpdateTellnr2 = "INSERT INTO Gebruikerstelefoon(gebruikersnaam, Telefoon) VALUES(?,?)";
            $queryUpdateTellnr2 = $dbh->prepare($sqlUpdateTellnr2);

            $queryUpdateTellnr2->execute(array($username, $telnr2));
          }

          // Unset session var
          $_SESSION = array();

          // Destroy session
          session_destroy();

          // Succsess
          header("Location: index.php?page=registrerenSucces&status=1");

        } catch (PDOException $e) {
          echo "Er is iets fout gegaan met de database" ; //: {$e->getMessage()} ";
        }
      }

    } else {
    $errorData = false;
    $errorPassword = false;
    $errorUsername = false;
    }

?>
