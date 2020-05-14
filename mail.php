<?php
include("sjekkomloggetinn.php");
include("start2.html");
header('Content-Type: text/html; charset=utf-8');
?>

  <div class="container3">
    <h1>Her kan du sende mail til en annen person!</h1>
<form action="" method="POST" id="sendmailskjema" name="sendmailskjema" class="sendmailskjema">

  <div class='rad'>
    <div class='col-25'>
      <label for='til'>Til:</label>
    </div>
      <div class='col-75'>
        <select name='eposttil' id='eposttil' autofocus>
        <option value=''>Velg epost her...</option>
    <?php
          include("kobletil.php");

          $sqlSetning="SELECT ePost, brukerNavn FROM bruker ORDER BY ePost;";
          $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

          $antallRader=mysqli_num_rows($sqlResultat);

          for ($r=1;$r<=$antallRader;$r++)
          {
          $rad=mysqli_fetch_array($sqlResultat);
          $eposttil = $rad['ePost'];
          $brukernavntil = $rad['brukerNavn'];
          print("<option value='$eposttil'>$eposttil : $brukernavntil</option>");
          }
          print("</select>");
          ?>
  </div>
</div>

<div class='rad'>
  <div class='col-25'>
    <label for='fra'>Fra:</label>
  </div>
    <div class='col-75'>
      <?php
      include("kobletil.php");
      $innloggetBrukerNavn = $_SESSION['brukerNavn'];

      $sql = "SELECT bruker.ePost FROM bruker WHERE brukerNavn=?"; //Henter ut informasjon om epost
      $stmt = mysqli_stmt_init($db);

      if(!mysqli_stmt_prepare($stmt,$sql))
      {
        header("Location mail.php?error=sqlerror"); //Får ut error hvis sql setningen ikke kan kjøre
        exit();
      }
      else
      {
        mysqli_stmt_bind_param($stmt, "s", $innloggetBruker);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);
        $rad = mysqli_fetch_assoc($resultat);
        $epost=$rad["ePost"];
  print("<input type='email' name='fra' id='fra' value='$epost' readonly></input>");
}
      ?>
</div>
</div>

<div class='rad'>
  <div class='col-25'>
    <label for='emne'>Emne: </label>
  </div>
    <div class='col-75'>
  <input type="text" name="emne" id="emne"/>
</div>
</div>

<div class='rad'>
  <div class='col-25'>
    <label for='melding'>Melding: </label>
  </div>
    <div class='col-75'>
  <textarea name="melding" id="melding" placeholder="Skriv inn din melding her..." cols='20' rows='5'></textarea>
</div>
</div>
<div class='rad'>
    <input type="submit" value="Send" name="send" id="send" class="send">
  </div>
  </form>
</div>

<?php
if(isset($_POST ["sendmailskjema"])) {
  ini_set("SMTP","s120.hbv.no");
  ini_set("smtp_port","25");
	date_default_timezone_set("Europe/Oslo");

	$sendtil = $_POST['eposttil'];
	$sendtfra = $_POST['fra'];
	$emne = $_POST['emne'];
	$melding = $_POST['melding'];
	$headers = "From: " . $fra . "\r\n" .
				'X-Mailer: PHP/' . phpversion() . "\r\n" .
				"MIME-Version: 1.0\r\n" .
				"Content-Type: text/html; charset=utf-8\r\n" .
				"Content-Transfer-Encoding: 8bit\r\n\r\n";
	$OK = mail($sendtil, $emne, $melding, $headers);
	if ($OK) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Meldingen er sendt</title>
</head>
<body>Meldingen er sendt!</body>
</html>
<?php
	} else {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Meldingen er ikke sendt</title>
</head>
<body>Meldingen ble ikke sendt til <?php print("$sendtil"); ?></body>
</html>
<?php
	}
}
?>
