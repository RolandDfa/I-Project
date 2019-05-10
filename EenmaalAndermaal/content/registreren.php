<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

?>
<div class="container">
  <form class="registerForm" method="post" action="">
    <h2>Registreren</h2>
    <div class="row form-group"></div>
    <div class="row form-group">
      <label for="firstName" class="col-lg-4 alignRight control-label">Emailadres *</label>
      <div class="col-lg-8">
        <input type="email" class="form-control" name="email" placeholder="example@student.han.nl" required>
      </div>
    </div>
    <button type="submit" name="sendCode" class="btn btn-primary btn-block">Verzenden</button>
  </form>
  <form class="registerForm" method="post" action="registrerenScript.php">
    <div class="row form-group"></div>
    <div class="row form-group">
      <label for="firstName" class="col-lg-4 alignRight control-label">Code *</label>
      <div class="col-lg-8">
        <input type="text" class="form-control" name="code" placeholder="E1X3A5M2P7L1E" required>
        <div style="color: red;">
          <?php
          if (!empty($_GET['error'])) {
            echo 'De code komt niet overeen';
          }
          ?>
        </div>
      </div>
    </div>
    <button type="submit" name="verifyCode" class="btn btn-primary btn-block">Verifiëren</button>
  </form>
</div>

<?php
if(isset($_POST['sendCode'])){

  $_SESSION['email'] = $_POST['email'];
  $validEmail = filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL);

  if (empty($_SESSION['email'])) {
    echo("Vul het veld in.");
  } elseif(!$validEmail) {
    echo("$email is geen emailadres.");
  } elseif($validEmail) {

    $_SESSION['code'] = generateRandomString(8);

    $mail = new PHPMailer(true);
    try {
      //Server settings
      //$mail->SMTPDebug = 1;                                        // Enable verbose debug output
      $mail->isSMTP();                                               // Set mailer to use SMTP
      $mail->Host       = 'smtp.gmail.com';                          // Specify main and backup SMTP servers
      $mail->SMTPAuth   = true;                                      // Enable SMTP authentication
      $mail->Username   = 'info.EenmaalAndermaal41@gmail.com';       // SMTP username
      $mail->Password   = 'IprojectGroep41';                         // SMTP password
      $mail->SMTPSecure = 'tls';                                     // Enable TLS encryption, `ssl` also accepted
      $mail->Port       = 587;                                       // TCP port to connect to

      $mail->setFrom('info.EenmaalAndermaal41@gmail.com');
      $mail ->addAddress($_SESSION['email']);

      $mail->isHTML(true);
      $mail->addAttachment('images/EenmaalAndermaalLogo.png');
      $mail->Subject = '[EenmaalAndermaal] Please verify your email address.';
      $mail->Body    =
      "<b>your verify code </b><br>".$_SESSION['code']."<br><br><br><b>Van bedrijven voor bedrijven.</b>";

      $mail->send();
      echo "Er is een verificatie mail naar ". $_SESSION['email'] ."gestuurd, Vul de code in. ";
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }
}
