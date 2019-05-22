<?php
if(isset($_POST['searchText'])){
  $searchText = cleanInput($_POST['searchText']);
}else{
  $searchText = "";
}

?>

<div class="pageWrapper">

  <?php
  try{
    $overzichtquery = "SELECT titel, voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE veilingGesloten = 0 and (titel like ? or beschrijving like ?)";
    $stmt = $dbh->prepare($overzichtquery);
    $stmt->execute(array('%'.$searchText.'%', '%'.$searchText.'%'));
    if ($stmt->rowCount() != 0) {
      if($searchText != ""){
        echo '<h4><b>Gevonden resultaten voor: "'.$searchText.'"</b></h4><br><div class="row contentWrapper">';
      }
      else{
        echo '<h4><b>Alle veilingen</b></h4><br><div class="row contentWrapper">';
      }
      $results = $stmt->fetchAll();
      foreach( $results as $result ) {
        $voorwerpnummer = $result['voorwerpnummer'];
        echo '<div class="cardItem">
        <a href="index.php?page=veiling&id='.$result['voorwerpnummer'].'">
        <div class="card shadow-sm">
        <div class="cardImage">';

        $imagesquery = "SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = ?";
        $imagesStmt = $dbh->prepare($imagesquery);
        $imagesStmt->execute(array($voorwerpnummer));
        if($imagesStmt->rowCount()!=0){
          $images = $imagesStmt->fetchAll();
          foreach ($images as $image) {
            echo '<img class="rounded-top" src="uploaded_content/'.$image['bestandsnaam'].'" width="100%" height="220" alt="'.$result['titel'].'">';
          }
        }else{
          echo '<img class="rounded-top" src="images/image_placeholder.jpg" width="100%" height="220" alt="'.$result['titel'].'">';
        }
        echo '</div>
        <div class="cardTitle">
        <div class="cardHeader">'.
        $result['titel'].'
        </div>
        <div class="cardPrice">';

        $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = ? ORDER BY bodbedrag ASC";
        $priceStmt = $dbh->prepare($pricequery);
        $priceStmt->execute(array($voorwerpnummer));
        if($priceStmt->rowCount()!=0){
          $prices = $priceStmt->fetchAll();
          foreach ($prices as $price) {
            echo 'Hoogste bod: &euro; '.str_replace('.', ',', $price['bodbedrag']);
          }
        }
        else{
          echo 'Nog geen bod';
        }
        echo '</div>
        <div class="cardFooter">
        Sluit '.date_format(date_create($result['looptijdeindeDag']), "d-m-Y").' om '.date('H:i.s',strtotime($result['looptijdeindeTijdstip'])).' uur
        </div>';

        echo '
        </div>
        </div>
        </a>
        </div>';
      }
    } else {
      echo '<h4><b>Geen resultaten voor: "'.$searchText.'"</b></h4>';
    }
  }
  catch (PDOException $e){
    echo "Er gaat iets fout met het ophalen van de veilingen: ".$e->getMessage();
  }
  ?>
</div>
