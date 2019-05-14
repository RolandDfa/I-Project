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

// Check code
if(isset($_POST['verifyCode'])){
	$codeInput = $_POST['code'];
	if ($codeInput != $_SESSION['code']) {
		header("Location: index.php?page=registreren&error=blabla");
	} elseif ($codeInput == $_SESSION['code']) {
		$_SESSION['verifySucces'] = true;
	}
}

// Register
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
	$allValid = $validName && $validLastName && $validBirthDate && $validAddress && $validZipcode && $validCity && $validTelnr && $validTelnr2 && $validKvknr && $validUsername && $validSecurityA;




	if (!$allValid) {
		if ($validName) {
			$returntekst = $returntekst . "&Name=".$name;
			echo "$validName $validLastName $validBirthDate $validAddress $validZipcode $validCity $validCountry $validTelnr $validTelnr2 $validKvknr $validUsername $validSecurityA";
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

				$queryInsert->execute(array($username, $name, $lastname, $address, $zipcode, $city, $country, $kvknr, $birthDate, $_SESSION['email'], $hashedWW, $securityQ, $hashedSecurityA, 1, 1));

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
				header("Location: index.php?page=inloggen");

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

				<div class="container">
					<form class="registerForm" method="post" action="">
						<h2>Registreren</h2>
						<div class="row form-group"></div>

						<!-- Firstname -->
						<div class="row form-group">
							<label for="name" class="col-lg-4 alignRight control-label">Voornaam *</label>
							<div class="col-lg-8">
								<input type="text" id="name" class="form-control" name="name" pattern="[A-Za-z]{1,50}" title="Uw voornaam" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>" placeholder="Jan" required>
							</div>
						</div>
						<!-- Lastname -->
						<div class="row form-group">
							<label for="lastname" class="col-lg-4 alignRight control-label">Achternaam *</label>
							<div class="col-lg-8">
								<input type="text" id="lastname" class="form-control" name="lastname" pattern="[A-Za-z]{1,50}" title="Uw achternaam" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>" placeholder="Harris" required>
							</div>
						</div>
						<!-- BirthDate -->
						<div class="row form-group">
							<label for="birthdate" class="col-lg-4 alignRight control-label">Geboortedatum *</label>
							<div class="col-lg-8">
								<input type="date" id="birthdate" class="form-control" name="birthdate" value="<?php echo isset($_POST['birthdate']) ? $_POST['birthdate'] : '' ?>" required>
							</div>
						</div>
						<!-- Address -->
						<div class="row form-group">
							<label for="address" class="col-lg-4 alignRight control-label">Adres *</label>
							<div class="col-lg-8">
								<input type="text" id="address" class="form-control" name="address" pattern="[a-zA-Z0-9 ]{1,255}" title="Uw adres" value="<?php echo isset($_POST['address']) ? $_POST['address'] : '' ?>" placeholder="willemStraat 45" required>
							</div>
						</div>
						<!-- Postcode -->
						<div class="row form-group">
							<label for="zipcode" class="col-lg-4 alignRight control-label">Postcode *</label>
							<div class="col-lg-8">
								<input type="text" id="zipcode" class="form-control" name="zipcode" pattern="(?=.*\d{4})(?=.*[A-Z]).{6}" title="Uw postcode zonder spatie" value="<?php echo isset($_POST['zipcode']) ? $_POST['zipcode'] : '' ?>" placeholder="7007HS" required>
							</div>
						</div>
						<!-- City -->
						<div class="row form-group">
							<label for="city" class="col-lg-4 alignRight control-label">Plaatsnaam *</label>
							<div class="col-lg-8">
								<input type="text" id="city" class="form-control" name="city" pattern="[a-zA-Z]{1,25}" title="Plaatsnaam" value="<?php echo isset($_POST['city']) ? $_POST['city'] : '' ?>" placeholder="Doesburg" required>
							</div>
						</div>
						<!-- Country -->
						<div class="row form-group">
							<label for="country" class="col-lg-4 alignRight control-label">Land *</label>
							<div class="col-lg-8">
								<input type="text" id="country" class="form-control" name="country" pattern="[a-zA-Z]{1,50}" title="Land" value="<?php echo isset($_POST['country']) ? $_POST['country'] : '' ?>" placeholder="Nederland" required>
							</div>
						</div>
						<!-- Phonenumber -->
						<div class="row form-group">
							<label for="telnr" class="col-lg-4 alignRight control-label">Telefoonnummer *</label>
							<div class="col-lg-8">
								<input type="text" id="telnr" class="form-control" name="telnr" pattern="[0-9]{1,15}" title="Telefoonnummer" value="<?php echo isset($_POST['telnr']) ? $_POST['telnr'] : '' ?>" placeholder="0612344455" required>
							</div>
						</div>
						<!-- Phonenumber 2 -->
						<div class="row form-group">
							<label for="telnr2" class="col-lg-4 alignRight control-label">Telefoonnummer 2</label>
							<div class="col-lg-8">
								<input type="text" id="telnr2" class="form-control" name="telnr2" pattern="[0-9]{1,15}" title="2e Telefoonnummer" value="<?php echo isset($_POST['telnr2']) ? $_POST['telnr2'] : '' ?>" placeholder="0314364999">
							</div>
						</div>
						<!-- KVK number -->
						<div class="row form-group">
							<label for="kvkNummer" class="col-lg-4 alignRight control-label">KVK nummer *</label>
							<div class="col-lg-8">
								<input type="text" id="kvkNummer" class="form-control" name="kvkNummer" pattern="[0-9]{1,8}" title="kvkNummer" value="<?php echo isset($_POST['kvkNummer']) ? $_POST['kvkNummer'] : '' ?>" placeholder="12345678" required>
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
							<label for="username" class="col-lg-4 alignRight control-label">Gebruikersnaam *</label>
							<div class="col-lg-8">
								<input type="text" id="username" class="form-control" name="username" pattern="[a-zA-Z0-9]{1,50}" title="Kies een gebruikersnaam" required>
							</div>
						</div>
						<!-- Password -->
						<div class="row form-group">
							<label for="password" class="col-lg-4 alignRight control-label">Wachtwoord *</label>
							<div class="col-lg-8">
								<input type="password" id="password" class="form-control" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" title="vul minimaal een kleine letter, een cijfer en een hoofd letter in. het wachtwoord moet tussen 8 en 15 lang zijn."  required>
							</div>
						</div>
						<!-- Repeat password -->
						<div class="row form-group">
							<label for="passwordRepeat" class="col-lg-4 alignRight control-label">Herhaal wachtwoord *</label>
							<div class="col-lg-8">
								<input type="password" id="passwordRepeat" class="form-control" name="passwordRepeat" required>
								<div class="redText">
									<?php
									if ($errorPassword) {
										echo "Wachtwoorden komen niet overeen";
									}
									if ($errorUsername) {
										echo "Gebruikersnaam is al in gebruik";
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
							<label for="securityQ" class="col-lg-4 alignRight control-label">Veiligheidsvraag *</label>
							<div class="col-lg-8">
								<select id="securityQ" class="form-control" name="securityQ" required>
									<option value="">- - -</option>
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
							</div>
						</div>
						<!-- Answer -->
						<div class="row form-group">
							<label for="securityA" class="col-lg-4 alignRight control-label">Antwoord *</label>
							<div class="col-lg-8">
								<input type="text" id="securityA" class="form-control" name="securityA" pattern="[a-zA-Z0-9]{1,255}" required>
							</div>
						</div>

						<button type="submit" name="signUp" class="btn btnGreenery btn-block">Registreren</button>
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
							<?php
							if (!isset($_SESSION["username"])) {
								echo '<li><a class="linkFooter" href="index.php?page=inloggen">Inloggen</a></li>
								<li><a class="linkFooter" href="index.php?page=registreren">Gratis registreren</a></li>';
							} else {
								echo '<li><a class="linkFooter" href="logout.php">Uitloggen</a></li>';
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

</body>
</html>
