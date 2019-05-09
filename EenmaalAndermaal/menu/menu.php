<?php session_start(); ?>
<div class="col-lg-2"><!-- White space --></div>
<div class="col-lg-3 col-sm-4 col-6">
  <a id="logo" href="index.php?page=home"><img src="images/EenmaalAndermaalLogo.png" width="120" height="60" alt="Logo"></a>
  <a class="menuItem" href="" data-toggle="dropdown">
    <div class="row">
      <div>
        <b>Alle veilingen</b>
      </div>
      <div class="arrow">
        <i class="fas fa-caret-right arrowDown"></i>
      </div>
    </div>
  </a>
  <div id="dropdownAuctions" class="dropdown-menu greeneryBorder">
    <a class="dropdown-item" href="">Tafels</a>
    <a class="dropdown-item" href="">Stoelen</a>
    <a class="dropdown-item" href="">Pannen</a>
  </div>
</div>
<div id="menuSearchbar" class="col-lg-2 col-sm-4">
  <form class="form-inline" action="" method="post">
    <div class="input-group">
      <input class="form-control greeneryBorder" type="text" placeholder="Zoeken">
      <div class="input-group-append">
        <span class="input-group-text greeneryBackground greeneryBorder"><i class="fas fa-search"></i></span>
      </div>
    </div>
  </form>
</div>
<div class="col-lg-3 col-sm-4 col-6 alignRight">
  <a class="menuItem" href="">Plaats advertentie</a>
  <a class="menuItem" href="
  <?php
  if (!isset($_SESSION["username"])) {
    echo "index.php?page=inloggen";
  } else {
    echo "logout.php";
  }
  ?>
  ">
    <?php
    if (!isset($_SESSION["username"])) {
      echo "Inloggen";
    } else {
      echo "Uitloggen";
    }
    ?>
  </a>
  <a class="menuItem marginLeft marginRight" href="" id="dropdownCountries" data-toggle="dropdown">
    <div class="row">
      <div>
        <b>NL</b>
      </div>
      <div class="arrow">
        <i class="fas fa-caret-right arrowDown"></i>
      </div>
    </div>
  </a>
  <div id="dropdownLanguage" class="dropdown-menu greeneryBorder">
    <a class="dropdown-item" href="">NL</a>
    <a class="dropdown-item" href="">EN</a>
  </div>
  <a class="hamburgerMenu" onclick="openNav()"><i class="fa fa-bars hamburgerIcon"></i></a>
</div>
<div class="col-lg-2"><!-- White space --></div>

<div id="myNav" class="overlay">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <div class="overlay-content">
    <a href="index.php?page=home">Home</a>
    <a href="">Alle veilingen</a>
    <a href="">Plaats advertentie</a>
    <a href="index.php?page=inloggen">Inloggen</a>
  </div>
</div>

<script>
function openNav() {
  document.getElementById("myNav").style.height = "100%";
}

function closeNav() {
  document.getElementById("myNav").style.height = "0%";
}
</script>
