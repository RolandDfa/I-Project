<?php
// Database connection
// require('connectie.php');

//Get page
// if (!empty($_GET['page'])) {
// 	$page = $_GET['page'];
// 	//If not logged in only home and contact are available
// 	if ($_SESSION['rechten'] == '') {
// 		if ($page == 'home' || $page == 'contact') {
// 			//Nothing
// 		} else {
// 			header("Location: index.php?page=home");
// 		}
// 	}
// } else {
// 	header("Location: index.php?page=home");
// }
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Bootstrap link -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	  <!-- CSS -->
	  <link rel="stylesheet" type="text/css" href="css/style.css">

		<title>EenmaalAndermaal</title>
	</head>
	<body>

		<!-- Main menu -->
		<nav id="mainNav" class="navbar navbar-expand-lg navbar-dark bg-dark shadow" style="padding-bottom: 0px;">
			<?php
			require('menu/menu.php');
			?>
		</nav>

		<div class="row" style="margin-right: 0px; margin-left: 0px;">
			<!-- Side menu -->
			<nav id="navLeft" class="navbar-light col-lg-2 col-md-2 col-sm-12">
				<?php
				require('menu/sidemenu.php');
				?>
			</nav>

			<!-- Page content -->
			<div id="pagecontent" class="col-lg-10 col-md-10">

			</div>
		</div>

	</body>
</html>
