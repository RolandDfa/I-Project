<?php
if(isset($_POST['blokkeren'])){
  try{
    $changeUserValid = "UPDATE Gebruiker SET valid = ? where gebruikersnaam = ?";
    $changeUserStmt = $dbh->prepare($changeUserValid);
    $changeUserStmt->execute(array(0, cleanInput($_POST['codeBokkeren'])));
  }
  catch (PDOException $e) {
    echo "Fout met de database: {$e->getMessage()} ";
  }
}
?>
<div class="pageWrapper">

  <h2 class="textCenter mb-4"><b>Actieve accounts beheren</b></h2>

  <?php
  try {
    $sqlAuctions = "SELECT gebruikersnaam, mailbox, gebruikersStatus FROM Gebruiker WHERE valid = 1 AND gebruikersStatus != 4";
    $querySelect = $dbh->prepare($sqlAuctions);
    $querySelect->execute();
    if ($querySelect->rowCount() != 0) {
      $accountRows = '';
      $results = $querySelect->fetchAll();
      foreach( $results as $result ) {
        $accountRows.= '
        <tr>
        <td>'.$result['gebruikersnaam'].'</td>
        <td>'.$result['mailbox'].'</td>
        <td>'.$result['gebruikersStatus'].'</td>
        <td><button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#blokkerenModal'.$result['gebruikersnaam'].'"><i class="fas fa-ban"></i></button></td>
        </tr>
        ';
        ?>

        <!-- Blokkeren modal -->
        <div class="modal fade" id="blokkerenModal<?=$result['gebruikersnaam']?>" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <form method="post">
              <div class="modal-content">
                <div class="modal-header modal-header-danger">
                  <h5 class="modal-title">Blokkeren sluiten</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12">

                      <input type="hidden" class="form-control" name="codeBokkeren" value="<?=$result['gebruikersnaam']?>">
                      Weet je zeker dat je het account met gebruikersnaam <?=$result['gebruikersnaam']?> wilt blokkeren?

                    </div>
                  </div>
                </div>
                <div class="modal-footer">

                  <div class="text-left col-lg-6">
                    <button type="button" class="btn btn-default btn-width" data-dismiss="modal">Annuleren</button>
                  </div>
                  <div class="text-right col-lg-6">
                    <button type="submit" name="blokkeren" class="btn btn-danger btn-width">Blokkeren</button>
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
    <table id="beheerAccountsTabel" class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Gebruikersnaam</th>
          <th>Email</th>
          <th>Accounttype</th>
          <th>Acties</th>
        </tr>
      </thead>
      <tbody>
        <?=$accountRows?>
      </tbody>
    </table>
  </div>

</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#beheerAccountsTabel').DataTable({
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
      { "orderable": false, "targets": 3 }
    ]
  });
});
</script>
