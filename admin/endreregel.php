<?php
include("sjekkomloggetinnadmin.php");
include("start3.html");
include("../kobletil.php");

$regelnr = $_GET["endreregel"];

$sqlSetning="SELECT regelnr, regel FROM regler WHERE regelnr = '$regelnr'";
$sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
$rad=mysqli_fetch_array($sqlResultat);
$regelnr1 = $rad["regelnr"];
$regel = $rad["regel"];
?>
<h1>Endre valgt regel her!</h1>
<form method="post" action="" id="endreregelform" name="endreregelform">
  <?php
    print("<input type='text' value='$regelnr1' name='endretregelnr' id='endretregelnr'/>");
    print("<textarea placeholder='$regel' style='margin-right: 5px; margin-left: 5px; margin-top: 5px;' cols='25' rows='2' name='endretregel' id='endretregel'></textarea>");
  ?>
  <input type="submit" name="lagreendring" id="lagreendring" value="Endre" />
</form>
<?php
if(isset($_POST["lagreendring"]))
{
  include("../kobletil.php");
  $regelnr = $_POST["endretregelnr"];
  $regel = $_POST["endretregel"];

  $sql1 = "UPDATE regler SET regelnr=?, regel=? WHERE regelnr=?";
  $stmt1 = mysqli_stmt_init($db);


  if(!mysqli_stmt_prepare($stmt1, $sql1))  //Får ut error hvis sql setningen ikke kan kjøre
  {
    print("SQL ERROR 1");
    exit();
  }

  else
  {
    mysqli_stmt_bind_param($stmt1, "isi", $regelnr, $regel, $regelnr);

    mysqli_stmt_execute($stmt1);
    print("Regel: $regelnr $regel er nå endret");
    print("<meta http-equiv='refresh' content='2'>");
    header("Location: regler.php");
}
}

 ?>
