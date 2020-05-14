<?php
include("sjekkomloggetinnadmin.php");
include("start3.html");
?>
<h1 style="margin-top: 20px;"> Her kan du lage og endre regler! </h1>
<form method="post" action="" id="lagregel" name="lagregel">
  <div class='col-25'>
  <div class='rad'>
  <input type="text" id="regelnr" name="regelnr" placeholder="Skriv inn regelnummeret"/>
  <br>
  <textarea placeholder="Skriv inn regelen" name="regel" id="regel" cols='25' rows='2'></textarea>
  <input type="submit" name="lagreregel" id="lagreregel" value="Lagre"/>
</div>
</div>
</form>
<form method="get" action="endreregel.php" id="endreregel" name="endreregel">
<div class='col-25'>
<div class='rad'>
  <select name="endreregel" id="endreregel">
    <option value="">Velg regel du ønsker å endre..</option>
    <?php
    include("../kobletil.php");


    $sqlSetning="SELECT * FROM regler ORDER BY regelnr";
    $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
    $antallRader=mysqli_num_rows($sqlResultat);

    for ($r=1;$r<=$antallRader;$r++)
    {
    $rad=mysqli_fetch_array($sqlResultat);

    $regelnr=$rad["regelnr"];
    $regel = $rad["regel"];

    print("<option value='$regelnr'>$regelnr</option>");

    }


     ?>
  </select>
  <input type="submit" value="Endre" name="endreknapp" id="endreknapp">
</form>

<form method="post" action"" id="redigerregler" name="redigerregler">
  <select name="slettregel" id="slettregel">
    <option value="">Velg regel du ønsker å slette..</option>
    <?php
    include("../kobletil.php");


    $sqlSetning="SELECT * FROM regler ORDER BY regelnr";
    $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
    $antallRader=mysqli_num_rows($sqlResultat);

    for ($r=1;$r<=$antallRader;$r++)
    {
    $rad=mysqli_fetch_array($sqlResultat);

    $regelnr=$rad["regelnr"];
    $regel = $rad["regel"];

    print("<option value='$regelnr'>$regelnr</option>");

    }


     ?>
  </select>
  <input type="submit" value="Slett" name="sletteknapp" id="sletteknapp" onClick="return bekreft()"></br>
  <p>Har du lagt til ny regel eller endret? Trykk på knappen under for å publisere til brukerene!</p>
  <input type="submit" name="skrivtilhtml" value="Publiser til brukere" id="skrivtilhtml" style="margin-bottom: 10px;"/>
  <table id="regler">
  <tr>
    <th>Regelnr</th>
    <th>Regel</th>

  </tr>
  <?php
  include("../kobletil.php");


  $sqlSetning="SELECT * FROM regler ORDER BY regelnr";
  $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
  $antallRader=mysqli_num_rows($sqlResultat);

  for ($r=1;$r<=$antallRader;$r++)
  {
  $rad=mysqli_fetch_array($sqlResultat);

  $regelnr=$rad["regelnr"];
  $regel = $rad["regel"];

  print("<tr> <td value='$regelnr' name='endretregelnr' id='endretregelnr'> $regelnr </td> <td > $regel </td> </tr>");

}
  print("</table>");

  ?>
</form>
  <?php

  if(isset($_POST['lagreregel']))
  {
    include("../kobletil.php");
    $regelnr = $_POST["regelnr"];
    $regel = $_POST["regel"];

    $sql = "SELECT * FROM regler WHERE regelnr=?";
    $stmt = mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt,$sql)){
      header("Location: login.php?error=sqlerror1");
      exit();
    }
    if(!is_numeric($regelnr))
    {
      print("Regelnummer må være et tall");
      die();
    }

    else{
      mysqli_stmt_bind_param($stmt, "i", $regelnr);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultat = mysqli_stmt_num_rows($stmt);
      if($resultat > 0) {
        die("Regelen er registrert fra før, endre under <br />");

      }


      $sql1 = "INSERT INTO regler (regelnr, regel) VALUES (?,?)";
      $stmt1 = mysqli_stmt_init($db);


      if(!mysqli_stmt_prepare($stmt1, $sql1))  //Får ut error hvis sql setningen ikke kan kjøre
      {
        print("SQL ERROR 1");
        exit();
      }

      else
      {
        mysqli_stmt_bind_param($stmt1, "is", $regelnr, $regel);

        mysqli_stmt_execute($stmt1);
        print("Regel: $regelnr $regel er nå lagret");
        print("<meta http-equiv='refresh' content='2'>");
    }

}
}
if(isset($_POST["sletteknapp"]))
{
  $regelnr=$_POST["slettregel"];
  include("../kobletil.php");

  $sqlSetning="DELETE FROM regler WHERE regelnr='$regelnr';";
  mysqli_query($db,$sqlSetning) or die ("ikke mulig å slette data i databasen");
  print("Regel: $regelnr er nå slettet!");
  print("<meta http-equiv='refresh' content='2'>");
}
if(isset($_POST["skrivtilhtml"]))
{
  include("../kobletil.php");
  $sql = "SELECT regel FROM regler";

  $open = fopen("regel.html",'w');
  fwrite($open, "<html>\n\t<head> <link title='stilark' href='../stilark.css' rel='stylesheet' type='text/css' media='screen' />\n\t\t<title>Regler</title>\n\t</head>\n");
   fwrite($open,"\t<body>\n\t\t");
    fwrite($open,"<h1>Nettsidens Regeler</h1>");
     fwrite($open,"<table id='reglertabell' name='reglertabell'>\n\t\t\t<tr><th>Regelnummer</th><th>Regelen</th></tr>");

     $resultat=mysqli_query($db,$sql) or die ("ikke mulig &aring; hente data fra databasen");
     $antallRader=mysqli_num_rows($resultat);

for($r=1;$r<=$antallRader;$r++){
  $rad = mysqli_fetch_array($resultat);
  $regel = $rad["regel"];
  fwrite($open,"\n\t\t\t<tr><td>$r</td><td>$regel</td></tr>");
}
fwrite($open,"\n\t\t");
fwrite($open,"</table>\n\t</body>\n</html>");
fclose($open);
print("Html dokument er nå publisert til brukerene!");
}



    include("../slutt.html");
   ?>
