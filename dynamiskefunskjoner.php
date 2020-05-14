<?php
function listeboksinteresser()
{
  include("kobletil.php");

  $sqlSetning="SELECT * FROM interesser ORDER BY interesse;";
  $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

  $antallRader=mysqli_num_rows($sqlResultat);

  for ($r=1;$r<=$antallRader;$r++)
  {
  $rad=mysqli_fetch_array($sqlResultat);
  $interesse=$rad["interesse"];
  print("<option value='$interesse'>$interesse</option>");


  }
}

function listeboksbrukere()
{
  include("kobletil.php");

  $sqlSetning="SELECT brukerNavn FROM bruker;";
  $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

  $antallRader=mysqli_num_rows($sqlResultat);

  for ($r=1;$r<=$antallRader;$r++)
  {
  $rad=mysqli_fetch_array($sqlResultat);
  $brukernavn=$rad["brukerNavn"];

  print("<option value='$brukernavn'>$brukernavn</option>");

}
}
function listeboksdineinteresser()
{
  include("sjekkomloggetinn.php");
  include("kobletil.php");

  $sql="SELECT interesser.interesse FROM interesser INNER JOIN interesserbruker ON interesser.interesseID = interesserbruker.interesseIDbruker AND interesserbruker.interesserbrukerID=?;";
  $stmt= mysqli_stmt_init($db);
  if(!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: registerinfo.php?error=sqlerror1");
    exit();
  }
  else{
    mysqli_stmt_bind_param($stmt, "s", $innloggetBrukerID);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);
    $rad = mysqli_fetch_assoc($resultat);
    $dineinteresser=$rad["interesse"];



  print("<option value='$dineinteresser'>$dineinteresser</option>");
  }
}
function listeboksstudier()
{
  include("kobletil.php");

  $sqlSetning="SELECT * FROM studier ORDER BY studier;";
  $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

  $antallRader=mysqli_num_rows($sqlResultat);

  for ($r=1;$r<=$antallRader;$r++)
  {
  $rad=mysqli_fetch_array($sqlResultat);
  $studier=$rad["studier"];
  $campus=$rad["campus"];

  print("<option value='$studier $campus'>$studier $campus</option>");

  }
}

 ?>
