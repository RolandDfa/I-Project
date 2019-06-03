  <?php
  try{
    $categoryquery = "SELECT * from Rubriek where parent = -1 order by rubrieknaam asc";
    $categorystmt = $dbh->prepare($categoryquery);
    $categorystmt->execute();
    if($categorystmt->rowCount()!=0){
      $categorys = $categorystmt->fetchAll();

      foreach ($categorys as $category) {
        echo '<div class="topicContainer">';
        echo '<a href=index.php?page=overzicht&category='.$category['rubrieknummer'].'"><h1 class="greeneryText">'.$category['rubrieknaam'].'</h1></a>';
        echo '<div class="subtopicContainer d-flex flex-row flex-wrap">';
        try{
          $subcategoryquery = "SELECT * from Rubriek where parent = ? order by rubrieknaam asc";
          $subcategorystmt = $dbh->prepare($subcategoryquery);
          $subcategorystmt->execute(array($category['rubrieknummer']));
          if($subcategorystmt->rowCount()!=0){
            $subcategorys = $subcategorystmt->fetchAll();
            foreach ($subcategorys as $subcategory) {
              echo '<a href=index.php?page=overzicht&category='.$subcategory['rubrieknummer'].'"><h2>'.$subcategory['rubrieknaam'].'</h2></a>';
            }
          }else{
            echo '';
          }
        }catch (PDOException $e){
          echo "Er gaat iets fout met het ophalen van de subcategorieën";
        }
        echo '</div>';
        echo '</div>';
        echo '<br>';
      }

    }else{
      echo '';
    }
  }catch (PDOException $e){
    echo "Er gaat iets fout met het ophalen van categorieën";
  }
  ?>
