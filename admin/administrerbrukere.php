<?php
include("sjekkomloggetinnadmin.php");
include("start3.html");
?>

<h1> Her kan du administrere brukere! </h1>

<form method="post" action="" id="administrer" name="administrer">

  <?php
include("../kobletil.php");
print("<div class='rad' style='height: 20px;'>");
  print("<div class='col-25'>");
      print("<label for='utfør' style='margin-right: 5px;'>Velg handling</label> ");
  print("<select name='brukernavn' id='brukernavn'>");
  print("<option value=''>Velg brukernavn her...</option>");

  $sqlSetning="SELECT brukerNavn, status FROM bruker ORDER BY status;";
  $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

  $antallRader=mysqli_num_rows($sqlResultat);

  for ($r=1;$r<=$antallRader;$r++)
  {
  $rad=mysqli_fetch_array($sqlResultat);

  $status=$rad["status"];
  $brukernavn=$rad["brukerNavn"];
  print("<option value='$brukernavn'>$brukernavn $status</option>");
  }
  print("</select>");

  ?>
  <select name="handling" id="handling">
    <option value="">Velg handling her...</option>
    <option value="0">Fjern advarsler og utestengelser</option>
    <option value="1">Gi advarsel</option>
    <option value="2">Utesteng</option>
  </select>
<select name="adminrettigheter" id="adminrettigheter">
  <option value="">Gi en bruker admin rettigheter..</option>
  <option value="0">Fjern administratorrettigheter</option>
  <option value="1">Gi administratorrettigheter</option>
</select>
  <input type="submit" value="Lagre" name="lagre" id="lagre"/>
</div>
</form>
<table id="liste">
<tr>
  <th onclick="sorter(0)">Brukernavn▼</th>
  <th onclick="sorter(1)">Epost▼</th>
  <th style="width: 30%;">Rapporter</th>
  <th style="width: 20%;">Status</th>
</tr>
<?php
include("../kobletil.php");


$sqlSetning="SELECT bruker.brukerNavn, bruker.ePost, bruker.status, rapporter.begrunnelse FROM bruker LEFT JOIN rapporter ON bruker.idbruker = rapporter.rapportertbrukerID";
$sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
$antallRader=mysqli_num_rows($sqlResultat);

for ($r=1;$r<=$antallRader;$r++)
{
$rad=mysqli_fetch_array($sqlResultat);

$epost = $rad["ePost"];
$brukernavn=$rad["brukerNavn"];
$begrunnelse=$rad["begrunnelse"];
$status = $rad["status"];
if($status == '0'){
  print("<tr> <td> $brukernavn </td> <td> $epost </td> <td> $begrunnelse </td> <td> Ingen advarsler eller utestengelser </td> </tr>");
}
if($status == '1'){
  print("<tr> <td> $brukernavn </td> <td> $epost </td> <td> $begrunnelse </td> <td> Brukeren gitt en advarsel </td> </tr>");
}
if($status == '2'){
  print("<tr> <td> $brukernavn </td> <td> $epost </td> <td> $begrunnelse </td> <td> Brukeren er utestengt </td> </tr>");
}
}

?>
</table>
<script>
function sorter(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("liste");
  switching = true;

  dir = "asc";

  while (switching) {

    switching = false;
    rows = table.rows;

    for (i = 1; i < (rows.length - 1); i++) {

      shouldSwitch = false;

      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];

      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {

          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {

          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {

      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;

      switchcount ++;
    } else {

      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>
<?php
if (isset($_POST ["lagre"]))
{
  $handling = $_POST["handling"];
  $brukernavn = $_POST['brukernavn'];
  $admin = $_POST['adminrettigheter'];

  $sql = "UPDATE bruker SET status=?, administrator=? WHERE brukerNavn=?";
  $stmt= mysqli_stmt_init($db);

  if(!mysqli_stmt_prepare($stmt,$sql)) //Får ut error hvis sql setningen ikke kan kjøre
  {
    print("SQL ERROR 1");
    exit();
  }
  else
  {
    mysqli_stmt_bind_param($stmt, "iis", $handling, $admin, $brukernavn);
    mysqli_stmt_execute($stmt);
    print("Brukernavn: $brukernavn er nå oppdatert!");
    print("<meta http-equiv='refresh' content='2' >");

    if($handling == '1')
    {
      $sqlSetning="SELECT ePost FROM bruker WHERE brukerNavn = '$brukernavn'";
      $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
      $rad=mysqli_fetch_array($sqlResultat);
      $epost = $rad['ePost'];
      ini_set("SMTP","s120.hbv.no");
      ini_set("smtp_port","25");
    	date_default_timezone_set("Europe/Oslo");

    	$sendtil = $epost;
    	$sendtfra = "admin@alumni11.no";
    	$emne = "Advarsel";
    	$melding = "Du er rapportert av en bruker, og gitt en advarsel. Neste gang vil du bli utestengt";
    	$headers = "From: " . $sendtfra . "\r\n" .
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
}
}
include("../slutt.html") ?>
