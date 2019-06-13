<?php
if(isset($_POST['wijzigen'])){
  try{
    $changeTopicNameQuery = "UPDATE Rubriek SET Rubrieknaam = ? where rubrieknummer = ?";
    $changeTopicNameStmt = $dbh->prepare($changeTopicNameQuery);
    $changeTopicNameStmt->execute(array(cleanInput($_POST['rubrieknaamEdit']), cleanInput($_POST['voorwerpId'])));
  }
  catch (PDOException $e) {
    echo "Kan rubrieken niet ophalen";
  }
}

if(isset($_POST['toevoegen'])){
  if($_POST['parentRubriek'] != ""){
    $parentRubriek = cleanInput($_POST['parentRubriek']);
  }else{
    $parentRubriek = "-1";
  }
  try{
    $changeTopicNameQuery = "EXEC toevoegen_rubriek ?, ?";
    $changeTopicNameStmt = $dbh->prepare($changeTopicNameQuery);
    $changeTopicNameStmt->execute(array($parentRubriek, cleanInput($_POST['rubrieknaam'])));
  }
  catch (PDOException $e) {
    echo "De rubriek bestaat al";
  }
}

if(isset($_POST['delete'])){

  try{
    $deleteTopicQuery = "EXEC Verwijderen_rubriek ?,?";
    $deleteTopicStmt = $dbh->prepare($deleteTopicQuery);
    $deleteTopicStmt->execute(array($_POST['codeParentDelete'], cleanInput($_POST['codeDelete'])));
  }
  catch (PDOException $e) {
    echo "De rubriek kan niet verwijderd worden";
  }
}


