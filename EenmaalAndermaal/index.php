<?php
session_start();

// Database connection
require('connectie.php');
require('functions/functions.php');

// Get page
if (!empty($_GET['page'])) {
	$page = $_GET['page'];
} else {
	header("Location: index.php?page=home");
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

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/style.css">

	<!-- Icon -->
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
				<?php
				switch($page) {
					case 'home':
					require('content/home.php');
					break;
					case 'inloggen':
					require('content/inloggen.php');
					break;
					case 'registreren':
					require('content/registreren.php');
					break;
					case 'registrerenSucces':
					require('content/registrerenSucces.php');
					break;
					case 'overzicht':
					require('content/overzicht.php');
					break;
					case 'plaatsVeiling':
					if (!isset($_SESSION["username"])) {
						require('content/inloggen.php');
					} else {
						if ($_SESSION["userstate"] > 2) {
							require('content/plaatsVeiling.php');
						} else {
							require('content/registrerenVerkoper.php');
						}
					}
					break;
					case 'veiling':
					require('content/veiling.php');
					break;
					case 'gebruikersvoorwaarden':
					require('content/gebruikersvoorwaarden.php');
					break;
					case 'privacybeleid':
					require('content/privacybeleid.php');
					break;
					case 'mijnaccount':
					if (!isset($_SESSION["username"])) {
						require('content/inloggen.php');
					} else {
						require('content/mijnaccount.php');
					}
					break;
					case 'wachtwoordVergeten':
					require('content/wachtwoordVergeten.php');
					break;
					case 'wachtwoordAanpassen':
					if (!isset($_SESSION["username"])) {
						require('content/inloggen.php');
					} else {
						require('content/wachtwoordAanpassen.php');
					}
					break;
					default:
					require('content/home.php');
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
