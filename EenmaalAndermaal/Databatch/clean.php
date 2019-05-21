<?php
require('../connectie.php');
require('../functions/functions.php');

$dirtyDataQuery = "SELECT titel, beschrijving, voorwerpnummer FROM voorwerp";
$dirtyStmt = $dbh->prepare($dirtyDataQuery);
$dirtyStmt->execute();
if($dirtyStmt->rowCount()!=0){
  $dirtyRows = $dirtyStmt->fetchAll();
  foreach ($dirtyRows as $dirtyRow) {


    $cleanDataQuery = "UPDATE voorwerp SET titel = ?, beschrijving = ? where voorwerpnummer = ?";
    $cleanStmt = $dbh->prepare($cleanDataQuery);
    $cleanStmt->execute(array());
    if($cleanStmt->rowCount()!=0){
      $cleanRows = $cleanStmt->fetchAll();
      foreach ($cleanRows as $cleanRow) {

      }
    }

  }
}
?>
