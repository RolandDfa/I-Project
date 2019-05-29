<?php
if(isset($_GET['searchedText'])){
  $searchText = cleanInput($_GET['searchedText']);
}else{
  if(isset($_POST['searchText'])){
    $searchText = cleanInput($_POST['searchText']);

  }else{
    $searchText = "";
  }
}

if(isset($_GET['category'])){
  $categoryNumber = (int)cleanInput($_GET['category']);
  $andQuery = "and RubriekOpLaagsteNiveau in (
    select r1.rubrieknummer
    from Rubriek r1 left join Rubriek r2 on r1.parent = r2.rubrieknummer left join Rubriek r3 on r2.parent = r3.rubrieknummer left join Rubriek r4 on r3.parent = r4.rubrieknummer
    where r1.rubrieknummer = ".$categoryNumber." or r1.parent = ".$categoryNumber." or r2.rubrieknummer = ".$categoryNumber." or r2.parent = ".$categoryNumber." or r3.rubrieknummer = ".$categoryNumber." or r3.parent = ".$categoryNumber." or r4.rubrieknummer = ".$categoryNumber." or r4.parent  = ".$categoryNumber."
    )";
  }else{
    $andQuery = "";
  }

  ?>

  <div class="pageWrapper">

    <?php
    try{
      $overzichtquery = "SELECT titel, Voorwerp.voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp inner join Voorwerp_in_rubriek on Voorwerp.voorwerpnummer = Voorwerp_in_rubriek.voorwerpnummer WHERE veilingGesloten = 0 and (titel like ? or beschrijving like ?)".$andQuery;
      $stmt = $dbh->prepare($overzichtquery);
      $stmt->execute(array('%'.$searchText.'%', '%'.$searchText.'%'));
      if ($stmt->rowCount() != 0) {
        if($searchText != ""){
          echo '<h4><b>Gevonden resultaten voor: "'.$searchText.'"</b></h4><br><div class="row contentWrapper">';
        }
        else{
          if(isset($categoryNumber)){
            try{
              $categoryquery = "SELECT rubrieknaam from Rubriek where rubrieknummer = ?";
              $categorystmt = $dbh->prepare($categoryquery);
              $categorystmt->execute(array($categoryNumber));
              if($categorystmt->rowCount()!=0){
                $categorys = $categorystmt->fetchAll();
                foreach ($categorys as $searchedcategory) {
                  echo '<h4><b>Alle veilingen in de rubriek: "'.$searchedcategory['rubrieknaam'].'"</b></h4><br><div class="row contentWrapper">';
                }
              }else{
                echo '<h4><b>Deze gekozen rubriek: "'.$searchedcategory['rubrieknaam'].'" bestaat niet</b></h4>';
              }
            }catch (PDOException $e){
              echo "Er gaat iets fout met het ophalen van categorieën";
            }
          }else{
            echo '<h4><b>Alle veilingen</b></h4><br><div class="row contentWrapper">';
          }
        }
        $results = $stmt->fetchAll();
        foreach( $results as $result ) {
          $voorwerpnummer = $result['voorwerpnummer'];
          echo '<div class="cardItem">
          <a href="index.php?page=veiling&searchedText='.$searchText.'&id='.$result['voorwerpnummer'].'">
          <div class="card shadow-sm">
          <div class="cardImage">';

          try{
            $imagesquery = "SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = ?";
            $imagesStmt = $dbh->prepare($imagesquery);
            $imagesStmt->execute(array($voorwerpnummer));
            if($imagesStmt->rowCount()!=0){
              $foundImage = false;
              $images = $imagesStmt->fetchAll();
              foreach ($images as $image) {
                $imagesFromUpload = scandir("./upload");
                foreach ($imagesFromUpload as $uploadImage) {
                  if($image['bestandsnaam'] == $uploadImage){
                    $foundImage = true;
                  }
                }
                if($foundImage){
                  echo '<img class="rounded-top" src="./upload/'.$uploadImage.'" width="100%" height="220" alt="'.$result['titel'].'">';
                }else{
                  echo '<img class="rounded-top" src="../pics/'.$image['bestandsnaam'].'" width="100%" height="220" alt="'.$result['titel'].'">';
                }
              }
            }else{
              echo '<img class="rounded-top" src="images/image_placeholder.jpg" width="100%" height="220" alt="'.$result['titel'].'">';
            }
          }catch (PDOException $e){
            echo "Er gaat iets fout met het ophalen van de plaatjes";
          }

          echo '</div>
          <div class="cardTitle">
          <div class="cardHeader">'.
          $result['titel'].'
          </div>
          <div class="cardPrice">';

          try{
            $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = ? ORDER BY bodbedrag DESC";
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
          }
          catch (PDOException $e){
            echo "Er gaat iets fout met het ophalen van het hoogste bod";
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
        if(!empty($searchText)){
          echo '<h4><b>Geen resultaten voor: "'.$searchText.'"</b></h4>';
        }else if(!empty($andQuery)){

          $categoryquery = "SELECT rubrieknaam from Rubriek where rubrieknummer = ?";
          $categorystmt = $dbh->prepare($categoryquery);
          $categorystmt->execute(array($categoryNumber));
          if($categorystmt->rowCount()!=0){
            $categorys = $categorystmt->fetchAll();
            foreach ($categorys as $searchedcategory) {
              echo '<h4><b>Geen resultaten voor de rubriek: "'.$searchedcategory['rubrieknaam'].'"</b></h4>';
            }
          }else{
            echo '<h4><b>Deze rubriek bestaat niet</b></h4>';
          }
        }else{
          echo '<h4><b>Geen resultaten</b></h4>';
        }
      }
    }
    catch (PDOException $e){
      echo "Er gaat iets fout met het ophalen van de veilingen: ".$e->getMessage();
    }
    ?>
  </div>
