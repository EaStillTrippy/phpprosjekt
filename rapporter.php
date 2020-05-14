<?php
include("sjekkomloggetinn.php");
include("start2.html");
header('Content-Type: text/html; charset=utf-8');
?>
<h1 style="margin-top: 20px;"> Her kan du rapportere brukere! </h1>
<form method="post" action="" id="rapporter" name="rapporter">
  <?php
include("kobletil.php");
print("<div class='rad' style='height: 20px;'>");
  print("<div class='col'>");

  print("<select name='brukerID' id='brukerID'>");
  print("<option value=''>Velg brukernavn her...</option>");

  $sqlSetning="SELECT brukerNavn, idbruker FROM bruker ORDER BY brukerNavn;";
  $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

  $antallRader=mysqli_num_rows($sqlResultat);

  for ($r=1;$r<=$antallRader;$r++)
  {
  $rad=mysqli_fetch_array($sqlResultat);

  $brukerID = $rad["idbruker"];
  $brukernavn=$rad["brukerNavn"];
  print("<option value='$brukerID'>$brukernavn</option>");
  }
  print("</select>");

  ?>
  <br>
  <textarea name="begrunnelse" id="begrunnelse" placeholder="Skriv din begrunnelse her..."></textarea>
  <br>
  <input type="submit" name="rapporter" value="rapporter" id="rapporter"/>
</form>

<?php
include("admin/regel.html");
  if (isset($_POST ["rapporter"]))
  {
    $begrunnelse = $_POST["begrunnelse"];
    $brukerID = $_POST['brukerID'];

    $sql = "INSERT INTO rapporter(rapportertbrukerID, begrunnelse) VALUES (?,?)";
    $stmt= mysqli_stmt_init($db);

    if(!mysqli_stmt_prepare($stmt,$sql)) //Får ut error hvis sql setningen ikke kan kjøre
    {
      print("SQL ERROR 1");
      exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "is", $brukerID, $begrunnelse);
      mysqli_stmt_execute($stmt);
      print("Brukeren er nå rapportert!");
      print("<meta http-equiv='refresh' content='2' >");
    }
  }
  ?>
