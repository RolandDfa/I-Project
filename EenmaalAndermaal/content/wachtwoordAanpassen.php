<?php
if (isset($_SESSION['username'])){
  $hashedcurrentPass = "";
    if (!isset($_POST['changePass'])) {
  ?>
  <form class="registerForm" method="post" action="">
    <div class="row form-group"></div>
    <div class="row form-group">
      <label for="currentPass" class="col-lg-4 control-label">Vul uw huidige wachtwoord in.</label>
      <div class="col-lg-8">
        <input type="password" class="form-control" name="currentPass" required >
      </div>
    </div>
    <button type="submit" name="changePass" class="btn btnGreenery btn-block" >Verstuur</button>
  </form>
  <?php
}
  if (isset($_POST['changePass'])) {
    try{
      $sql = "SELECT wachtwoord FROM Gebruiker WHERE gebruikersnaam = :id";
      $query = $dbh->prepare($sql);
      if(!$query) {
        echo "oops error";
        exit();
      }
      else {
        $query->execute(array(':id' => $_SESSION['username']));
        $data = $query->fetchAll(PDO::FETCH_BOTH);
        if(empty(!$data)){
          $temp = $data[0];
          $password = $temp['wachtwoord'];
          $currentPass = cleanInput($_POST['currentPass']);
          $hashedcurrentPass = hash('sha256', $currentPass);
          if ($hashedcurrentPass == $password) {
            ?>
            <form class="registerForm" method="post" action="">
              <div class="row form-group"></div>
              <h4>Vul nieuw wachtwoord in.</h4>
              <div class="row form-group">
                <label for="newPass" class="col-lg-4 control-label">Wachtwoord:</label>
                <div class="col-lg-8">
                  <input type="password" class="form-control" name="newPass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" title="vul minimaal een kleine letter, een cijfer en een hoofd letter in. het wachtwoord moet tussen 8 en 15 lang zijn." required >
                </div>
              </div>
              <div class="row form-group">
                <label for="newPass" class="col-lg-4 control-label">Herhaal wachtwoord:</label>
                <div class="col-lg-8">
                  <input type="password" class="form-control" name="newPassRepeat" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}" title="vul minimaal een kleine letter, een cijfer en een hoofd letter in. het wachtwoord moet tussen 8 en 15 lang zijn." required >
                </div>
              </div>
              <button type="submit" name="newPassButton" class="btn btnGreenery btn-block" >Verander wachtwoord</button>
            </form>
          </div>
          <?php
        } else {
          echo '<div class="redText"><p>Dit is niet uw huidige wachtwoord.</p></div>';
        }
      }
    }
  } catch (PDOException $e) {
    echo "Er ging iets fout met het ophalen van gegevens";
  }
}
else {
}
if (isset($_POST['newPassButton'])) {
  $passwordNew = cleanInput($_POST['newPass']);
  $passwordRepeat = cleanInput($_POST['newPassRepeat']);
  if ($passwordNew != $passwordRepeat) {
    echo '<div class="redText"><p>De velden wachtwoord en herhaal wachtwoord komen niet overeen.</p></div>';;
  }
  else {
    $hashedWW = hash('sha256', $passwordNew);
    try{
      $sqlUpdate = "UPDATE Gebruiker SET wachtwoord=? WHERE gebruikersnaam=?";
      $queryInsert = $dbh->prepare($sqlUpdate);
      if(!$queryInsert) {
        echo "oops error";
        exit();
      }
      else {
        $queryInsert->execute(array($hashedWW,$_SESSION['username']));
        // Unset session var
        $_SESSION = array();
        // Destroy session
        session_destroy();
        echo '<p>Wachtwoord succesvol aangepast, klik <a href="index.php?page=inloggen">Hier</a> om opnieuw in te loggen</a>.</p>';
      }
    } catch (PDOException $e) {
      echo "Er ging iets fout met plaatsen van gegevens";
    }
  }
}else {
}
} else {
  echo '<p> Klik <a href="index.php?page=inloggen">Hier</a> om in te loggen</a>.</p>';
}
?>
