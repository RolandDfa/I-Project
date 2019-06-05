<?php
if(isset($_POST['delete'])){
  try{
    $changeAuctionValid = "UPDATE Voorwerp SET veilingGesloten = ? where voorwerpnummer = ?";
    $changeAuctionStmt = $dbh->prepare($changeAuctionValid);
    $changeAuctionStmt->execute(array(1, cleanInput($_POST['codeDelete'])));
  }
  catch (PDOException $e) {
    echo "Fout met de database: {$e->getMessage()} ";
  }
}
?>
<div class="pageWrapper">

  <h2 class="textCenter mb-4"><b>Veilingen beheren</b></h2>

  <?php
  try {
    $sqlAuctions = "SELECT voorwerpnummer, titel, verkopernaam, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE veilingGesloten = 0";
    $querySelect = $dbh->prepare($sqlAuctions);
    $querySelect->execute();
    if ($querySelect->rowCount() != 0) {
      $auctionRows = '';
      $results = $querySelect->fetchAll();
      foreach( $results as $result ) {
        $auctionRows.= '
        <tr>
        <td>'.$result['voorwerpnummer'].'</td>
        <td style="max-width: 400px;">'.$result['titel'].'</td>
        <td>'.$result['verkopernaam'].'</td>
        <td>'.date('d-m-Y',strtotime($result['looptijdeindeDag'])).' '.date('H:m:s',strtotime($result['looptijdeindeTijdstip'])).'</td>
        <td><button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteModal'.$result['voorwerpnummer'].'"><i class="fas fa-times"></i></button></td>
        </tr>
        ';
        ?>

        <!-- Sluiten modal -->
        <div class="modal fade" id="deleteModal<?=$result['voorwerpnummer']?>" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <form method="post">
              <div class="modal-content">
                <div class="modal-header modal-header-danger">
                  <h5 class="modal-title">Veiling sluiten</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12">

                      <input type="hidden" class="form-control" name="codeDelete" value="<?=$result['voorwerpnummer']?>">
                      Weet je zeker dat je de veiling met veilingnummer <?=$result['voorwerpnummer']?> wilt sluiten?

                    </div>
                  </div>
                </div>
                <div class="modal-footer">

                  <div class="text-left col-lg-6">
                    <button type="button" class="btn btn-default btn-width" data-dismiss="modal">Annuleren</button>
                  </div>
                  <div class="text-right col-lg-6">
                    <button type="submit" name="delete" class="btn btn-danger btn-width">Sluiten</button>
                  </div>

                </div>
              </div>
            </form>
          </div>
        </div>
        <?php
      }
    }else{
      $auctionRows = '';
    }
  } catch (PDOException $e) {
    echo "Er gaat iets fout met het sluiten van veilingen".$e->getMessage();
  }
  ?>

  <div class="table-responsive">
    <table id="beheerVeilingTabel" class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Voorwerpnummer</th>
          <th>Titel</th>
          <th>Verkoper</th>
          <th>Einddatum</th>
          <th>Acties</th>
        </tr>
      </thead>
      <tbody>
        <?=$auctionRows?>
      </tbody>
    </table>
  </div>

</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#beheerVeilingTabel').DataTable({
    "language": {
      "lengthMenu": "Toon _MENU_ rubrieken per pagina",
      "zeroRecords": "Geen resultaten gevonden",
      "info": "Toon resultaten van pagina _PAGE_ van _PAGES_",
      "infoEmpty": "Geen pagina's gevonden",
      "infoFiltered": "(gefilterd van _MAX_ totale resultaten)",
      "sSearch": "Zoeken: ",
      "oPaginate": {
        "sFirst": "Eerste pagina", // This is the link to the first page
        "sPrevious": "Vorige", // This is the link to the previous page
        "sNext": "Volgende", // This is the link to the next page
        "sLast": "Laatste pagina" // This is the link to the last page
      }
    },
    "columnDefs": [
      { "orderable": false, "targets": 4 }
    ]
  });
});
</script>
