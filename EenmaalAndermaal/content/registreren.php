<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';




if(!$_SESSION['verifySucces']) {

  ?>
  <div>
     <form class="form" method="post" action="">
         <h2>Signup</h2>
         <label for="email">E-mail:</label>
         <input type="email" name="email" placeholder="example@student.han.nl" id="email"><br>
         <input type="submit" name="sendCode" value="Send code" id="sendCodeButton">
         <label for="code">Verify code:</label>
         <input type="text" name="code" placeholder="E1X3A5M2P7L1E" id="code"><br>
         <input type="submit" name="verifyCode" value="Verify code" id="verifyButton">
     </form>
  </div>
  <?php
} else {
  ?>
  <div>
     <form class="form" method="post" action="">
         <h2>Account aanmaken</h2>
         <?php echo
         '<label for="name">Voornaam:*</label><br>
         <input type="text" name="name" placeholder="Jan" value="'. $name.'" id="name"><br>
         <label for="lastname">Achternaam:*</label><br>
         <input type="text" name="lastname" placeholder="Harris" value="'. $lastname.'" id="lastname"><br>
         <label for="birthdate">Geboortedatum:*</label><br>
         <input type="date" name="birthdate" value="'. $name .'" id="birthdate"><br>
         <label for="adress">Adres:*</label><br>
         <input type="text" name="adress" placeholder="willemStraat 45" value="'.$adress .'" id="adress"><br>
         <label for="zipcode">Postcode:*</label><br>
         <input type="text" name="zipcode" placeholder="7007HS" value="'. $zipcode .'" id="zipcode"><br>
         <label for="city">Plaast:*</label><br>
         <input type="text" name="city" placeholder="Doesburg" value="'. $city .'" id="city"><br>
         <label for="country">Land:*</label><br>
         <input type="text" name="country" placeholder="Nederland" value="'. $country .'" id="country"><br>
         <label for="telnr">Telefoonnummer:*</label><br>
         <input type="text" name="telnr" placeholder="0612344455" value="'. $telnr .'" id="telnr"><br>
         <label for="telnr2">Telefoonnummer 2:</label><br>
         <input type="text" name="telnr2" placeholder="0314364999" value="'. $telnr2 .'" id="telnr2"><br>
         <label for="kvkNummer">KVK Nummer:</label><br>
         <input type="text" name="kvkNummer" placeholder="12345678" value="'. $kvknr .'" id="kvkNummer"><br>
         <label for="username">Gebruikersnaam:*</label><br>
         <input type="text" name="username" value="'. $username .'" id="username"><br>
         <label for="password">Password:*</label>
         <input type="password" name="password" id="password"><br>
         <label for="passwordRepeat">Repeat your password:*</label>
         <input type="password" name="passwordRepeat" id="passwordRepeat"><br>
         <label for="securityQ">Geheime vraag:*</label>
                      <select name="securityQ" id="securityQ">
                          <option value="1">Afghanistan</option>
                          <option value="2">Bahamas</option>
                          <option value="3">Cambodia</option>
                          <option value="4">Denmark</option>
                          <option value="5">Ecuador</option>
                          <option value="6">Fiji</option>
                          <option value="7">Gabon</option>
                          <option value="8">Haiti</option>
                      </select>
         <label for="securityA">Andwoord:*</label><br>
         <input type="text" name="securityA" value="'. $securityA .'" id="securityA"><br>
         <input type="submit" name="signUp" value="signUp" id="signUpButton">'; ?>
     </form>
  </div>
  <?php
}

function cleanInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



if(isset($_POST['sendCode'])){

  $_SESSION['email'] = $_POST['email'];
  $validEmail = filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL);
  $errorMes="";
  $returntext="";

  if (empty($_SESSION['email'])) {
    echo("Vul het veld in.");
  } elseif(!$validEmail) {
    echo("$email is geen emailadres.");
  } elseif($validEmail) {

    $_SESSION['code'] = generateRandomString(8);

  $mail = new PHPMailer(true);
  try {
      //Server settings
       //$mail->SMTPDebug = 1;                                        // Enable verbose debug output
       $mail->isSMTP();                                               // Set mailer to use SMTP
       $mail->Host       = 'smtp.gmail.com';                          // Specify main and backup SMTP servers
       $mail->SMTPAuth   = true;                                      // Enable SMTP authentication
       $mail->Username   = 'info.EenmaalAndermaal41@gmail.com';       // SMTP username
       $mail->Password   = 'IprojectGroep41';                         // SMTP password
       $mail->SMTPSecure = 'tls';                                     // Enable TLS encryption, `ssl` also accepted
       $mail->Port       = 587;                                       // TCP port to connect to

       $mail->setFrom('info.EenmaalAndermaal41@gmail.com');
       $mail ->addAddress($_SESSION['email']);

       $mail->isHTML(true);
       $mail->addAttachment('images/EenmaalAndermaalLogo.png');
       $mail->Subject = '[EenmaalAndermaal] Please verify your email address.';
       $mail->Body    =
       "<b>your verify code </b><br>".$_SESSION['code']."<br><br><br><b>Van bedrijven voor bedrijven.</b>";

       //$mail->send();
       echo "Er is een verificatie mail naar ". $_SESSION['email'] ."gestuurd,".$_SESSION['code']." Vul de code in. ";
     } catch (Exception $e) {
         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
     }
  }
}

