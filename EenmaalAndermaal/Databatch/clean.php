<?php
require('../connectie.php');
require('../functions/functions.php');

$dirtyDataQuery = "SELECT titel, beschrijving, voorwerpnummer FROM voorwerp";
$dirtyStmt = $dbh->prepare($dirtyDataQuery);
$dirtyStmt->execute();
if($dirtyStmt->rowCount()!=0){
  $dirtyRows = $dirtyStmt->fetchAll();
  foreach ($dirtyRows as $dirtyRow) {
    echo '<h1> Dirty: '.$dirtyRow['titel'].'</h2>';
    echo '<p> Dirty: '.$dirtyRow['beschrijving'].'</p>';
    $cleanTitle = cleanInput(strip_html_tags(replaceWhitespace($dirtyRow['titel'])));
    $cleanDescription =  cleanInput(strip_html_tags(replaceWhitespace($dirtyRow['beschrijving'])));
    echo '<h1> Clean: '.$cleanTitle.'</h1>';
    echo '<p> Clean: '.$cleanDescription.'</p>';
    echo '<br><br><br><br><br><br><br><br><br><br><br>';
    $cleanDataQuery = "UPDATE voorwerp SET titel = ?, beschrijving = ? where voorwerpnummer = ?";
    $cleanStmt = $dbh->prepare($cleanDataQuery);
    $cleanStmt->execute(array(cleanInput(strip_html_tags(replaceWhitespace($dirtyRow['titel']))), cleanInput(strip_html_tags(replaceWhitespace($dirtyRow['beschrijving']))), $dirtyRow['voorwerpnummer']));
  }
}
?>
