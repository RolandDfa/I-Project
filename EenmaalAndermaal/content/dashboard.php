<?php
// Get gebruiker activ
try {
  $sqlGebruikersA = "SELECT COUNT(gebruikersnaam) FROM Gebruiker WHERE valid = 1";
  $querySelect = $dbh->prepare($sqlGebruikersA);
  $querySelect->execute();
  if ($querySelect->rowCount() != 0) {
    $results = $querySelect->fetchAll();
    foreach( $results as $result ) {
      $aantalGebruikersA = $result[0];
    }
  }
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}

// Get gebruiker inactiv
try {
  $sqlGebruikersI = "SELECT COUNT(gebruikersnaam) FROM Gebruiker WHERE valid = 0";
  $querySelect = $dbh->prepare($sqlGebruikersI);
  $querySelect->execute();
  if ($querySelect->rowCount() != 0) {
    $results = $querySelect->fetchAll();
    foreach( $results as $result ) {
      $aantalGebruikersI = $result[0];
    }
  }
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}

// Get veiling amount
try {
  $sqlVeilingen = "SELECT COUNT(voorwerpnummer) FROM Voorwerp WHERE veilingGesloten = ?";
  $querySelect = $dbh->prepare($sqlVeilingen);
  $querySelect->execute(array(0));
  if ($querySelect->rowCount() != 0) {
    $results = $querySelect->fetchAll();
    foreach( $results as $result ) {
      $aantalVeilingen = $result[0];
    }
  }
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}

// Get rubriek amount
try {
  $sqlRubrieken = "SELECT COUNT(rubrieknummer) FROM Rubriek";
  $querySelect = $dbh->prepare($sqlRubrieken);
  $querySelect->execute();
  if ($querySelect->rowCount() != 0) {
    $results = $querySelect->fetchAll();
    foreach( $results as $result ) {
      $aantalRubrieken = $result[0];
    }
  }
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}


// Get veilingen per jaar
try {
  $sqlRubrieken = "SELECT DATEPART(yyyy, looptijdbeginDag), COUNT(voorwerpnummer) FROM Voorwerp GROUP BY DATEPART(yyyy, looptijdbeginDag) ORDER BY DATEPART(yyyy, looptijdbeginDag) ASC";
  $querySelect = $dbh->prepare($sqlRubrieken);
  $querySelect->execute();
  if ($querySelect->rowCount() != 0) {
    $dataVeilingen = $querySelect->fetchAll();
  }
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}

// Get rubrieken
try {
  $sqlBiedingen = "SELECT DATEPART(yyyy, bodDag), COUNT(voorwerp) FROM Bod GROUP BY DATEPART(yyyy, bodDag) ORDER BY DATEPART(yyyy, bodDag) ASC";
  $querySelect = $dbh->prepare($sqlBiedingen);
  $querySelect->execute();
  if ($querySelect->rowCount() != 0) {
    $dataBiedingen = $querySelect->fetchAll();
  }
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}
?>
<div class="row">

  <!-- Accounts -->
  <div class="col-xl-3 col-md-6 mt-4 mb-4">
    <div class="beheerCard">

      <a href="index.php?page=beheerAccounts">
      <div class="card borderLeftGreenery shadow-sm">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="font-weight-bold greeneryText text-uppercase mb-1">Accounts (actief)</div>
              <div class="h5 font-weight-bold textGrayDark"><?=$aantalGebruikersA?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-tie fa-2x textGrayLight"></i>
            </div>
          </div>
        </div>
      </div>
      </a>

    </div>
  </div>

  <!-- Verkopers -->
  <div class="col-xl-3 col-md-6 mt-4 mb-4">
    <div class="beheerCard">

      <a href="index.php?page=beheerAccounts">
      <div class="card borderLeftGreenery shadow-sm">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="font-weight-bold greeneryText text-uppercase mb-1">Accounts (inactief)</div>
              <div class="h5 font-weight-bold textGrayDark"><?=$aantalGebruikersI?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-tag fa-2x textGrayLight"></i>
            </div>
          </div>
        </div>
      </div>
      </a>

    </div>
  </div>

  <!-- Actieve veilingen -->
  <div class="col-xl-3 col-md-6 mt-4 mb-4">
    <div class="beheerCard">

      <a href="index.php?page=beheerVeilingen">
      <div class="card borderLeftGreenery shadow-sm">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="font-weight-bold greeneryText text-uppercase mb-1">Veilingen (actief)</div>
              <div class="h5 font-weight-bold textGrayDark"><?=$aantalVeilingen?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-gavel fa-2x textGrayLight"></i>
            </div>
          </div>
        </div>
      </div>
      </a>

    </div>
  </div>

  <!-- Rubrieken -->
  <div class="col-xl-3 col-md-6 mt-4 mb-4">
    <div class="beheerCard">

      <a href="index.php?page=beheerRubriekenboom">
      <div class="card borderLeftGreenery shadow-sm">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="font-weight-bold greeneryText text-uppercase mb-1">Rubrieken</div>
              <div class="h5 font-weight-bold textGrayDark"><?=$aantalRubrieken?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-align-left fa-2x textGrayLight"></i>
            </div>
          </div>
        </div>
      </div>
      </a>

    </div>
  </div>

</div>
<div class="row">

  <!-- Veilingen per jaar -->
  <div class="col-xl-6 col-md-12 mb-4">
    <div class="card shadow-sm chartBlock">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Aantal veilingen per jaar</h6>
      </div>
      <div class="card-body chartBlock">
        <div id="veilingen_div" class="chartBlock"></div>
      </div>
    </div>
  </div>

  <!-- Biedingen per jaar -->
  <div class="col-xl-6 col-md-12 mb-4">
    <div class="card shadow-sm chartBlock">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Aantal biedingen per jaar</h6>
      </div>
      <div class="card-body chartBlock">
        <div id="piechart_3d" class="chartBlock"></div>
      </div>
    </div>
  </div>

</div>

<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawLineChart);
google.charts.setOnLoadCallback(drawPieChart);

function drawLineChart() {
  var data = google.visualization.arrayToDataTable([
    ["Year", "Veilingen"],
    <?php
    foreach($dataVeilingen as $result) {
      echo '["'.$result[0].'", '.$result[1].'],';
    }
    ?>
  ]);

  var options = {
    colors: ['rgb(136, 176, 75)', 'Veilingen']
  };

  var chart = new google.visualization.AreaChart(document.getElementById('veilingen_div'));
  chart.draw(data, options);
}

function drawPieChart() {
  var data = google.visualization.arrayToDataTable([
    ['Rubrieken', 'Biedingen'],
    <?php
    foreach($dataBiedingen as $result) {
      echo '["'.$result[0].'", '.$result[1].'],';
    }
    ?>
  ]);

  var options = {
    colors: ['rgb(136, 176, 75)', 'Biedingen']
  };

  var chart = new google.visualization.ColumnChart(document.getElementById('piechart_3d'));
  chart.draw(data, options);
}

$(window).resize(function(){
  drawLineChart();
  drawPieChart();
});
</script>