$headTopicContent = '';
try {
  $headTopicQuery = "SELECT rubrieknaam, rubrieknummer, parent FROM Rubriek WHERE parent = -1 ORDER BY rubrieknaam asc";
  $headTopicStmt = $dbh->prepare($headTopicQuery);
  $headTopicStmt->execute();
  if($headTopicStmt->rowCount()!=0){
    $headTopics = $headTopicStmt->fetchAll();
    foreach ($headTopics as $headTopic) {
      $headTopicContent .= '<tr>
      <td>'.$headTopic['rubrieknummer'].'</td>
      <td>'.$headTopic['rubrieknaam'].'</td>
      <td><button type="button" class="btn btn-warning btn-circle" data-toggle="modal" data-target="#wijzigenModal'.$headTopic['rubrieknummer'].'" style="margin-right:5px;"><i class="fas fa-pencil-alt"></i></button></td>
      <td><button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteModal'.$headTopic['rubrieknummer'].'"><i class="fas fa-trash-alt"></i></button></td>
      </tr>';
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
                        <input type="text" class="form-control" name="rubrieknaamEdit" value="<?=$headTopic['rubrieknaam']?>" placeholder="Rubrieknaam" pattern="[a-zA-Z ]{3,50}" minlength="3" maxlength="50" required>
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



      <div class="modal fade" id="deleteModal<?=$headTopic['rubrieknummer']?>" tabindex="-1" role="dialog">
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

                    <input type="hidden" class="form-control" name="codeDelete" value="<?=$headTopic['rubrieknummer']?>">
                    Weet je zeker dat je de rubriek '<?=$headTopic['rubrieknaam']?>' met rubrieknummer '<?=$headTopic['rubrieknummer']?>' wil verwijderen?
                    <input type="hidden" class="form-control" name="codeParentDelete" value="<?=$headTopic['parent']?>">
                  </div>
                </div>
              </div>
              <div class="modal-footer">

                <div class="text-left col-lg-6">
                  <button type="button" class="btn btn-default btn-width" data-dismiss="modal">Annuleren</button>
                </div>
                <div class="text-right col-lg-6">
                  <button type="submit" name="delete" class="btn btn-danger btn-width">Verwijderen</button>
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
  echo "Kan rubrieken niet laden";
}






$subTopicContent = '';
try {
  $subTopicQuery = "SELECT r1.rubrieknaam naam, r1.rubrieknummer nummer, r1.parent, r2.rubrieknaam parentnaam FROM Rubriek r1 inner join Rubriek r2 on r1.parent = r2.rubrieknummer WHERE r1.parent != -1 or r2.parent != -1 ORDER BY r1.rubrieknaam asc";
  $subTopicStmt = $dbh->prepare($subTopicQuery);
  $subTopicStmt->execute();
  if($subTopicStmt->rowCount()!=0){
    $subTopics = $subTopicStmt->fetchAll();
    foreach ($subTopics as $subTopic) {
      $subTopicContent .= '<tr>
      <td>'.$subTopic['nummer'].'</td>
      <td>'.$subTopic['naam'].'</td>
      <td>'.$subTopic['parentnaam'].'</td>
      <td><button type="button" class="btn btn-warning btn-circle" data-toggle="modal" data-target="#wijzigenModal'.$subTopic['nummer'].'" style="margin-right:5px;"><i class="fas fa-pencil-alt"></i></button></td>
      <td><button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteModal'.$subTopic['nummer'].'"><i class="fas fa-trash-alt"></i></button></td>
      </tr>';
      ?>
      <div class="modal fade" id="wijzigenModal<?=$subTopic['nummer']?>" tabindex="-1" role="dialog">
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
                        <input type="text" class="form-control" name="rubrieknaamEdit" value="<?=$subTopic['naam']?>" placeholder="Rubrieknaam" pattern="[a-zA-Z ]{3,50}" minlength="3" maxlength="50" required>
                        <input type="hidden" name="voorwerpId" value="<?=$subTopic['nummer']?>">
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


      <div class="modal fade" id="deleteModal<?=$subTopic['nummer']?>" tabindex="-1" role="dialog">
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

                    <input type="hidden" class="form-control" name="codeDelete" value="<?=$subTopic['nummer']?>">
                    <input type="hidden" class="form-control" name="codeParentDelete" value="<?=$subTopic['parent']?>">
                    Weet je zeker dat je de rubriek '<?=$headTopic['rubrieknaam']?>' met rubrieknummer '<?=$subTopic['nummer']?>' wil verwijderen?

                  </div>
                </div>
              </div>
              <div class="modal-footer">

                <div class="text-left col-lg-6">
                  <button type="button" class="btn btn-default btn-width" data-dismiss="modal">Annuleren</button>
                </div>
                <div class="text-right col-lg-6">
                  <button type="submit" name="delete" class="btn btn-danger btn-width">Verwijderen</button>
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
  echo "Kan rubrieken niet laden";
}
?>

<div class="pageWrapper">
  <button type="button" class="btn btn-warning btn-circle greeneryBackground" data-toggle="modal" data-target="#toevoegenModal" style="margin-right:5px;"><i class="fas fa-pencil-alt"></i> Rubriek toevoegen</button>

  <div class="modal fade" id="toevoegenModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form method="post">
        <div class="modal-content">
          <div class="modal-header modal-header-warning">
            <h5 class="modal-title">Rubriek toevoegen</h5>
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
                    <label for="rubrieknaam">Rubrieknaam</label>
                  </div>
                  <div class="form-group col-lg-12">
                    <input type="text" class="form-control" name="rubrieknaam" id="rubrieknaam" placeholder="Rubrieknaam" pattern="[a-zA-Z ]{3,50}" minlength="3" maxlength="50" required>
                  </div>

                  <div class="col-lg-12">
                    <label for="parentRubriek">Nummer van rubriek erboven (leeglaten voor hoofdrubriek)</label>
                  </div>
                  <div class="form-group col-lg-12">
                    <input type="text" class="form-control" name="parentRubriek" id="parentRubriek" placeholder="Nummer">
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
              <button type="submit" name="toevoegen" class="btn btn-warning width">Toevoegen</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="rubriekenTableContainer">
    <h2 class="textCenter mb-4"><b>Rubrieken beheren</b></h2>
    <h3 class="textCenter mb-4"><b>Hoofdrubrieken</b></h3>
    <div class="table-responsive">
      <table id="hoofdrubriekenTable" class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th>Rubrieknummer</th>
            <th>Rubrieknaam</th>
            <th>Naam wijzigen</th>
            <th>Rubriek verwijderen</th>
          </tr>
        </thead>
        <tbody>
          <?=$headTopicContent ?>
        </tbody>
      </table>
    </div>
    <h3 class="textCenter mb-4"><b>Subrubrieken</b></h3>
    <div class="table-responsive">
      <table id="subrubriekenTable" class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th>Rubrieknummer</th>
            <th>Rubrieknaam</th>
            <th>Rubriek erboven</th>
            <th>Naam wijzigen</th>
            <th>Rubriek verwijderen</th>
          </tr>
        </thead>
        <tbody>
          <?=$subTopicContent ?>
        </tbody>
      </table>
    </div>
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
      { "orderable": false, "targets": 2 }
    ]
  } );
} );
$(document).ready(function() {
  $('#subrubriekenTable').DataTable( {
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
  } );
} );
</script>
