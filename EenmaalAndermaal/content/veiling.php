<?php
// Get Artikelnummer
if (!empty($_GET['id'])) {
	$id = $_GET['id'];
} else {
	header("Location: index.php?page=home");
}

date_default_timezone_set('UTC');

try {
  $artikelquery = "SELECT * FROM Voorwerp WHERE voorwerpnummer like :search";
  $stmt = $dbh->prepare($artikelquery);
  $stmt->bindValue(':search', '%' . $id . '%', PDO::PARAM_INT);
  $stmt->execute();
  if ($stmt->rowCount() != 0) {
    $results = $stmt->fetchAll();
    foreach( $results as $result ) {
      $titel = $result['titel'];
      $verkoper = $result['verkopernaam'];
      $plaatsnaam = $result['plaatsnaam'];
      $verzendkosten = $result['verzendkosten'];
      $einddatum = date('m-d-Y',strtotime($result['looptijdeindeDag'])).' '.date('H:i:s',strtotime($result['looptijdeindeTijdstip']));
      $prijs = str_replace(".",",",$result['startprijs']);
      $beschrijving = $result['beschrijving'];
    }
  }
} catch (PDOException $e) {
  echo "Er gaat iets fout met het ophalen van het artikel: ".$e->getMessage();
}

try {
  $afbeeldingen = array();
  $afbeeldingquery = "SELECT * FROM Bestand WHERE voorwerp like :search";
  $stmt = $dbh->prepare($afbeeldingquery);
  $stmt->bindValue(':search', '%' . $id . '%', PDO::PARAM_INT);
  $stmt->execute();
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

  <div class="row">
    <div class="col-lg-3">
    <!-- <p><?=cleanInput(strip_html_tags(replaceWhitespace(($beschrijving)))) ?></p> -->

      <h4><b><?=cleanInput(strip_html_tags(replaceWhitespace(($titel)))) ?></b></h4>
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
              if ($slide == 0) {
                echo '<div class="carousel-item auctionImageBlock active">
                        <img class="d-block w-100 auctionImageBlock" src="uploaded_content/'.$afbeelding.'" alt="'.$afbeelding.'">
                      </div>';
              } else {
                echo '<div class="carousel-item auctionImageBlock">
                        <img class="d-block w-100 auctionImageBlock" src="uploaded_content/'.$afbeelding.'" alt="'.$afbeelding.'">
                      </div>';
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
            foreach($afbeeldingen as $afbeelding) {
              if ($slide == 0) {
                echo '<li data-target="#carousel-image" data-slide-to="'.$slide.'" class="active">
                        <img src="uploaded_content/'.$afbeelding.'" width="100">
                      </li>';
              } else {
                echo '<li data-target="#carousel-image" data-slide-to="'.$slide.'">
                        <img src="uploaded_content/'.$afbeelding.'" width="100">
                      </li>';
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

      <!-- <div class="imageMarginBottom">
        <iframe frameborder="0" scrolling="no" width="100%" height="300" src="uploaded_content/$afbeeldingen[0]" name="imgbox" id="imgbox"><p>iframes are not supported by your browser.</p></iframe>
      </div>

      <div class="scrollmenu imageMarginBottom">
        // foreach( $afbeeldingen as $afbeelding ) {
        //   echo '<div class="col-lg-4">
        //           <a href="uploaded_content/'.$afbeelding.'" target="imgbox">
        //             <img src="uploaded_content/'.$afbeelding.'" width="100%" height="80%" alt="Placeholder">
        //           </a>
        //         </div>';
        }
      </div> -->
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
        <div>
          U heeft nog niet geboden op dit artikel
        </div>
      </div>
      <div class="auctionCardBottom marginLeft marginRight">
        <div class="row imageMarginBottom" style="line-height: 2em;">
          <div class="col-lg-6">
            <div class="titleMarginBottom">
              Huidig bod:
            </div>
          </div>
          <div class="col-lg-6 bigtext greeneryText">
            <b>&euro; <?=$prijs?></b>
          </div>
        </div>
        <div class="greeneryText">
          <b>Snel bieden</b>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12" style="background-color: #222222; color: #ffffff; padding: 15px;">
      <h5 style="margin-bottom: 0;"><b>Artikelbeschrijving</b></h5>
    </div>
  </div>

  <div style="padding: 20px;">
    <p><?=cleanInput(strip_html_tags(replaceWhitespace(($beschrijving)))) ?></p>
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
  }
}, 100);
</script>
