<?php
if ($_SESSION['accountRecovery']) {
?>
<div class="container loginContainer">
  <div class="row marginThirty">
    <div class="col-md-6 loginForm">
      <h3>Gebruikersnaam</h3>
      <form action="content/accountRecoveryScript.php" method="post">
        <div class="form-group">
          <input type="text" class="form-control" name="usernameAccRecovery" placeholder="Gebruikersnaam *" required />
        </div>
        <div class="redText">
          <?php
          if (!empty($_GET['error'])) {
            echo 'het account met deze gebruikersnaam bestaat niet of is niet mogelijk om te herstellen.';
          }
          ?>
        </div>
        <div class="form-group">
          <input type="submit" class="btnSubmit" name="accountRecoveryButton1" value="Ga verder" />
        </div>
      </form>
    </div>
  </div>
</div>
<?php
}
?>
