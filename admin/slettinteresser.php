<?php
include("sjekkomloggetinnadmin.php");
include("start3.html");
?>
<h3>Slett Interesser</h3>

<form method="post" action="" id="slettinteresse" name="slettinteresse" onSubmit="return bekreft()">
  Interesser: <select name="interesse" id="interesse">
  <?php
  include("../dynamiskefunskjoner.php"); listeboksinteresser();
  ?>
</select>
  <input type="submit" value="Slett Interesse" name="slettinteresseknapp" id="slettinteresseknapp" />
</form>

<?php
if(isset($_POST ["slettinteresseknapp"]))
{
  $interesse=$_POST["interesse"];
  include("../kobletil.php");

  $sqlSetning="DELETE FROM interesser WHERE interesse='$interesse';";
  mysqli_query($db,$sqlSetning) or die ("ikke mulig å slette data i databasen");
  $sqlSetning="DELETE FROM interesserbruker INNER JOIN interesser ON interesser.interesseID = interessebruker.interesseIDbruker WHERE interesser.interesse = '$interesse';";
  mysqli_query($db,$sqlSetning) or die ("ikke mulig å slette data i databasen");

  print("Følgende interesse er nå slettet: $interesse");
}
include("../slutt.html");
 ?>
