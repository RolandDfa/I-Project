<?php
session_start();

// Database connection
require('connectie.php');
require('functions/functions.php');

$errorMes="";
$returntekst="";

if(isset($_POST['verifyCode'])){
	$codeInput = $_POST['code'];
	if ($codeInput != $_SESSION['code']) {
		header("Location: index.php?page=registreren&error=blabla");
	} elseif ($codeInput == $_SESSION['code']) {
		$_SESSION['verifySucces'] = true;
	}
}
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

		<!-- Page content -->
		<div id="pagecontent" class="row">
			<div class="col-lg-2"><!-- White space --></div>
			<div class="col-lg-8">
      <div>
        <form class="form" method="post" action="">
          <h2>Account aanmaken</h2>
          <label for="name">Voornaam:*</label><br>
          <input type="text" name="name" placeholder="Jan" value="" id="name" required><br>
          <label for="lastname">Achternaam:*</label><br>
          <input type="text" name="lastname" placeholder="Harris" value="" id="lastname" required><br>
          <label for="birthdate">Geboortedatum:*</label><br>
          <input type="date" name="birthdate" value="" id="birthdate" required><br>
          <label for="adress">Adres:*</label><br>
          <input type="text" name="adress" placeholder="willemStraat 45" value="" id="adress" required><br>
          <label for="zipcode">Postcode:*</label><br>
          <input type="text" name="zipcode" placeholder="7007HS" value="" id="zipcode required"><br>
          <label for="city">Plaast:*</label><br>
          <input type="text" name="city" placeholder="Doesburg" value="" id="city" required><br>
          <label for="country">Land:*</label><br>
          <input type="text" name="country" placeholder="Nederland" value="" id="country" required><br>
          <label for="telnr">Telefoonnummer:*</label><br>
          <input type="text" name="telnr" placeholder="0612344455" value="" id="telnr" required><br>
          <label for="telnr2">Telefoonnummer 2:</label><br>
          <input type="text" name="telnr2" placeholder="0314364999" value="" id="telnr2"><br>
          <label for="kvkNummer">KVK Nummer:</label><br>
          <input type="text" name="kvkNummer" placeholder="12345678" value="" id="kvkNummer" required><br>
          <label for="username">Gebruikersnaam:*</label><br>
          <input type="text" name="username" value="" id="username" required><br>
          <label for="password">Password:*</label>
          <input type="password" name="password" id="password" required><br>
          <label for="passwordRepeat">Repeat your password:*</label>
          <input type="password" name="passwordRepeat" id="passwordRepeat" required><br>
          <label for="securityQ">Geheime vraag:*</label>
          <select name="securityQ" id="securityQ" required>
            <?php
            try{
              $data = $dbh->query("SELECT vraagnummer, tekstvraag FROM Vraag");
              while($row = $data->fetch()){
                echo '<option value="'.$row['vraagnummer'].'">'.$row['tekstvraag'].'</option>';
              }
            }
            catch (PDOException $e){
              echo "Kan rubrieken niet laden".$e->getMessage();
            }
            ?>
          </select>
          <label for="securityA">Andwoord:*</label><br>
          <input type="text" name="securityA" value="" id="securityA" required><br>
          <input type="submit" name="signUp" value="signUp" id="signUpButton">
        </form>
      </div>

      <?php
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
        }
        else if($password !== $passwordRepeat){
          echo("wachtwoord en herhaalwachtwoord komen niet overeen.");
          exit();
        }
        else{
          $sql = "SELECT gebruikersnaam FROM Gebruikers WHERE gebruikersnaam = $username";
          $query = $dbh->prepare($sql);
          if(!$query) {
            echo"db fucked up 666";
          }
          else {
            $data = $query->fetchAll(PDO::FETCH_BOTH);
            $usernameExist = count($data) > 0;

            if ($usernameExist) {
              echo("Deze gebruikersnaam is al in gebruik, kies een andere.");
            } else {
              $hashedWW = hash('sha256', $password);
              try{
                $sqlInsert = "INSERT INTO Gebruiker(gebruikersnaam,voornaam,achternaam,adresregel,postcode,plaatsnaam,land,kvkNummer,geboorteDag,mailbox,wachtwoord,vraag,antwoordTekst,gebruikersStatus,valid) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $queryInsert = $dbh->prepare($sqlInsert);
                if (!$queryInsert) {
                  echo"db fucked up 666";
                } else {
                  $queryInsert->execute(array($username,$name,$lastname, $adress, $zipcode, $city, $country,$kvknr,$birthDate,$_SESSION['email'], $hashedWW, $securityQ , $securityA,1,1));
                  $_SESSION['email']="";
                  $_SESSION['code']="";
                  echo "Uw bent sucsesvol geregistreerd.";
                  header("Location: ../index.php?page=home");
                  $_SESSION['verifySucces']= false;
                }
              }
              catch (PDOException $e) {
                echo "Fout met de database: {$e->getMessage()} ";
              }

            }
          }
        }
      }
      ?>
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
            <li><a class="linkFooter" href="index.php?page=home">Alle veilingen</a></li>
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

            <li><a class="linkFooter" href="<?php
            if (!isset($_SESSION["username"])) {
              echo "index.php?page=inloggen";
            } else {
              echo "logout.php";
            }
            ?>">							<?php
            if (!isset($_SESSION["username"])) {
              echo "Inloggen";
            } else {
              echo "Uitloggen";
            }
            ?></a></li>
            <li><a class="linkFooter" href="index.php?page=registreren">Gratis registreren</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-12 footerLine"><!-- Footer line --></div>
    </div>
    <div class="col-lg-2"><!-- White space --></div>
  </div>
  <div class="col-lg-12">
    <a class="linkFooter" href="">Gebruikersvoorwaarden</a> <span class="footerBreak">|</span> <a  class="linkFooter" href="">Privacybeleid</a> <span class="footerBreak">|</span> &copy; 2019 IConcepts
  </div>
</footer>

</body>
</html>
