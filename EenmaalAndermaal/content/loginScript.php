<?php
session_start();

// Database connection
require('../connectie.php');
require('../functions/functions.php');

// Post inloggen
if(isset($_POST['login'])) {
  $username = cleanInput($_POST['username']);
  $password = cleanInput($_POST['password']);

  // Username and password check
  try {
    $loginquery = "SELECT * FROM Gebruiker WHERE gebruikersnaam = ? COLLATE SQL_Latin1_General_CP1_CS_AS AND valid = 1";
    $loginStmt = $dbh->prepare($loginquery);
    $loginStmt->execute(array($username));
    if($loginStmt->rowCount()!=0){
      $usernames = $loginStmt->fetchAll();
      foreach ($usernames as $users) {
        $password = hash('sha256', $password);
        // If passwords match
        if ($password == $users['wachtwoord']) {
          // Create session variables
          $_SESSION['username'] = $username;
          $_SESSION['userstate'] = $users['gebruikersStatus'];
          // Login succesvol
          header("Location: ../index.php?page=home");
        } else {
          // Passwords don't match
          header("Location: ../index.php?page=inloggen&error=onjuist");
        }
      }
    }
    else {
      // No user found
      header("Location: ../index.php?page=inloggen&error=onjuist");
    }
  } catch (PDOExeption $e) {
    die ("Fout met de database: {$e->getMessage()} ");
  }
}
?>
