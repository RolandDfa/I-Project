
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
      $data = $dbh->query("SELECT titel, voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE veilingGesloten = 0 and titel like '%$searchText%'");
      if($data->rowCount()){
        if($searchText != ""){
        echo '<h4><b>Gevonden resultaten voor: "'.$searchText.'"</b></h4><br><div class="row contentWrapper">';
      }
        else{
          echo '<h4><b>Alle veilingen</b></h4><br><div class="row contentWrapper">';
        }
        while($row = $data->fetch()){
          $voorwerpnummer = $row['voorwerpnummer'];
          echo '<div class="cardItem">
          <a href="">
          <div class="card shadow-sm">
          <div class="cardImage">';
          $imageData = $dbh->query("SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = $voorwerpnummer");
          if($imageData->rowCount()){
            while($image = $imageData->fetch()){
              echo '<img class="rounded-top" src="uploaded_content/'.$image['bestandsnaam'].'" width="100%" height="220" alt="'.$row['titel'].'">';
            }
          }else{
            echo '<img class="rounded-top" src="uploaded_content/image_placeholder.png" width="100%" height="220" alt="'.$row['titel'].'">';
          }
          echo '</div>
          <div class="cardTitle">
          <div class="cardHeader">'.
          $row['titel'].'
          </div>
          <div class="cardFooter">
          Sluit '.$row['looptijdeindeDag'].' om '.date('H:i.s',strtotime($row['looptijdeindeTijdstip'])).'
          </div>
          </div>
          </div>
          </a>
          </div>';
        }
      }else{
        echo '<h4><b>Geen resultaten voor: "'.$searchText.'"</b></h4>';
      }
    }
    catch (PDOException $e){
      echo "Er gaat iets fout met het ophalen van de artikelen: ".$e->getMessage();
    }
  ?>
</div>
</div>
