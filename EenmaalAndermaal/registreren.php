<?php
  require blabla;
  use PHPmailer/PHPmailer/PHPMailer;
?>

<!DOCTYPE html>
<html lang="nl">
<body>

  <?php
    if(!$_SESSION['verifySucces']){
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
    }
    else {
    ?>
    <div>
       <form class="form" method="post" action="">
           <h2>Account aanmaken</h2>


           <label for="name">Voornaam:*</label><br>
           <input type="text" name="name" placeholder="Jan" value="<? $name ?>" id="name"><br>
           <label for="lastname">Achternaam:*</label><br>
           <input type="text" name="lastname" placeholder="Hender" value="<? $name ?>" id="lastname"><br>
           <label for="birthdate">Geboortedatum:*</label><br>
           <input type="date" name="birthdate" value="<? $name ?>" id="birthdate"><br>
           <label for="adress">Adres:*</label><br>
           <input type="text" name="adress" placeholder="willemStraat 45" value="<? $lastname ?>" id="adress"><br>
           <label for="zipcode">Postcode:*</label><br>
           <input type="text" name="zipcode" placeholder="7007HS" value="<? $zipcode ?>" id="zipcode"><br>
           <label for="city">Plaast:*</label><br>
           <input type="text" name="city" placeholder="Doesburg" value="<? $city ?>" id="city"><br>
           <label for="country">Land:*</label><br>
           <input type="text" name="country" placeholder="Nederland" value="<? $country ?>" id="country"><br>
           <label for="telnr">Telefoonnummer:*</label><br>
           <input type="text" name="telnr" placeholder="0612344455" value="<? $telnr ?>" id="telnr"><br>
           <label for="telnr2">Telefoonnummer 2:</label><br>
           <input type="text" name="telnr2" placeholder="0314364999" value="<? $telnr2 ?>" id="telnr2"><br>
           <label for="kvkNummer">KVK Nummer:</label><br>
           <input type="text" name="kvkNummer" placeholder="12345678" value="<? $kvknr ?>" id="kvkNummer"><br>
           <label for="username">Gebruikersnaam:*</label><br>
           <input type="text" name="username" value="<? $username ?>" id="username"><br>
           <label for="password">Password:*</label>
           <input type="password" name="password" id="password"><br>
           <label for="passwordRepeat">Repeat your password:*</label>
           <input type="password" name="passwordRepeat" id="passwordRepeat"><br>
           <label for="securityQ">Geheime vraag:*</label>
                        <select id="securityQ">
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
           <input type="text" name="securityA" value="<? $securityA ?>" id="securityA"><br>
           <input type="submit" name="signUp" value="signUp" id="signUpButton">
       </form>
   </div>
   <?php
    }
?>
</body>
</html>
<?php
function cleanInput($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$_SESSION['verifySucces'] = false;

if(isset($_POST['sendCode'])){

  $email = $_POST['email'];
  $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
  $errorMes="";
  $returntext="";

    if (empty($email)){
      echo("Vul het veld in.");
    }
    elseif(!$validEmail) {
      echo("$email is geen emailadres.");
    }
    elseif($validEmail) {
      include_once "PHPmailer/PHPMailer.php";
      $mail = new PHPMailer();
      $mail -> setForm(address: 'info@EenmaalAndermaal.nl');
      $mail ->addAddress($email, $name);
      $mail ->Subject = "Verivieër uw email";
      $mail ->isHTML(isHtml: true);
      $mail ->Body = "
      bla bla
      ";
      if($mail->send()){
        echo("Er is een verificatie mail naar $email gestuurd, Vul de code in.");
      }
      else{
      echo("u fucked up.");
    }
    }
}

if(isset($_POST['verifyCode'])){

  $codeInput = $_POST['code'];
  $validVerify = "$codeInput == $code";

    if(empty($codeInput)){
      echo("Fill in the field");
    }
    elseif (!$validVerify) {
          echo("$codeInput is not a valid code");
    }
    elseif ($validVerify) {
          echo("Your email [$email] has been verified.");
          $_SESSION['verifySucces'] = true;
    }
}

if(isset($_POST['signUp'])){

    require 'connectie.php';

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
        echo("Vul alle velden met * volledig in");
    }
    else if (!$allValid) {
        if ($validName) {
            $returntekst = $returntekst . "&Name=".$name;
        } else {
            $errorMes = $errorMes . "+unvalidName";
        }

        if ($validLastName) {
            $returntekst = $returntekst . "&LastName=".$Lastname;
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
        header("location: ../registreren.php?error=" . $errorMes . $returntekst);
        exit();
    }
    else if($password !== $passwordRepeat){
        echo("wachtwoord en herhaalwachtwoord komen niet overeen.");
        exit();
    }
    else{
        $sql = "SELECT gebruikersnaam FROM Gebruikers WHERE gebruikersnaam = :id";
        $query = $dbh->prepare($sql);
        if(!$query) {
            header("location: ../registreren.php?error=" . "DB Fucked up");
            exit();
        }
        else {
            $query->execute(array(':id' => $username));
            $data = $query->fetchAll(PDO::FETCH_BOTH);
            $usernameExist = count($data) > 0;

            if ($usernameExist) {
                echo("Deze gebruikersnaam is al in gebruik, kies een andere.");
                header("location: ../registreren.php?error=" . "usernameExist" . "&Email=".$email . "&Username=".$username);
                exit();
            } else {
                $sqlInsert = "INSERT INTO Gebruikers(gebruikersnaam,voornaam,achternaam,adresregel,postcode,plaatsnaam,land,kvkNummer,geboorteDag,mailBox,wachtwoord,vraag,antwoordTekst) VALUES(:username,:name,:lastname,:adress,:zipcode,:city,:country,:kvk,:birthDate,:email,:password,:securityQ,:securityA)";
                $queryInsert = $dbh->prepare($sqlInsert);
                if (!$query) {
                    header("location: ../registreren.php?error=" . "DB error");
                    exit();
                } else {
                    $hashedWW = password_hash($password, PASSWORD_DEFAULT);

                    $query->execute(array(':username' => $username,':name' => $name,':lastname' => $lastname,':adress' => $adress,':zipcode' => $zipcode,':city' => $city,':country' => $country,':kvk' => $kvknr,':birthDate' => $birthDate,':email' => $email,
                    ':password' => $hashedWW,':securityQ' => $securityQ ,':securityA' => $securityA));


                    header("Location: ../index.php?signup=success");
                    exit();
                }
            }
          }
    }


}
else {
    header("Locaction: ../index.php");
    exit();
}
