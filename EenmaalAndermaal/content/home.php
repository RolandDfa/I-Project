<div class="homeContainer">

  <div id="reviews" class="row">
    <h4><b>Reviews</b></h4>
  </div>

  <div class="row">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="d-block w-100" src="images/Reviews/Review1-min.png" width="100%" height= auto alt="First review">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="images/Reviews/Review2-min.png" width="100%" height= auto alt="Second review">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="images/Reviews/Review3-min.png" width="100%" height= auto alt="Third review">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="images/Reviews/Review4-min.png" width="100%" height= auto alt="Fourth review">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="images/Reviews/Review5-min.png" width="100%" height= auto alt="Fifth review">
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>

  <div id="auctions" class="row">
    <h4><b>Meest recente veilingen</b></h4>
  </div>



  <div class="row contentWrapper">
    <?php
    try{
      $overzichtquery = "SELECT TOP 4 titel, voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE veilingGesloten = 0 ORDER BY looptijdbeginDag DESC, looptijdbeginTijdstip DESC";
      $stmt = $dbh->prepare($overzichtquery);
      $stmt->execute();
      if ($stmt->rowCount() != 0) {
        $results = $stmt->fetchAll();
        foreach( $results as $result ) {
          $voorwerpnummer = $result['voorwerpnummer'];
          echo '<div class="cardItem">
          <a href="index.php?page=veiling&id='.$result['voorwerpnummer'].'">
          <div class="card shadow-sm">
          <div class="cardImage">';

          $imagesquery = "SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = :voorwerpnummer";
          $imagesStmt = $dbh->prepare($imagesquery);
          $imagesStmt->bindParam(':voorwerpnummer', $voorwerpnummer);
          $imagesStmt->execute();
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
          <div class="cardHeader titleMarginBottom">'.
          $result['titel'].'
          </div>
          <div class="cardPrice">';

          $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = :voorwerpnummerPrijs ORDER BY bodbedrag ASC";
          $priceStmt = $dbh->prepare($pricequery);
          $priceStmt->bindParam(':voorwerpnummerPrijs', $voorwerpnummer);
          $priceStmt->execute();
          if($priceStmt->rowCount()!=0){
            $prices = $priceStmt->fetchAll();
            foreach ($prices as $price) {
              echo '&euro; '.$price['bodbedrag'];
            }
          }
          else{
            echo 'Nog geen bod';
          }
          echo '</div>
          <div class="cardFooter">
          Sluit '.$result['looptijdeindeDag'].' om '.date('H:i.s',strtotime($result['looptijdeindeTijdstip'])).'
          </div>';

          echo '
          </div>
          </div>
          </a>
          </div>';
        }
      }
    }
    catch (PDOException $e){
      echo "Er gaat iets fout met het ophalen van de artikelen: ".$e->getMessage();
    }



    ?>
  </div>

  <div class="textCenter">
    <h3><b>Populaire categorieën</b></h3>
  </div>

  <div class="row contentWrapper">

    <?php
    //Haal data uit database voor dropdown menu van rubrieken
    try{
      $data = $dbh->query("SELECT rubrieknaam FROM Rubriek WHERE parent is null ORDER BY rubrieknaam asc");
      while($row = $data->fetch()){
        echo '  <div class="popularCategoryItem">
        <a class="opacityHover" href="">
        <div class="popularCategoryBackground"><img src="images/Category/'.$row['rubrieknaam'].'.jpg" width="100%" height="100%" alt="Oldtimers"></div>
        <div class="popularCategoryText">'.$row['rubrieknaam'].'</div>
        </a>
        </div>';
      }
    }
    catch (PDOException $e){
      echo "Kan rubrieken niet laden".$e->getMessage();
    }
    ?>
    <div class="popularCategoryItem">
      <a class="opacityHover" href="">
        <div class="popularCategoryBackground"><img src="images/oldtimers.png" width="100%" height="100%" alt="Oldtimers"></div>
        <div class="popularCategoryText">Oldtimers</div>
      </a>
    </div>
    <div class="popularCategoryItem">
      <a class="opacityHover" href="">
        <div class="popularCategoryBackground"><img src="images/oldtimers.png" width="100%" height="100%" alt="Oldtimers"></div>
        <div class="popularCategoryText">Oldtimers</div>
      </a>
    </div>
  </div>
</div>
