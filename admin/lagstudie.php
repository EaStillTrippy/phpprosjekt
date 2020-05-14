<?php
include("sjekkomloggetinnadmin.php");
include("start3.html");
?>
<h3>Registrer Studie </h3>
<form method="post" action="" id="registrerstudie" name="registrerstudie" accept-charset="utf-8">
  Studie <input type="text" id="studie" name="studie" required /> <br/>
  Campus <input type="text" id="campus" name="campus" required /> <br/>
  <input type="submit" value="Registrer Studie" id="registrerstudieknapp" name="registrerstudieknapp" />
  <input type="reset" value="Nullstill" id="nullstill" name="nullstill" /> <br />
</form>
<div id="melding"></div>
<?php

  if (isset($_POST ["registrerstudieknapp"]))
    {
      $studie=$_POST ["studie"];
      $campus=$_POST ["campus"];
      $studiesmå=strtolower($studie);
      $studier=ucwords($studiesmå);
      $campussmå=strtolower($campus);
      $campuser=ucwords($campus);
      if (!$studie || !$campus)
        {
          print ("feltene må fylles ut!");
        }
      else
        {
          include("../kobletil.php");
              $sqlSetning="INSERT INTO studier (studier, campus) VALUES ('$studier','$campuser');";
              mysqli_query($db,$sqlSetning) or die ("ikke mulig å registrere data i databasen");
               print ("Følgende Studie er nå registrert: $studier $campuser");
            }
          }
    include("../slutt.html");
?>