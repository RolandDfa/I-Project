<?php
if(isset($_POST['wijzigen'])){
  try{
    $changeTopicNameQuery = "UPDATE Rubriek SET Rubrieknaam = ? where rubrieknummer = ?";
    $changeTopicNameStmt = $dbh->prepare($changeTopicNameQuery);
    $changeTopicNameStmt->execute(array(cleanInput($_POST['rubrieknaamEdit']), cleanInput($_POST['voorwerpId'])));
  }
  catch (PDOException $e) {
    echo "Fout met de database: {$e->getMessage()} ";
  }
}


$headTopicContent = '';
try {
  $headTopicQuery = "SELECT rubrieknaam, rubrieknummer FROM Rubriek WHERE parent = -1 ORDER BY rubrieknaam asc";
  $headTopicStmt = $dbh->prepare($headTopicQuery);
  $headTopicStmt->execute();
  if($headTopicStmt->rowCount()!=0){
    $headTopics = $headTopicStmt->fetchAll();
    foreach ($headTopics as $headTopic) {
      $headTopicContent .= '<tr><td>'.$headTopic['rubrieknummer'].'</td><td>'.$headTopic['rubrieknaam'].'</td><td><button type="button" class="btn btn-warning btn-circle greeneryBackground" data-toggle="modal" data-target="#wijzigenModal'.$headTopic['rubrieknummer'].'" style="margin-right:5px;"><i class="fas fa-pencil-alt"></i></button></td></tr>';
        ?>
        <div class="modal fade" id="wijzigenModal<?=$headTopic['rubrieknummer']?>" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <form method="post">
              <div class="modal-content">
                <div class="modal-header modal-header-warning">
                  <h5 class="modal-title">Rubrieknaam wijzigen</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="hidden" class="form-control" name="codeEdit" value="">

                      <div class="row">

                        <div class="col-lg-12">
                          <label>Rubrieknaam</label>
                        </div>
                        <div class="form-group col-lg-12">
                          <input type="text" class="form-control" name="rubrieknaamEdit" value="<?=$headTopic['rubrieknaam']?>" placeholder="Rubrieknaam" required>
                          <input type="hidden" name="voorwerpId" value="<?=$headTopic['rubrieknummer']?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="text-left col-lg-6">
                    <button type="button" class="btn btn-default width" data-dismiss="modal">Annuleren</button>
                  </div>
                  <div class="text-right col-lg-6">
                    <button type="submit" name="wijzigen" class="btn btn-warning width">Wijzigen</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <?php
    }
  }
  else{
    echo 'Nog geen bod';
  }
  }
catch (PDOException $e) {
  echo "Kan rubrieken niet laden".$e->getMessage();
}
?>

<div class="pageWrapper">
  <div class="rubriekenTableContainer">
    <h2 class="textCenter mb-4"><b>Rubrieken beheren beheren</b></h2>
    <h3 class="textCenter mb-4"><b>Hoofdrubrieken</b></h3>
    <table id="hoofdrubriekenTable" class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Rubrieknummer</th>
          <th>Rubrieknaam</th>
          <th>Naam wijzigen</th>
        </tr>
      </thead>
      <tbody>
        <?=$headTopicContent ?>
      </tbody>
    </table>
  </div>
</div>


<script>
$(document).ready(function() {
    $('#hoofdrubriekenTable').DataTable( {
        "language": {
            "lengthMenu": "Toon _MENU_ rubrieken per pagina",
            "zeroRecords": "Geen resultaten gevonden",
            "info": "Toon resultaten van pagina _PAGE_ van _PAGES_",
            "infoEmpty": "Geen pagina's gevonden",
            "infoFiltered": "(gefilterd van _MAX_ totale resultaten)"
        }
    } );
} );
</script>
