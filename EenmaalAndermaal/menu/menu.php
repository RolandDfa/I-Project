<div class="col-lg-2"><!-- White space --></div>
<div class="col-lg-3 col-sm-6 col-6">
  <a id="logo" href="index.php?page=home"><img src="images/EenmaalAndermaalLogo.png" width="120" height="60" alt="Logo"></a>
  <div class="btn-group">
    <a class="menuItem dropdown-toggle" href="" data-toggle="dropdown"><b>Alle categorieën</b></a>
    <div class="dropdown-menu greeneryBorder">
      <?php
      // Get the headings from the database
      try{
        $data = $dbh->query("SELECT rubrieknaam FROM Rubriek WHERE parent is null ORDER BY rubrieknaam asc");
        while($row = $data->fetch()){
          echo '<a class="dropdown-item" href="">'.$row['rubrieknaam'].'</a>';
        }
      }
      catch (PDOException $e){
        echo "Kan rubrieken niet laden".$e->getMessage();
      }
      ?>
    </div>
  </div>
</div>
<div id="menuSearchbar" class="col-lg-2 col-sm-4">
  <form class="form-inline" action="index.php?page=overzicht" method="post">
    <div class="input-group">
      <input class="form-control greeneryBorder" type="text" placeholder="Zoeken" required minlength="2" name="searchText">
      <div class="input-group-append">
        <button type="submit" class="input-group-text greeneryBackground greeneryBorder"><i class="fa fa-search"></i></button>
      </div>
    </div>
  </form>
</div>
<div class="col-lg-3 col-sm-6 col-6 alignRight">
  <a class="menuItem" href="">Plaats advertentie</a>
  <?php
  if (!isset($_SESSION["username"])) {
    echo '<a class="menuItem" href="index.php?page=inloggen">Inloggen</a>';
  } else {
    echo '<div class="btn-group">
            <a class="menuItem marginLeft marginRight dropdown-toggle" href="" data-toggle="dropdown">'.$_SESSION["username"].'</a>
            <div class="dropdown-menu greeneryBorder dropdown-menu-right">
              <a class="dropdown-item" href="index.php?page=account">Mijn account</a>
              <a class="dropdown-item" href="logout.php">Uitloggen</a>
            </div>
          </div>';
  }
  ?>
  <div class="btn-group">
    <a class="menuItem dropdown-toggle" href="" data-toggle="dropdown">NL</a>
    <div id="dropdownLanguage" class="dropdown-menu greeneryBorder dropdown-menu-right">
      <a class="dropdown-item" href="">NL</a>
      <a class="dropdown-item" href="">EN</a>
    </div>
  </div>
  <a class="phoneButton" onclick="openNav()"><i class="fa fa-bars phoneIcon"></i></a>
  <?php
  if (isset($_SESSION["username"])) {
    echo '<a class="phoneButton" href="index.php?page=account"><i class="fas fa-user-tie phoneIcon"></i></a>';
  }
  ?>

</div>
<div class="col-lg-2"><!-- White space --></div>

<div id="myNav" class="overlay">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <div class="overlay-content">
    <a href="index.php?page=home">Home</a>
    <a href="">Alle veilingen</a>
    <a href="">Plaats advertentie</a>
    <?php
    if (!isset($_SESSION["username"])) {
      echo '<a href="index.php?page=inloggen">Inloggen</a>';
    } else {
      echo '<a href="logout.php">Uitloggen</a>';
    }
    ?>
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
