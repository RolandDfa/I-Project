<div class="pageWrapper">

<?PHP
if (!empty($_GET['status'])) {
echo '<img src="images/check.png" alt="registratie succesvol" height="100" width="100">';
echo '<p>U heeft het account succesvol hersteld!</p>';
echo '<p> Klik <a href="index.php?page=inloggen">Hier</a> om in te loggen</a>.</p>';
}else {
echo '<img src="images/check.png" alt="registratie succesvol" height="100" width="100">';
echo '<p>U bent succesvol geregistreerd!</p>';
echo '<p> Klik <a href="index.php?page=inloggen">Hier</a> om in te loggen</a>.</p>';
}
?>

</div>
