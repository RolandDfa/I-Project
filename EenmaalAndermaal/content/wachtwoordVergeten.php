<?php
$security = "";
$securitynr = "";
?>
  <div class="pageWrapper">
    <form class="registerForm" method="post" action="">
        <h2>Wachtwoord vergeten</h2>
      <div class="row form-group"></div>
      <div class="row form-group">
        <label for="login" class="col-lg-4 control-label"><p>Vul uw gebruikersnaam in.</p></label>
        <div class="col-lg-8">
          <input type="login" class="form-control" name="login"  required >
          <div class="redText">
          </div>
        </div>
        <button type="submit" name="getSecurity" class="btn btnGreenery btn-block">Geheime vraag ophalen</button>
      </form>
      <?php
      if (isset($_POST['getSecurity'])) {
        $_SESSION['securityLogin'] = "";
        $_SESSION['securityLogin'] = cleanInput($_POST['login']);
        $validLogin = !preg_match("/^[a-zA-Z0-9]$/",$_SESSION['securityLogin']);
        if ($validLogin) {
          try{
            $sql = "SELECT Vraag, antwoordTekst FROM Gebruiker WHERE gebruikersnaam = :id";
            $query = $dbh->prepare($sql);
            if(!$query) {
              echo "oops error";
              exit();
            }
            else {
              $query->execute(array(':id' => $_SESSION['securityLogin']));
              $data = $query->fetchAll(PDO::FETCH_BOTH);
              if(empty(!$data)){
                $temp = $data[0];
                $_SESSION['securityAnswer'] = $temp['antwoordTekst'];
                $securitynr = $temp['Vraag'];
              }
              try{
                $sql2 = "SELECT tekstvraag FROM Vraag WHERE vraagnummer = :id2";
                $query2 = $dbh->prepare($sql2);
                if(!$query2) {
                  echo "oops error";
                  exit();
                }
                else {
                  $query2->execute(array(':id2' => $securitynr));
                  $data2 = $query2->fetchAll(PDO::FETCH_BOTH);
                  if(empty(!$data2)){
                    $temp = $data2[0];
                    $security = $temp['tekstvraag'];
                  }
                  else {
                    echo '<div class="redText">
                    <p>Gebruikersnaam bestaat niet.</p></div>';
                  }
                }
              } catch (PDOException $e) {
                echo "Er ging iets fout met het ophalen van de vraag.";
              }
            }
          } catch (PDOException $e) {
            echo "Er ging iets fout met het ophalen van de vraag.";
          }
        }
        else {
          echo $_SESSION['securityLogin']
          ."is geen geldig/bestaande gebruikersnaam.";
        }
      }

      ?>
      <form class="registerForm" method="post" action="">
        <div class="row form-group"></div>
        <?php echo "<h2>$security</h2>"; ?>
        <div class="row form-group">
          <label for="answer" class="col-lg-4 control-label">Vul het antwoord op de vraag in.</label>
          <div class="col-lg-8">
            <input type="text" class="form-control" name="answer" required >
            <div class="redText">
            </div>
          </div>
        </div>
        <button type="submit" name="verifyAnswer" class="btn btnGreenery btn-block" >Verstuur</button>
      </form>
    </div>
    <?php
    if (isset($_POST['verifyAnswer'])) {
      $inputAnswer = cleanInput($_POST['answer']);
      $hashedSecurityA = hash('sha256', $inputAnswer);
      if ($hashedSecurityA == $_SESSION['securityAnswer']) {
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
      echo '<div class="redText"><p>Geheime vraag foutief beantwoord. Vul uw gebruikersnaam opnieuw in.</p></div>';
      }
  }

if (isset($_POST['newPassButton'])) {
  $passwordNew = cleanInput($_POST['newPass']);
  $passwordRepeat = cleanInput($_POST['newPassRepeat']);
  if ($passwordNew != $passwordRepeat) {
    echo '<div class="redText"><p>Wachtwoord en herhaal komen niet overeen.</p></div>';;
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
        $queryInsert->execute(array($hashedWW,$_SESSION['securityLogin']));
        echo "Uw wachtwoord is succesvol aangepast.";
      }
    } catch (PDOException $e) {
      echo "Er ging iets fout met het aanpassen van het wachtwoord";
    }
  }
}
else {
}
?>
