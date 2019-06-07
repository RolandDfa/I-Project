<?php



// Get Artikelnummer
if (!empty($_GET['id'])) {
  $id = $_GET['id'];
  $id = (float) $id;
} else {
  header("Location: index.php?page=home");
}

date_default_timezone_set('UTC');

if(isset($_POST['bod'])){
  try{
    $bidUploadQuery = "INSERT INTO Bod values(?,?,?,?,?)";
    $bidUploadStmt = $dbh->prepare($bidUploadQuery);
    $bidUploadStmt->execute(array($id, str_replace(',', '.', $_POST['bod']), $_SESSION['username'], date('Y-m-d'), date('H:i:s')));

    $addBuyerQuery = "UPDATE Voorwerp SET kopernaam=? WHERE voorwerpnummer=?";
    $addBuyerStmt = $dbh->prepare($addBuyerQuery);
    $addBuyerStmt->execute(array($_SESSION['username'],$id));
  }
  catch (PDOException $e) {
    echo "Bod kan niet geplaatst worden. Iemand heeft het door u geboden bedrag al geboden.";
  }
}


try {
  $artikelquery = "SELECT * FROM Voorwerp WHERE voorwerpnummer = ?";
  $stmt = $dbh->prepare($artikelquery);
  $stmt->execute(array($id));
  if ($stmt->rowCount() != 0) {
    $auctionExists = true;
    $results = $stmt->fetchAll();
    foreach( $results as $result ) {
      $titel = $result['titel'];
      $verkoper = $result['verkopernaam'];
      $plaatsnaam = $result['plaatsnaam'];
      $verzendkosten = $result['verzendkosten'];
      $einddatum = date('m-d-Y',strtotime($result['looptijdeindeDag'])).' '.date('H:i:s',strtotime($result['looptijdeindeTijdstip']));
      $valuta = $result['Valuta'];
      // $valutaQuery = "SELECT ";
      $startprijs = str_replace(",",".",$result['startprijs']);
      $beschrijving = $result['beschrijving'];
      $closed = $result['veilingGesloten'];

      $sellerQuery = "SELECT mailbox FROM Gebruiker WHERE gebruikersnaam=?";
      $selletStmt =   $dbh->prepare($sellerQuery);
      $selletStmt->execute(array($verkoper));
      if ($selletStmt->rowCount() != 0) {
        $resultsSellerMail = $selletStmt->fetchAll();
        $temp = $resultsSellerMail['0'];
        $sellerMail = $temp['mailbox'];
      }


      $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = ? ORDER BY bodbedrag DESC";
      $priceStmt = $dbh->prepare($pricequery);
      $priceStmt->execute(array($id));
      if($priceStmt->rowCount()!=0){
        $prices = $priceStmt->fetchAll();
        foreach ($prices as $price) {
          $prijs = str_replace('.', ',', $price['bodbedrag']);
          $geboden = true;
        }
      }
      else{
        $prijs = $startprijs;
        $geboden = false;
      }
    }
  }else{
    echo "<style>.auction{display: none;}</style>";
    $auctionExists = false;
  }
} catch (PDOException $e) {
  echo "Er gaat iets fout met het ophalen van het artikel: ".$e->getMessage();
}

try {
  $afbeeldingen = array();
  $afbeeldingquery = "SELECT * FROM Bestand WHERE voorwerp = ?";
  $stmt = $dbh->prepare($afbeeldingquery);
  $stmt->execute(array($id));
  if ($stmt->rowCount() != 0) {
    $results = $stmt->fetchAll();
    foreach( $results as $result ) {
      $afbeeldingen[] = $result['bestandsnaam'];
    }
  }
} catch (PDOException $e) {
  echo "Er gaat iets fout met het ophalen van de afbeeldingen: ".$e->getMessage();
}
?>

