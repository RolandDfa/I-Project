<div class="col-lg-12 alignCenter">
  <?php
  //Haal data uit database voor dropdown menu van rubrieken
  try{
    $data = $dbh->query("SELECT TOP 8 rubrieknaam, rubrieknummer FROM Rubriek WHERE parent = -1 ORDER BY rubrieknaam asc");
    while($row = $data->fetch()){
      echo '<a class="categorieMenuItem" href="index.php?page=overzicht&category='.$row['rubrieknummer'].'">'.$row['rubrieknaam'].'</a>';
    }
  }
  catch (PDOException $e){
    echo "Kan rubrieken niet laden".$e->getMessage();
  }
  ?>
</div>
