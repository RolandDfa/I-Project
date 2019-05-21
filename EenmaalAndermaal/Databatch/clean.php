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
    $cleanStmt->execute(array(cleanInput(strip_html_tags(replaceWhitespace($dirtyRow['titel'])), cleanInput(strip_html_tags(replaceWhitespace($dirtyRow['beschrijving']))), $dirtyRow['voorwerpnummer']));
    echo $dirtyRow['titel'];
  }
}
?>