<div class="pageWrapper">
  <?php if(!$auctionExists){echo'Kan veiling niet vinden. Klik <a href="index.php?page=overzicht">hier</a> om naar het veilingenoverzicht te gaan. Of klik <a href="index.php?page=home">hier</a> om naar de homepagina te gaan.';} ?>
  <div class="auction">
    <?php
    if(isset($_GET['searchedText'])){
      $searchText = cleanInput($_GET['searchedText']);
      echo '<form action="index.php?page=overzicht&searchedText='.$searchText.'" method="post" class="backbutton">';
    }else if(isset($_GET['category'])){
      $chosenCategory = cleanInput($_GET['category']);
      echo '<form action="index.php?page=overzicht&category='.$chosenCategory.'" method="post" class="backbutton">';
    }else{
      echo '<form action="index.php?page=overzicht" method="post" class="backbutton">';
    }
    echo '<button name="terug" type="submit" class="btn btn-success btn-lg mb-4">&lt; Terug naar overzicht</button>';
    echo '</form>';
    ?>
    <div class="row">
      <div class="col-lg-3">
        <h4><b><?=$titel ?></b></h4>
        <div class="cardFooter">
          <?=$id?>
        </div>
        <div class="bottomline"><!-- Line --></div>
        <div class="titleMarginBottom">
          <b>Verkoper</b>
        </div>
        <div>
          <?=$verkoper?>
        </div>
        <div>
          <?=$plaatsnaam?>
        </div>
        <div>
          <A HREF="mailto:<?=$sellerMail?>?SUBJECT=Contact"><?=$sellerMail?></A>
        </div>
        <div class="bottomline"><!-- Line --></div>
        <div class="titleMarginBottom">
          <b>Verzendkosten</b>
        </div>
        <div>
          &euro; <?=$verzendkosten?> (Nederland)
        </div>
        <div class="bottomline"><!-- Line --></div>
      </div>

      <div id="auctionImage" class="col-lg-5">
        <div id="carousel-image" class="carousel slide carousel-fade carousel-imagenails" data-ride="carousel">
          <!--Slides-->
          <div class="carousel-inner auctionImageBlock" role="listbox">
            <?php
            if (!empty($afbeeldingen)) {
              $slide = 0;
              foreach($afbeeldingen as $afbeelding) {
                $foundImage = false;
                $imagesFromUpload = scandir("./upload");
                foreach ($imagesFromUpload as $uploadImage) {
                  if($afbeelding == $uploadImage){
                    $foundImage = true;
                  }
                }
                if ($slide == 0) {


                  if($foundImage){
                    echo '<div class="carousel-item auctionImageBlock active">
                    <img class="d-block w-100 auctionImageBlock" src="./upload/'.$afbeelding.'" alt="'.$afbeelding.'">
                    </div>';
                  }else{
                    echo '<div class="carousel-item auctionImageBlock active">
                    <img class="d-block w-100 auctionImageBlock" src="../pics/'.$afbeelding.'" alt="'.$afbeelding.'">
                    </div>';
                  }


                  // echo '<div class="carousel-item auctionImageBlock active">
                  // <img class="d-block w-100 auctionImageBlock" src="../pics/'.$afbeelding.'" alt="'.$afbeelding.'">
                  // </div>';
                } else {
                  if($foundImage){
                    echo '<div class="carousel-item auctionImageBlock">
                    <img class="d-block w-100 auctionImageBlock" src="./upload/'.$afbeelding.'" alt="'.$afbeelding.'">
                    </div>';
                  }else{
                    echo '<div class="carousel-item auctionImageBlock">
                    <img class="d-block w-100 auctionImageBlock" src="../pics/'.$afbeelding.'" alt="'.$afbeelding.'">
                    </div>';
                  }

                  // echo '<div class="carousel-item auctionImageBlock">
                  // <img class="d-block w-100 auctionImageBlock" src="../pics/'.$afbeelding.'" alt="'.$afbeelding.'">
                  // </div>';
                }
                $slide++;
              }
            } else {
              echo '<div class="carousel-item auctionImageBlock active">
              <img class="d-block w-100 auctionImageBlock" src="images/image_placeholder.jpg" alt="noImage">
              </div>';
            }
            ?>
            <!--Controls-->
            <a class="carousel-control-prev" href="#carousel-image" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel-image" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
            <!--/Controls-->
          </div>
          <!--/Slides-->
          <ol class="carousel-indicators">
            <?php
            if (!empty($afbeeldingen)) {
              $slide = 0;
              // foreach($afbeeldingen as $afbeelding) {
              //   if ($slide == 0) {
              //     echo '<li data-target="#carousel-image" data-slide-to="'.$slide.'" class="active">
              //     <img src="../pics/'.$afbeelding.'" width="100">
              //     </li>';
              //   } else {
              //     echo '<li data-target="#carousel-image" data-slide-to="'.$slide.'">
              //     <img src="../pics/'.$afbeelding.'" width="100">
              //     </li>';
              //   }
              //   $slide++;
              // }

              foreach($afbeeldingen as $afbeelding) {
                $foundImage = false;
                $imagesFromUpload = scandir("./upload");
                foreach ($imagesFromUpload as $uploadImage) {
                  if($afbeelding == $uploadImage){
                    $foundImage = true;
                  }
                }
                if ($slide == 0) {


                  if($foundImage){
                    echo '<li data-target="#carousel-image" data-slide-to="'.$slide.'" class="active">
                    <img src="./upload/'.$afbeelding.'" width="100">
                    </li>';
                  }else{
                    echo '<li data-target="#carousel-image" data-slide-to="'.$slide.'" class="active">
                    <img src="../pics/'.$afbeelding.'" width="100">
                    </li>';
                  }
                } else {
                  if($foundImage){
                    echo '<li data-target="#carousel-image" data-slide-to="'.$slide.'" class="active">
                    <img src="./upload/'.$afbeelding.'" width="100">
                    </li>';
                  }else{
                    echo '<li data-target="#carousel-image" data-slide-to="'.$slide.'" class="active">
                    <img src="../pics/'.$afbeelding.'" width="100">
                    </li>';
                  }
                }
                $slide++;
              }

            } else {
              echo '<li data-target="#carousel-image" data-slide-to="0" class="active">
              <img src="images/image_placeholder.jpg" width="100">
              </li>';
            }
            ?>
          </ol>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="auctionCardTop marginLeft marginRight">
          <div class="titleMarginBottom">
            Veiling sluit in:
          </div>
          <div class="bigtext">
            <b id='time'>0d 0h 0m 0s</b>
          </div>
          <div class="bottomlineWhite"><!-- Line --></div>

        </div>
        <div class="auctionCardBottom marginLeft marginRight">
          <div class="row imageMarginBottom">
            <div class="col-lg-6">
              <div class="titleMarginBottom">
                Huidig bod:
              </div>
            </div>
            <div class="col-lg-6 bigtext greeneryText">
              <?php
              if($geboden){
                echo "<b>&euro; $prijs</b>";
              }else{
                echo "<b>Nog geen bod geplaatst</b>";
                echo "<br>Startprijs: $startprijs";
              }
              ?>

            </div>
          </div>

          <?php
          if((isset($_SESSION['username'])&& $einddatum>date('m-d-Y H:i:s')&&$_SESSION['username']!=$verkoper) && $closed==0){
            ?>
            <div id="bieden">
            <b>Snel bieden</b>
            <p>Klik op een bedrag om uw bod te plaatsen:</p>
            <?php
            $hoogsteBod = str_replace(',','.',$prijs);
            if($hoogsteBod<50 || !isset($hoogsteBod)){
              echo '<form action="" method="post" class="bidButtons">';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+0.50).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+0.50)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+1.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+1.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+5.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+5.00)).'</button>';
              echo '</form>';
            }else if($hoogsteBod<49.99){
              echo '<form action="" method="post" class="bidButtons">';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+1.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+1.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+5.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+5.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+10.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+10.00)).'</button>';
              echo '</form>';
            }else if($hoogsteBod<499.99){
              echo '<form action="" method="post" class="bidButtons">';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+5.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+5.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+10.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+10.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+20.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+20.00)).'</button>';
              echo '</form>';
            }else if($hoogsteBod<4999.99){
              echo '<form action="" method="post" class="bidButtons">';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+10.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+10.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+20.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+20.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+50.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+50.00)).'</button>';
              echo '</form>';
            }else if($hoogsteBod<99999.99){
              echo '<form action="" method="post" class="bidButtons">';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+50.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+50.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+100.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+100.00)).'</button>';
              echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+150.00).'">&euro; '.str_replace('.', ',', ((float)$hoogsteBod+150.00)).'</button>';
              echo '</form>';
            }
            ?>
            <br><br>
            <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info btn-lg manualBidButton" data-toggle="modal" data-target="#myModal">Handmatig bieden</button>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Bieden</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">


                    <?php
                    try{
                      $pricequery = "SELECT TOP 3 bodbedrag FROM Bod WHERE voorwerp = ? ORDER BY bodbedrag DESC";
                      $priceStmt = $dbh->prepare($pricequery);
                      $priceStmt->execute(array($id));
                      if($priceStmt->rowCount()!=0){
                        echo '<h5>Biedingen:</h5><br><ol>';
                        $prices = $priceStmt->fetchAll();
                        foreach ($prices as $price) {
                          echo  '<li>'.str_replace('.', ',', $price['bodbedrag']).'</li>';
                        }
                      }
                      else{
                        echo 'Nog geen bod';
                      }
                    }catch (PDOException $e) {
                      echo "Kan bodbedragen niet ophalen. Laad pagina opnieuw.";
                    }
                    ?>
                  </ol>
                  <h5>Plaats snel een bod:</h5>
                  <?php
                  try{
                    $pricequery = "SELECT TOP 1 bodbedrag FROM Bod WHERE voorwerp = ? ORDER BY bodbedrag DESC";
                    $priceStmt = $dbh->prepare($pricequery);
                    $priceStmt->execute(array($id));
                    if($priceStmt->rowCount()!=0){
                      $prices = $priceStmt->fetchAll();
                      $hoogsteBod = '';
                      foreach ($prices as $price) {
                        $hoogsteBod = $price['bodbedrag'];
                      }
                    }
                  }
                  catch (PDOException $e) {
                    echo "Kan hoogste bod niet ophalen. Laad pagina opnieuw.";
                  }
                  if($hoogsteBod<50 || !isset($hoogsteBod)){
                    echo '<form action="" method="post" class="bidButtons">';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+0.50).'">&euro; '.((float)$hoogsteBod+0.50).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+1.00).'">&euro; '.((float)$hoogsteBod+1.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+5.00).'">&euro; '.((float)$hoogsteBod+5.00).'</button>';
                    echo '</form>';
                    $minBod = ((float)$hoogsteBod+0.50);
                  }else if($hoogsteBod<500){
                    echo '<form action="" method="post" class="bidButtons">';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+1.00).'">&euro; '.((float)$hoogsteBod+1.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+5.00).'">&euro; '.((float)$hoogsteBod+5.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+10.00).'">&euro; '.((float)$hoogsteBod+10.00).'</button>';
                    echo '</form>';
                    $minBod = ((float)$hoogsteBod+1.00);
                  }else if($hoogsteBod<500){
                    echo '<form action="" method="post" class="bidButtons">';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+5.00).'">&euro; '.((float)$hoogsteBod+5.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+10.00).'">&euro; '.((float)$hoogsteBod+10.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+20.00).'">&euro; '.((float)$hoogsteBod+20.00).'</button>';
                    echo '</form>';
                    $minBod = ((float)$hoogsteBod+5.00);
                  }else if($hoogsteBod<5000){
                    echo '<form action="" method="post" class="bidButtons">';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+10.00).'">&euro; '.((float)$hoogsteBod+10.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+20.00).'">&euro; '.((float)$hoogsteBod+20.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+50.00).'">&euro; '.((float)$hoogsteBod+50.00).'</button>';
                    echo '</form>';
                    $minBod = ((float)$hoogsteBod+10.00);
                  }else if($hoogsteBod<1000000){
                    echo '<form action="" method="post" class="bidButtons">';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+50.00).'">&euro; '.((float)$hoogsteBod+50.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+100.00).'">&euro; '.((float)$hoogsteBod+100.00).'</button>';
                    echo '<button name="bod" type="submit" value="'.((float)$hoogsteBod+150.00).'">&euro; '.((float)$hoogsteBod+150.00).'</button>';
                    echo '</form>';
                    $minBod = ((float)$hoogsteBod+50.00);
                  }
                  ?>
                  <br>
                  <form action="" method="post">
                    <h5><label for="bod">Plaats handmatig uw bod</label></h5>
                    <input type="number" min="<?= $minBod ?>" max="99999.99" step="0.01" id="bod" name="bod" placeholder="bijv. 12">
                    <input type="submit" value="Bied">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
</div>
              <?php

            }
            ?>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 auctionHeader">
      <h5><b>Artikelbeschrijving</b></h5>
    </div>
  </div>

  <div class="paddingTop">
    <p><?=$beschrijving?></p>
  </div>
</div>
</div>

<script>
// Set the date we're counting down to
var countDownDate = new Date("<?php echo $einddatum; ?>").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Output the result in an element with id="time"
  document.getElementById("time").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is over, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("time").innerHTML = "VEILING GESLOTEN";
    document.getElementById("bieden").style.display = "none";
  }
}, 100);
</script>
