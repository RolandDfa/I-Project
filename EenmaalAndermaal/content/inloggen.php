<div class="container loginContainer">
  <div class="row">
    <div class="col-md-6 loginForm">
      <h3>Inloggen</h3>
      <form action="content/loginScript.php" method="post">
        <div class="form-group">
          <input type="text" class="form-control" name="username" placeholder="Gebruikersnaam *" required />
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Wachtwoord *" required />
          <div style="color: red;">
            <?php
            if (!empty($_GET['error'])) {
              echo 'Gebruikersnaam of wachtwoord is onjuist.';
            }
            ?>
          </div>
        </div>
        <div class="form-group">
          <input type="checkbox" name="blijfingelogd" value="ja"> Ingelogd blijven
        </div>
        <div class="form-group">
          <input type="submit" class="btnSubmit" name="login" value="Log in" />
        </div>
      </form>
      <div class="form-group">
        <a href="" class="btnForgetPwd">Wachtwoord vergeten?</a>
      </div>
    </div>
    <div class="col-md-6 registerenContainer">
      <div class="loginLogo">
        <i class="fas fa-user-tie"></i>
      </div>
      <h3>Registreren</h3>
      <div class="form-group">
        <input type="button" class="btnSubmit" value="Maak account aan" onclick="location.href='index.php?page=registreren';" />
      </div>&nbsp
      <div class="form-group">
        <i class="fas fa-check-circle colorGray"></i> Kopen van artikelen
      </div>
      <div class="form-group">
        <i class="fas fa-check-circle colorGray"></i> Contact met verkopers
      </div>
      <div class="form-group">
        <i class="fas fa-check-circle colorGray"></i> Schrijf reviews
      </div>
    </div>
  </div>
</div>
