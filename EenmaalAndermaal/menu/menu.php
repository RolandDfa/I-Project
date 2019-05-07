<?php session_start(); ?>
<div class="col-lg-2"><!-- White space --></div>
<div class="col-lg-3">
  <a id="logo" href="index.php?page=home"><img src="images/EenmaalAndermaalLogo.png" width="140" height="60" alt="Logo"></a>
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
<div class="col-lg-2 searchbar">
  <form class="form-inline" action="" method="post">
    <div class="input-group">
      <input class="form-control greeneryBorder" type="text" placeholder="Zoeken">
      <div class="input-group-append">
        <span class="input-group-text greeneryBackground greeneryBorder"><i class="fas fa-search"></i></span>
      </div>
    </div>
  </form>
</div>
<div class="col-lg-3">
  <a class="menuItem" href="">Berichten</a>
  <a class="menuItem" href="">Plaats advertentie</a>
  <a class="menuItem" href="
  <?php
  if (!isset($_SESSION["username"])) {
    echo "index.php?page=login";
  }else{
    echo "index.php?page=loguit";
  }
  ?>
  ">
    <?php
    if (!isset($_SESSION["username"])) {
      echo "Inloggen";
    }else{
      echo "Uitloggen";
    }
    ?>
  </a>
  <a class="menuItem marginLeft" href="" id="dropdownCountries" data-toggle="dropdown">
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
</div>
<div class="col-lg-2"><!-- White space --></div>
