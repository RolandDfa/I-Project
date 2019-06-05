<div class="pageWrapper">

  <h2 class="textCenter mb-4"><b>Veilingen beheren</b></h2>
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
        <?php
        try {
          $sqlAuctions = "SELECT voorwerpnummer, titel, verkopernaam, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE veilingGesloten = 0";
          $querySelect = $dbh->prepare($sqlAuctions);
          $querySelect->execute();
          $count = 0;
          if ($querySelect->rowCount() != 0) {
            $results = $querySelect->fetchAll();
            foreach( $results as $result ) {
              echo '
              <tr>
                <td>'.$result['voorwerpnummer'].'</td>
                <td style="max-width: 400px;">'.$result['titel'].'</td>
                <td>'.$result['verkopernaam'].'</td>
                <td>'.date('d-m-Y',strtotime($result['looptijdeindeDag'])).' '.date('H:m:s',strtotime($result['looptijdeindeTijdstip'])).'</td>
                <td><button type="button" class="btn btn-warning btn-circle" data-toggle="modal" data-target="#wijzigenModal'.$count.'" style="margin-right:5px;"><i class="fas fa-pencil-alt"></i></button><button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteModal'.$count.'"><i class="fas fa-times"></i></button></td>
              </tr>
              ';
              ?>
              <!-- Wijzigen modal -->
                <div class="modal fade" id="wijzigenModal<?=$count?>" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <form method="post">
                      <div class="modal-content">
                        <div class="modal-header modal-header-warning">
                          <h5 class="modal-title">Medewerker Bewerken</h5>
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
                                  <label>Voornaam</label>
                                </div>
                                <div class="form-group col-lg-12">
                                  <input type="text" class="form-control" name="voornaamEdit" value="<?=$result['voorwerpnummer']?>" placeholder="Voornaam" required>
                                </div>

                              </div>

                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">

                          <div class="text-left col-lg-6">
                            <button type="button" class="btn btn-default btn-width" data-dismiss="modal">Annuleren</button>
                          </div>
                          <div class="text-right col-lg-6">
                            <button type="submit" name="wijzigen" class="btn btn-warning btn-width">Wijzigen</button>
                          </div>

                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Verwijderen modal -->
                <div class="modal fade" id="deleteModal<?=$count?>" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <form method="post">
                      <div class="modal-content">
                        <div class="modal-header modal-header-danger">
                          <h5 class="modal-title">Medewerker verwijderen</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-lg-12">

                              <input type="hidden" class="form-control" name="codeDelete" value="">
                              Weet je zeker dat je deze medewerker wilt verwijderen?

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
              $count++;
            }
          }
        } catch (PDOException $e) {
          echo "Er gaat iets fout met het sluiten van veilingen".$e->getMessage();
        }
        ?>
      </tbody>
    </table>
  </div>

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#beheerVeilingTabel').DataTable();
} );
</script>
