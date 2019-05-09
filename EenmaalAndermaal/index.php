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

	  <!-- CSS -->
	  <link rel="stylesheet" type="text/css" href="css/style.css">

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
	    &copy; 2019 IConcepts
		</footer>

	</body>
</html>
