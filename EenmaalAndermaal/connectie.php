<?php
// Database connection
// $hostname = "mssql.iproject.icasites.nl";
// $dbname = "iproject41";
// $username = "iproject41";
// $pw = "V19UFzEQGJ";

$hostname = "localhost";
$dbname = "EenmaalAndermaal";
$username = "";
$pw = "";

try {
  $dbh = new PDO("sqlsrv:Server=$hostname;Database=$dbname;ConnectionPooling=0", "$username", "$pw");
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die ("Fout met de database: {$e->getMessage()} ");
}
?>
