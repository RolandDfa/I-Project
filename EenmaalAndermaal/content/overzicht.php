
<?php
if(isset($_POST['searchText'])){
  $searchText = cleanInput($_POST['searchText']);
}else{
  $searchText = "";
}
$gekozenCategorie;
// switch($categorie) {
//   case 'home':
//   require('content/home.php');
//   break;
//   default:
//   require('content/home.php');
// }

?>
<div class="pageWrapper">
  <?php
  if(!isset($searchText)){
    echo "<p>Gevonden resultaten voor: $searchText</p>";
  }
  ?>
  <div class="row contentWrapper">

    <?php
    if(isset($searchText)){
      try{
        $data = $dbh->query("SELECT titel, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE veilingGesloten = 0 and titel like '%$searchText%'");
        while($row = $data->fetch()){
          if($row['titel']==""){
            echo "Geen resultaten voor de zoekopdracht: ".$searchText;
          }else{
            echo '<div class="cardItem">
            <a href="">
            <div class="card shadow-sm">
            <div class="cardImage">
            <img class="rounded-top" src="images/fiets.jpg" width="100%" height="220" alt="'.$row['titel'].'">
            </div>
            <div class="cardTitle">
            <div class="cardHeader">'.
            $row['titel'].'
            </div>
            <div class="cardFooter">
            Sluit '.$row['looptijdeindeDag'].' om '.$row['looptijdeindeTijdstip'].'
            </div>
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
    }
    else{
      try{
        $data = $dbh->query("SELECT titel, looptijdeindeDag, looptijdeindeTijdstip FROM Voorwerp WHERE veilingGesloten = 0");
        while($row = $data->fetch()){
          if($row['titel']==""){
            echo "Geen resultaten voor de zoekopdracht: ".$searchText;
          }else{
            echo '<div class="cardItem">
            <a href="">
            <div class="card shadow-sm">
            <div class="cardImage">
            <img class="rounded-top" src="images/fiets.jpg" width="100%" height="220" alt="'.$row['titel'].'">
            </div>
            <div class="cardTitle">
            <div class="cardHeader">'.
            $row['titel'].'
            </div>
            <div class="cardFooter">
            Sluit zaterdag vanaf 20:00
            </div>
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
    }
    ?>
  </div>
</div>
