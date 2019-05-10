<?php
// PHP mailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Init variables
$errorEmail = "";
$errorPHPmailer = "";
$emailSucces = "";

if (isset($_POST['sendCode'])) {

  $email = $_POST['email'];
  $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

  if (!$validEmail) {
    $errorEmail = true;
  } else {

    $_SESSION['email'] = $email;
    $_SESSION['code'] = generateRandomString(8);

    $mail = new PHPMailer(true);
    try {
      //Mail settings
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
      "<b>Use this verify code </b><br>".$_SESSION['code']."<br><br><br><b>Van bedrijven voor bedrijven.</b>";

      $mail->send();
      $emailSucces = true;
    } catch (Exception $e) {
      $errorPHPmailer = true;
    }
  }
} else {
  $errorEmail = false;
  $errorPHPmailer = false;
  $emailSucces = false;
}

?>
<div class="container">
  <form class="registerForm" method="post" action="">
    <h2>Registreren</h2>
    <div class="row form-group"></div>

    <div class="row form-group">
      <label for="email" class="col-lg-4 control-label">Emailadres *</label>
      <div class="col-lg-8">
        <input type="email" class="form-control" name="email" placeholder="example@student.han.nl" required <?php if($emailSucces){echo'value="'.$email.'" readonly';}?>>
        <div class="redText">
          <?php
          if ($errorEmail) {
            echo "$email is geen geldig emailadres";
          }
          if ($errorPHPmailer) {
            echo "Mail kan niet worden verstuurd. Mailsysteem error";
          }
          ?>
        </div>
        <?php
        if ($emailSucces) {
          echo "Er is een verificatie mail naar ". $_SESSION['email'] ." gestuurd, Vul de code hieronder in.";
        }
        ?>
      </div>
    </div>

    <button type="submit" name="sendCode" class="btn btnGreenery btn-block">Verzenden</button>
  </form>
  <form class="registerForm" method="post" action="registrerenScript.php">
    <div class="row form-group"></div>
    <h4>Code verifiëren</h4>

    <div class="row form-group">
      <label for="code" class="col-lg-4 control-label">Code *</label>
      <div class="col-lg-8">
        <input type="text" class="form-control" name="code" placeholder="E1X3A5M2P7L1E" required <?php if(!$emailSucces){echo'readonly';}?>>
        <div class="redText">
          <?php
          if (!empty($_GET['error'])) {
            echo 'De code komt niet overeen';
          }
          ?>
        </div>
      </div>
    </div>

    <button type="submit" name="verifyCode" class="btn btnGreenery btn-block" <?php if(!$emailSucces){echo'disabled';}?>>Verifiëren</button>
  </form>
</div>
