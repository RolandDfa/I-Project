<?php
session_start();

// Database connection
require('../connectie.php');

//Post inloggen
if(isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  //Username and password check
  try {
    $sql = "SELECT * FROM Gebruiker WHERE gebruikersnaam = '$username'";
    $result = $dbh->query($sql);

    // If record ^
    if (($row = $result->fetch()) > 0) {
      $password = hash('sha256', $password);
      // If passwords match
      if ($password == $row['wachtwoord']) {
        // Create session variables
        $_SESSION['username'] = $username;
        $_SESSION['userstate'] = $row['gebruikersStatus'];
        // Login succesvol
        header("Location: ../index.php?page=home");
      } else {
        // Passwords don't match
        echo 'De gebruikersnaam of het wachtwoord is onjuist.';
      }
    } else {
      echo 'De gebruikersnaam of het wachtwoord is onjuist.';
    }
  } catch (PDOExeption $e) {
    die ("Fout met de database: {$e->getMessage()} ");
  }
}
?>
