<?php
include("sjekkomloggetinn.php");
include("start2.html");
header('Content-Type: text/html; charset=utf-8');
?>
<link title="stilark" href="stilark.css" rel="stylesheet" type="text/css" media="screen" />

<div class="container">
  <div class="container6">
    <h1> Her kan du se informasjonen om ditt valgte arrangement </h1>
    <form method="post" action="" id="registrdeltagelse" name="registrdeltagelse" class="registrdeltagelse" enctype="multipart/form-data">

      <?php
      $arrangementID = $_GET["arrangementnavn"];
          include("kobletil.php");
          $sqlSetning="SELECT arrangementnavn, arrangementbeskrivelse, arrangementtid, arrangementID FROM arrangement WHERE arrangementID='$arrangementID' ;";
          $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

          $antallRader=mysqli_num_rows($sqlResultat);
          $rad=mysqli_fetch_array($sqlResultat);
          $arrangementnavn2=$rad['arrangementnavn'];
          $arrangementbeskrivelse=$rad['arrangementbeskrivelse'];
          $arrangementtid=$rad['arrangementtid'];
          $arrID=$rad['arrangementID'];

          print("<div class='rad'>");
              print("<div class='col-25'>");
                print("<label for='arrangementID'>Arrangement ID</label>");
              print("</div>");
              print("<div class='col-75'>");
                print("<input type='text' name='arrID' id='arrID' class='arrID' autofocus value='$arrID' readonly></input>");
              print("</div>");
            print("</div>");

      print("<div class='rad'>");
          print("<div class='col-25'>");
            print("<label for='arrangementnavn'>Arrangement Navn</label>");
          print("</div>");
          print("<div class='col-75'>");
            print("<input type='text' name='arrNavn' id='arrNavn' class='arrNavn' autofocus value='$arrangementnavn2' readonly></input>");
          print("</div>");
        print("</div>");

        print("<div class='rad'>");
          print("<div class='col-25'>");
          print("<label for='arrangementbeskrivelse'>Arrangement Beskrivelse</label>");
        print("</div>");
        print("<div class='col-75'>");
          print("<textarea  name='arrBeskrivelse' id='arrBeskrivelse' class='arrBeskrivelse' cols='20' rows='5' readonly>$arrangementbeskrivelse</textarea>");
        print("</div>");
        print("</div>");

      print("<div class='rad'>");
        print("<div class='col-25'>");
          print("<label for='arrangementtid'>Arrangement Tid</label>");
          print("</div>");
        print("<div class='col-79'>");
          print("<input type='date' value='$arrangementtid' name='arrTid' id='arrTid' class='arrTid' readonly></input>");
        print("</div>");
      print("</div>");

      print("<div class='rad'>");
        print("<div class='col-25'>");
          print("<label for='deltagelse'>Ønsker du å delta?</label>");
          print("</div>");
        print("<div class='col-80'>");

        $sqlSetning="SELECT arrangementdeltagelse FROM arrangementbruker WHERE arrangementbrukerID='$innloggetBrukerID' AND arrangementIDbruker='$arrangementID' ;";
        $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

        $antallRader=mysqli_num_rows($sqlResultat);
        $rad=mysqli_fetch_array($sqlResultat);
        $arrdeltagelse = $rad["arrangementdeltagelse"];


?>
            <input type='radio' name='deltagelse' value='Y' id='deltagelse' class='yes' <?php if($arrdeltagelse == "Y"){ echo 'checked';} ?>/>Ønsker å delta
            <input type='radio' name='deltagelse' value='N' id='deltagelse' class='no' <?php if($arrdeltagelse == "N"){ echo 'checked';} ?>/>Ønsker ikke å delta
            <input type='radio' name='deltagelse' value='K' id='deltagelse' class='maybe' <?php if($arrdeltagelse == "K"){ echo 'checked';} ?>/>Interessert


      </div>
        <input type='submit' name='lagrevalg' value='Lagre valget' class='lagrevalg' id='lagrevalg'>
          <input type='submit' name='tilbaketilarrangementer' value='Tilbake' class='tilbaketilarrangementer' id='tilbaketilarrangementer'>
        </div>


    </form>
  </div>
</div>
<?php
if(isset($_POST["lagrevalg"]))
  {
      $arrangementID = $_POST["arrID"];
      $innloggetBrukerID = $_SESSION['idbruker'];
      $deltagelse = $_POST["deltagelse"];


          $sql = "INSERT INTO arrangementbruker (arrangementbrukerID, arrangementIDbruker, arrangementdeltagelse) VALUES (?,?,?) ON DUPLICATE KEY UPDATE arrangementdeltagelse=?";
          $stmt= mysqli_stmt_init($db);

          if(!mysqli_stmt_prepare($stmt,$sql)) //Får ut error hvis sql setningen ikke kan kjøre
          {
            print("SQL ERROR 1");
            exit();
          }
          else
          {
            mysqli_stmt_bind_param($stmt, "ssss", $innloggetBrukerID, $arrangementID, $deltagelse, $deltagelse);
            mysqli_stmt_execute($stmt);
            print("Du har nå registrert ditt valg, ønsker du å endre valget, trykker du bare på en ny knapp!");
            print("<meta http-equiv='refresh' content='0' >");
          }

        }

      if(isset($_POST["tilbaketilarrangementer"])){
        echo "<meta http-equiv='refresh' content='0;url=arrangementer.php'>";
      }




 ?>
