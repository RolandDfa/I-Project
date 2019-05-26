<!-- <?php if (isset($_POST['changePassword'])){
?>
<div class="pageWrapper">
    <form class="registerForm" method="post" action="">
      <div class="row form-group"></div>
      <div class="row form-group">
        <label for="currentPass" class="col-lg-4 control-label">Vul u huidige wachtwoord in.</label>
        <div class="col-lg-8">
          <input type="password" class="form-control" name="currentPass" required >
          <div class="redText">
          </div>
        </div>
      </div>
      <button type="submit" name="verifyCurrentPass" class="btn btnGreenery btn-block" >Verstuur</button>
    </form>
  </div>
  <?php
  if (isset($_POST['verifyCurrentPass'])) {
    echo "asdfghjk";
      try{
        echo "aaaaaaaaaa";
        $sql = "SELECT wachtwoord FROM Gebruiker WHERE gebruikersnaam = :id";
        $query = $dbh->prepare($sql);
        if(!$query) {
          echo "oops error";
          exit();
        }
        else {
          echo "bbbbbbbbbb";
          $query->execute(array(':id' => $_SESSION['username']));
          $data = $query->fetchAll(PDO::FETCH_BOTH);
          if(empty(!$data)){
            echo "cccccccccccc";
            $temp = $data[0];
            $password = $temp['wachtwoord'];
            $currentPass = cleanInput($_POST['answer']);
            $hashedcurrentPass = hash('sha256', $currentPass);
            if ($hashedcurrentPass == $password) {
              echo "dddddddddddd";
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
          }
        } else {
          echo "Foute invoer van het wachtwoord.";
        }
        }
      } catch (PDOException $e) {
        echo "Fout met de database 2: {$e->getMessage()} ";
      }
    }
    else {
    } -->
