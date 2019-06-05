<?php
// Get gebruiker amount
try {
  $sqlGebruikers = "SELECT COUNT(gebruikersnaam) FROM Gebruiker";
  $querySelect = $dbh->prepare($sqlGebruikers);
  $querySelect->execute();
  if ($querySelect->rowCount() != 0) {
    $results = $querySelect->fetchAll();
    foreach( $results as $result ) {
      $aantalGebruikers = $result[0];
    }
  }
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}

// Get verkoper amount
try {
  $sqlVerkopers = "SELECT COUNT(gebruiker) FROM Verkoper";
  $querySelect = $dbh->prepare($sqlVerkopers);
  $querySelect->execute();
  if ($querySelect->rowCount() != 0) {
    $results = $querySelect->fetchAll();
    foreach( $results as $result ) {
      $aantalVerkopers = $result[0];
    }
  }
} catch (PDOException $e) {
  echo "Fout met de database: {$e->getMessage()} ";
}

// Get veiling amount
try {
  $sqlVeilingen = "SELECT COUNT(voorwerpnummer) FROM Voorwerp WHERE veilingGesloten = 0";
  $querySelect = $dbh->prepare($sqlVeilingen);
  $querySelect->execute();
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
?>
<div class="row">

  <div class="col-xl-3 col-md-6 mt-4 mb-4">
    <div class="card borderLeftGray">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="font-weight-bold greeneryText text-uppercase mb-1">Accounts</div>
            <div class="h5 font-weight-bold textGrayDark"><?=$aantalGebruikers?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-user-tie fa-2x textGrayLight"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mt-4 mb-4">
    <div class="card borderLeftGray">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="font-weight-bold greeneryText text-uppercase mb-1">Verkopers</div>
            <div class="h5 font-weight-bold textGrayDark"><?=$aantalVerkopers?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-user-tag fa-2x textGrayLight"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mt-4 mb-4">
    <div class="card borderLeftGray">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="font-weight-bold greeneryText text-uppercase mb-1">Veilingen</div>
            <div class="h5 font-weight-bold textGrayDark"><?=$aantalVeilingen?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-gavel fa-2x textGrayLight"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mt-4 mb-4">
    <div class="card borderLeftGray">
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
  </div>

</div>
<div class="row">

  <!-- Bar Chart -->
  <div class="col-xl-6 col-md-12 mb-4">
    <div class="card">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Aantal veilingen</h6>
      </div>
      <div class="card-body">
        <div id="chart_div"></div>
      </div>
    </div>
  </div>

</div>

<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {
  var data = google.visualization.arrayToDataTable([
    ["Year", "Accounts"],
    ["2018", 10],
    ["2019", 20],
    ["2020", 15]
  ]);

  var options = {
    width: 550,
    height: 400,
    legend: { position: 'top'},
    colors: ['rgb(136, 176, 75)', 'Accounts']
  };

  var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}
</script>
