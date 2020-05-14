<?php
include("sjekkomloggetinnadmin.php");
include("start3.html");
?>
<h3>Registrer Interesse </h3>
<form method="post" action="" id="registrerinteresse" name="registrerinteresse">
  Interesse <input type="text" id="interesse" name="interesse" required /> <br/>
  <input type="submit" value="Registrer Interesse" id="registrerinteresseknapp" name="registrerinteresseknapp" />
  <input type="reset" value="Nullstill" id="nullstill" name="nullstill" /> <br />
</form>
<div id="melding"></div>
<?php
  if (isset($_POST ["registrerinteresseknapp"]))
    {
      $interesser=$_POST ["interesse"];
      $interessersmå=strtolower($interesser);
      $interesse=ucwords($interessersmå);
      if (!$interesse)
        {
          print ("feltet må fylles ut!");
        }
        if (!ctype_alpha($interesse)){
          print("Interessen kan kun bestå av bokstaver");
        } else {
          include("../kobletil.php");
          $sqlSetning="SELECT * FROM interesser WHERE interesse='$interesse';";
          $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig å hente data fra databasen");
          $antallRader=mysqli_num_rows($sqlResultat);
          if ($antallRader!=0){
              print ("Interessen er registrert fra før");
            } else {
              $sqlSetning="INSERT INTO interesser (interesse) VALUES ('$interesse');";
              mysqli_query($db,$sqlSetning) or die ("ikke mulig å registrere data i databasen");
              print ("Følgende interesse er nå registrert: $interesse");
            }
          }
        }
    include("../slutt.html");
?>
