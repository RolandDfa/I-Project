<div class="pageWrapper">

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
      $data = $dbh->query("SELECT TOP 8 titel, voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE veilingGesloten = 0 ORDER BY looptijdbeginDag, looptijdbeginTijdstip");
      if($data->rowCount()){
        while($row = $data->fetch()){
          $voorwerpnummer = $row['voorwerpnummer'];
          echo '<div class="cardItem">
          <a href="index.php?page=overzicht&id='.hash('sha256', $row['voorwerpnummer']).'">
          <div class="card shadow-sm">
          <div class="cardImage">';
          $imageData = $dbh->query("SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = $voorwerpnummer");
          if($imageData->rowCount()){
            while($image = $imageData->fetch()){
              echo '<img class="rounded-top" src="uploaded_content/'.$image['bestandsnaam'].'" width="100%" height="220" alt="'.$row['titel'].'">';
            }
          }else{
            echo '<img class="rounded-top" src="images/image_placeholder.jpg" width="100%" height="220" alt="'.$row['titel'].'">';
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
</div>
