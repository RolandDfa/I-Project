<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

set_time_limit(60);
if(isset($_GET['searchedText'])){
  $searchText = cleanInput($_GET['searchedText']);
}else{
  if(isset($_POST['searchText'])){
    $searchText = cleanInput($_POST['searchText']);

  }else{
    $searchText = "";
  }
}

try {
  $auctionsQuery = "SELECT titel, voorwerpnummer, looptijdeindeDag, looptijdeindeTijdstip, verkopernaam, kopernaam FROM Voorwerp WHERE veilingGesloten = 0";
  $auctionsstmt = $dbh->prepare($auctionsQuery);
  $auctionsstmt->execute();
  if ($auctionsstmt->rowCount() != 0) {
    $results = $auctionsstmt->fetchAll();
    foreach( $results as $result ) {
      $sluitdatum = date('m-d-Y',strtotime($result['looptijdeindeDag'])).' '.date('H:i:s',strtotime($result['looptijdeindeTijdstip']));
      $nummer = $result['voorwerpnummer'];
      $sellerName = $result['verkopernaam'];
      $buyerName = $result['kopernaam'];
      $aucTitle = $result['titel'];
      if(date('m-d-Y H:i:s')>=$sluitdatum){
        try{
          //send mail toy winning buyer
          if (!empty($buyerName)) {
            $sql = "SELECT mailbox FROM Gebruiker WHERE gebruikersnaam = :id";
            $query = $dbh->prepare($sql);
            if(!$query) {
              echo "oops error";
              exit();
            }
            else {
              $query->execute(array(':id' => $buyerName));
              $data = $query->fetchAll(PDO::FETCH_BOTH);
            }
            $temp = $data['0'];
            $emailBuyer = $temp['mailbox'];
            $mail = new PHPMailer(true);
            try {
              //Mail settings
              $mail->isSMTP();                                               // Set mailer to use SMTP
              $mail->Host       = 'smtp.gmail.com';                          // Specify main and backup SMTP servers
              $mail->SMTPAuth   = true;                                      // Enable SMTP authentication
              $mail->Username   = 'info.EenmaalAndermaal41@gmail.com';       // SMTP username
              $mail->Password   = 'IprojectGroep41';                         // SMTP password
              $mail->SMTPSecure = 'tls';                                     // Enable TLS encryption, `ssl` also accepted
              $mail->Port       = 587;                                       // TCP port to connect to

              $mail->setFrom('info.EenmaalAndermaal41@gmail.com');
              $mail ->addAddress($emailBuyer);

              $mail->isHTML(true);
              $mail->addAttachment('images/EenmaalAndermaalLogo.png');
              $mail->Subject = '[EenmaalAndermaal] Gewonnen veiling!.';
              $mail->Body    =
              "<b>Gefeliciteerd u heeft de veiling [".$aucTitle."] gewonnen.</b>";

              $mail->send();
            } catch (Exception $e) {
              echo "Er gaat iets fout met het sturen van de sluitingsmelding naar de winnaar.".$e->getMessage();
            }
          }
          //send mail seller
          $sql = "SELECT mailbox FROM Gebruiker WHERE gebruikersnaam = :id";
          $query = $dbh->prepare($sql);
          if(!$query) {
            echo "oops error";
            exit();
          }
          else {
            $query->execute(array(':id' => $sellerName));
            $data = $query->fetchAll(PDO::FETCH_BOTH);
          }
          $temp = $data['0'];
          $emailSeller = $temp['mailbox'];
          $mail = new PHPMailer(true);
          try {
            //Mail settings
            $mail->isSMTP();                                               // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';                          // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                      // Enable SMTP authentication
            $mail->Username   = 'info.EenmaalAndermaal41@gmail.com';       // SMTP username
            $mail->Password   = 'IprojectGroep41';                         // SMTP password
            $mail->SMTPSecure = 'tls';                                     // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                       // TCP port to connect to

            $mail->setFrom('info.EenmaalAndermaal41@gmail.com');
            $mail ->addAddress($emailSeller);

            $mail->isHTML(true);
            $mail->addAttachment('images/EenmaalAndermaalLogo.png');
            $mail->Subject = '[EenmaalAndermaal] Uw veilig is gesloten.';
            $mail->Body    =
            "<b>Uw veiling [".$aucTitle."] </b><p> is automatisch gesloten omdat de veiling de maximale tijd heeft bereikt.</p>";

            $mail->send();
          } catch (Exception $e) {
            echo "Er gaat iets fout met het sturen van de sluitingsmelding naar de verkoper.".$e->getMessage();
          }
        } catch (PDOException $e) {
          echo "Er gaat iets fout met het sturen van de sluitingsmelding tijdens het ophalen van de contact gegevens.".$e->getMessage();
        }
          $auctionCloseQuery = "UPDATE Voorwerp SET veilingGesloten = 1 WHERE voorwerpnummer = ?";
          $auctionCloseStmt = $dbh->prepare($auctionCloseQuery);
          $auctionCloseStmt->execute(array($nummer));
      }
    }
  }
} catch (PDOException $e) {
  echo "Er gaat iets fout met het sluiten van veilingen".$e->getMessage();
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
          echo '<div class="cardItem">';
          if(isset($_POST['searchText'])){
            echo '  <a href="index.php?page=veiling&searchedText='.$searchText.'&id='.$result['voorwerpnummer'].'">';
          }else if(isset($_GET['searchedText'])){
            $searchText = cleanInput($_GET['searchedText']);
            echo '  <a href="index.php?page=veiling&searchedText='.$searchText.'&id='.$result['voorwerpnummer'].'">';
          }else if(isset($_GET['category'])){
            $chosenCategory = cleanInput($_GET['category']);
            echo '  <a href="index.php?page=veiling&category='.$categoryNumber.'&id='.$result['voorwerpnummer'].'">';
          }else{
            echo '  <a href="index.php?page=veiling&id='.$result['voorwerpnummer'].'">';
          }
          //<a href="index.php?page=veiling&searchedText='.$searchText.'&id='.$result['voorwerpnummer'].'&category='.$categoryNumber.'">
          echo '<div class="card shadow-sm">
          <div class="cardImage">';

          try{
            $imagesquery = "SELECT TOP 1 bestandsnaam FROM Bestand WHERE Voorwerp = ?";
            $imagesStmt = $dbh->prepare($imagesquery);
            $imagesStmt->execute(array($voorwerpnummer));
            if($imagesStmt->rowCount()!=0){
              $foundImage = false;
              $imageToShow = '';
              $images = $imagesStmt->fetchAll();
              foreach ($images as $image) {
                $imagesFromUpload = scandir("./upload");
                foreach ($imagesFromUpload as $uploadImage) {
                  if($image['bestandsnaam'] == $uploadImage){
                    $foundImage = true;
                    $imageToShow = $uploadImage;
                  }
                }
                if($foundImage){
                  echo '<img class="rounded-top" src="./upload/'.$imageToShow.'" width="100%" height="220" alt="'.$result['titel'].'">';
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
          <div class="cardFooter">';
          echo 'Sluit '.date_format(date_create($result['looptijdeindeDag']), "d-m-Y").' om '.date('H:i.s',strtotime($result['looptijdeindeTijdstip'])).' uur <br>';
          $sluitdatum = date('m-d-Y',strtotime($result['looptijdeindeDag'])).' '.date('H:i:s',strtotime($result['looptijdeindeTijdstip']));
          if(date('m-d-Y H:i:s')<$sluitdatum){echo 'Veiling is nog niet gesloten';}else{echo 'Veiling is gesloten';};
          echo '</div>';
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
