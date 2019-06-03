<?php
$_SESSION['accountRecovery'] = true;
if(isset($_POST['accountRecoveryButton1'])){
  $loginRecovery = cleanInput($_POST['usernameAccRecovery']);
  if (!empty($login)) {
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
            default:
            break;
          }
        }
      }
      $isNaN = $firstname == "NaN" &&  $lastname == "NaN" && $address == "NaN";
      if($isNaN){
        ?>
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
                    <input type="text" id="nameRecovery" class="form-control" name="nameRecovery" pattern="[A-Za-z]{1,50}" title="Uw voornaam" value="<?php echo isset($_POST['nameRecovery']) ? $_POST['nameRecovery'] : '' ?>" placeholder="Jan" required>
                  </div>
                </div>
                <!-- Lastname -->
                <div class="row form-group">
                  <label for="lastnameRecovery" class="col-lg-4 alignRight control-label">Achternaam *</label>
                  <div class="col-lg-8">
                    <input type="text" id="lastnameRecovery" class="form-control" name="lastnameRecovery" pattern="[A-Za-z ]{1,50}" title="Uw achternaam" value="<?php echo isset($_POST['lastnameRecovery']) ? $_POST['lastnameRecovery'] : '' ?>" placeholder="Harris" required>
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
                    <input type="text" id="addressRecovery" class="form-control" name="addressRecovery" pattern="[a-zA-Z0-9 ]{1,255}" title="Uw adres" value="<?php echo isset($_POST['addressRecovery']) ? $_POST['addressRecovery'] : '' ?>" placeholder="willemStraat 45" required>
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
                    <input type="text" id="cityRecovery" class="form-control" name="cityRecovery" pattern="[a-zA-Z]{1,25}" title="Plaatsnaam" value="<?php echo isset($_POST['cityRecovery']) ? $_POST['cityRecovery'] : '' ?>" placeholder="Doesburg" required>
                  </div>
                </div>
                <!-- Country -->
                <div class="row form-group">
                  <label for="countryRecovery" class="col-lg-4 alignRight control-label">Land *</label>
                  <div class="col-lg-8">
                    <input type="text" id="countryRecovery" class="form-control" name="countryRecovery" pattern="[a-zA-Z]{1,50}" title="Land" value="<?php echo isset($_POST['countryRecovery']) ? $_POST['countryRecovery'] : '' ?>" placeholder="Nederland" required>
                  </div>
                </div>
                <!-- Phonenumber -->
                <div class="row form-group">
                  <label for="telnrRecovery" class="col-lg-4 alignRight control-label">Telefoonnummer *</label>
                  <div class="col-lg-8">
                    <input type="text" id="telnrRecovery" class="form-control" name="telnrRecovery" pattern="[0-9]{1,15}" title="Telefoonnummer" value="<?php echo isset($_POST['telnrRecovery']) ? $_POST['telnrRecovery'] : '' ?>" placeholder="0612344455" required>
                  </div>
                </div>
                <!-- Phonenumber 2 -->
                <div class="row form-group">
                  <label for="telnr2Recovery" class="col-lg-4 alignRight control-label">Telefoonnummer 2</label>
                  <div class="col-lg-8">
                    <input type="text" id="telnr2Recovery" class="form-control" name="telnr2Recovery" pattern="[0-9]{1,15}" title="2e Telefoonnummer" value="<?php echo isset($_POST['telnr2Recovery']) ? $_POST['telnr2Recovery'] : '' ?>" placeholder="0314364999">
                  </div>
                </div>
                <!-- KVK number -->
                <div class="row form-group">
                  <label for="kvkNummerRecovery" class="col-lg-4 alignRight control-label">KVK nummer *</label>
                  <div class="col-lg-8">
                    <input type="text" id="kvkNummerRecovery" class="form-control" name="kvkNummerRecovery" pattern="[0-9]{1,8}" title="kvkNummer" value="<?php echo isset($_POST['kvkNummerRecovery']) ? $_POST['kvkNummerRecovery'] : '' ?>" placeholder="12345678" required>
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
                  <?php echo $username ?>
                  <div class="col-lg-8">
                  </div>
                </div>
                <!-- Email -->
                <div class="row form-group">
                  <label for="email" class="col-lg-4 control-label">Emailadres *</label>
                  <div class="col-lg-8">
                    <input type="email" class="form-control" name="email" placeholder="example@student.han.nl" required <?php if($emailSucces){echo'value="'.$email.'" readonly';}?>>
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



        <?php
        if(isset($_POST['signUp'])){

          $name = cleanInput($_POST['name']);
          $lastname = cleanInput($_POST['lastname']);
          $birthDate = cleanInput($_POST['birthdate']);
          $address = cleanInput($_POST['address']);
          $zipcode = cleanInput($_POST['zipcode']);
          $city  = cleanInput($_POST['city']);
          $country  = cleanInput($_POST['country']);
          $telnr  = cleanInput($_POST['telnr']);
          $telnr2  = cleanInput($_POST['telnr2']);
          $kvknr = cleanInput($_POST['kvkNummer']);
          $username = cleanInput($_POST['username']);
          $email = $_POST['email'];
          $password = cleanInput($_POST['password']);
          $passwordRepeat = cleanInput($_POST['passwordRepeat']);
          $securityQ = $_POST['securityQ'];
          $securityA = cleanInput($_POST['securityA']);

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
          $allValid = $validName && $validLastName && $validBirthDate && $validAddress && $validZipcode && $validCity && $validTelnr && $validTelnr2 && $validKvknr && $validUsername && $validSecurityA;

          if (!$allValid) {
            if ($validName) {
              $returntekst = $returntekst . "&Name=".$name;
            } else {
              $errorMes = $errorMes . "+unvalidName";
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

            $sql = "SELECT gebruikersnaam FROM Gebruiker WHERE gebruikersnaam = '$username'";
            $result = $dbh->query($sql);

            if (($row = $result->fetch()) > 0) {
              $errorUsername = true;
            } else {
              $hashedWW = hash('sha256', $password);
              $hashedSecurityA = hash('sha256', $securityA);
              try {
                $sqlInsert = "INSERT INTO Gebruiker(gebruikersnaam, voornaam, achternaam, adresregel, postcode, plaatsnaam, land, kvkNummer, geboorteDag, mailbox, wachtwoord, vraag, antwoordTekst, gebruikersStatus, valid) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $queryInsert = $dbh->prepare($sqlInsert);

                $queryInsert->execute(array($username, $name, $lastname, $address, $zipcode, $city, $country, $kvknr, $birthDate, $_SESSION['email'], $hashedWW, $securityQ, $hashedSecurityA, 2, 1));

                $sqlInsertTellnr = "INSERT INTO Gebruikerstelefoon(gebruikersnaam, Telefoon) VALUES(?,?)";
                $queryInsertTellnr = $dbh->prepare($sqlInsertTellnr);

                $queryInsertTellnr->execute(array($username, $telnr));

                if (!empty($telnr2)) {
                  $sqlInsertTellnr2 = "INSERT INTO Gebruikerstelefoon(gebruikersnaam, Telefoon) VALUES(?,?)";
                  $queryInsertTellnr2 = $dbh->prepare($sqlInsertTellnr2);

                  $queryInsertTellnr2->execute(array($username, $telnr2));
                }

                // Unset session var
                $_SESSION = array();

                // Destroy session
                session_destroy();

                // Header to login page
                header("Location: index.php?page=registrerenSucces");

              } catch (PDOException $e) {
                echo "Fout met de database: {$e->getMessage()} ";
              }
            }

          }
        } else {
          $errorData = false;
          $errorPassword = false;
          $errorUsername = false;
        }

      }
    } catch (PDOException $e) {
      echo "Er is iets fout gegaan met de database, "; //{$e->getMessage()} ";
    }
  }

}
?>