if(isset($_POST['verifyCode'])){

  $codeInput = $_POST['code'];
  if(empty($codeInput)) {
    echo("Fill in the field");
  } elseif ($codeInput != $_SESSION['code']) {
    echo("$codeInput is not a valid code");
  } elseif ($codeInput == $_SESSION['code']) {
    echo "Your email". $_SESSION['email'] ."has been verified.";
    $_SESSION['verifySucces'] = true;
  }
}

if(isset($_POST['signUp'])){

  $name = cleanInput($_POST['name']);
  $lastname = cleanInput($_POST['lastname']);
  $birthDate = cleanInput($_POST['birthdate']);
  $adress = cleanInput($_POST['adress']);
  $zipcode = cleanInput($_POST['zipcode']);
  $city  = cleanInput($_POST['city']);
  $country  = cleanInput($_POST['country']);
  $telnr  = cleanInput($_POST['telnr']);
  $telnr2  = cleanInput($_POST['telnr2']);
  $kvknr = cleanInput($_POST['kvkNummer']);
  $username = cleanInput($_POST['username']);
  $password = cleanInput($_POST['password']);
  $passwordRepeat = cleanInput($_POST['passwordRepeat']);
  $securityQ = $_POST['securityQ'];
  $securityA = cleanInput($_POST['securityA']);


  $validName = !preg_match("/^[a-zA-Z]$/",$name);
  $validLastName = !preg_match("/^[a-zA-Z]$/",$lastname);
  $validBirthDate = !preg_match("/^[0-9]$/",$birthDate);
  $validAdress = !preg_match("/^[a-zA-Z0-9]$/",$adress);
  $validZipcode = !preg_match("/^[A-Z0-9]$/",$zipcode);
  $validCity = !preg_match("/^[a-zA-Z]$/",$city);
  $validCountry = !preg_match("/^[a-zA-Z]$/",$country);
  $validTelnr = !preg_match("/^[0-9]$/",$telnr);
  $validTelnr2 = !preg_match("/^[0-9]$/",$telnr2);
  $validKvknr = !preg_match("/^[0-9]$/",$kvknr);
  $validUsername = !preg_match("/^[a-zA-Z0-9]$/",$username);
  $validSecurityA = !preg_match("/^[a-zA-Z0-9]$/",$securityA);
  $allValid = $validName && $validLastName && $validBirthDate && $validAdress && $validZipcode && $validCity && $validTelnr && $validTelnr2 && $validKvknr && $validUsername && $validSecurityA;

  /*leeg*/
  if(empty($name) || empty($lastname) || empty($birthDate) || empty($adress) || empty($zipcode) || empty($city) || empty($telnr) || empty($kvknr) || empty($username) || empty($password) || empty($passwordRepeat) || empty($securityA)){
      echo("Vul alle velden met * volledig in $securityQ ");
  }
  else if (!$allValid) {
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

      if ($validAdress) {
          $returntekst = $returntekst . "&Adress=".$adress;
      } else {
          $errorMes = $errorMes . "+unvalidAdress";
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

      echo("Foute waarden ingevoerd [$errorMes]");
      // header("location: ../registreren.php?error=" . $errorMes . $returntekst);
      // exit();
  }
  else if($password !== $passwordRepeat){
      echo("wachtwoord en herhaalwachtwoord komen niet overeen.");
      exit();
  }
  else{
      $sql = "SELECT gebruikersnaam FROM Gebruikers WHERE gebruikersnaam = $username";
      $query = $dbh->prepare($sql);
      if(!$query) {

          header("location: ../registreren.php?error=" . "DB Fucked up");
          exit();
      }
      else {
          $data = $query->fetchAll(PDO::FETCH_BOTH);
          $usernameExist = count($data) > 0;

          if ($usernameExist) {
              echo("Deze gebruikersnaam is al in gebruik, kies een andere.");
              header("location: ../registreren.php?error=" . "usernameExist" . "&Email=".$email . "&Username=".$username);
              exit();
          } else {
            $hashedWW = password_hash($password, PASSWORD_DEFAULT);
              $sqlInsert = "INSERT INTO Gebruiker(gebruikersnaam,voornaam,achternaam,adresregel,postcode,plaatsnaam,land,kvkNummer,geboorteDag,mailbox,wachtwoord,vraag,antwoordTekst) VALUES(:username,:name,:lastname,:adress,:zipcode,:city,:country,:kvk,:birthDate ,:email,:password,:securityQ ,:securityA)";
              $queryInsert = $dbh->prepare($sqlInsert);
              if (!$query) {
                  header("location: ../registreren.php?error=" . "DB error");
                  exit();
              } else {
                  $query->execute(array(':username' => $username,':name' => $name,':lastname' => $lastname,':adress' => $adress,':zipcode' => $zipcode,':city' => $city,':country' => $country,':kvk' => $kvknr,':birthDate' => $birthDate,
                  ':email' => $_SESSION['email'],':password' => $hashedWW,':securityQ' => $securityQ ,':securityA' => $securityA));


                  header("Location: ../index.php?signup=success");
                  exit();
              }

          }
        }
  }
} else {
  // header("Locaction: ../index.php");
}
