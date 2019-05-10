<?php
$name ="";
$lastname="";
$birthDate1="";
$adress="";
$zipcode="";
$city="";
$country="";
$telnr="";
$telnr2="";
$kvknr="";
$username="";
$password="";
$passwordRepeat="";
$securityQ="";
$securityA="";
$_SESSION['verifySucces'] = false;
// Database connection
$hostname = "mssql.iproject.icasites.nl";
$dbname = "iproject41";
$username = "iproject41";
$pw = "V19UFzEQGJ";

// $hostname = "localhost";
// $dbname = "EenmaalAndermaal";
// $username = "";
// $pw = "";

try {
  $dbh = new PDO("sqlsrv:Server=$hostname;Database=$dbname;ConnectionPooling=0", "$username", "$pw");
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die ("Fout met de database: {$e->getMessage()} ");
}
?>
