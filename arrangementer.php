<?php
include("sjekkomloggetinn.php");
include("start2.html");
header('Content-Type: text/html; charset=utf-8');
?>
<link title="stilark" href="stilark.css" rel="stylesheet" type="text/css" media="screen" />

<div class="container">
  <div class="container4">
    <h1> Her kan du registrere nye arrangementer og se aktive! </h1>
    <form method="post" action="" id="registrerarrangement" name="registrerarrangement" class="registrerarrangement" enctype="multipart/form-data">

      <div class='rad'>
        <div class='col-25'>
          <label for='arrangementnavn'>Arrangement Navn</label>
        </div>
        <div class='col-75'>
          <input type="text" name="arrNavn" id="arrNavn" class="arrNavn" placeholder="Skriv inn arrangement navn her..." autofocus/>
        </div>
      </div>

      <div class='rad'>
        <div class='col-25'>
          <label for='arrangementbeskrivelse'>Arrangement Beskrivelse</label>
        </div>
        <div class='col-75'>
          <textarea  name="arrBeskrivelse" id="arrBeskrivelse" class="arrBeskrivelse" placeholder="Skriv inn en beskrivelse av arrangementet her..." cols='20' rows='5'></textarea>
        </div>
      </div>

      <div class='rad'>
        <div class='col-25'>
          <label for='arrangementtid'>Arrangement Tid</label>
        </div>
        <div class='col-79'>
          <input type="date" name="arrTid" id="arrTid" class="arrTid"/>
        </div>
        <input type="submit" name="registrerarr" id="registrerarr" class="registrerarr" value="Registrer Arrangement">
      </div>
    </form>
  </div>
  <div class="container5">
    <form method="get" action="valgtarrangement.php" id="registrerdeltagelse" name="registrerdeltagelse" class="registrerdeltagelse" enctype="multipart/form-data">

      <div class='rad'>
        <div class='col-25'>
          <label for='valgtarrangement'>Velg arrangement</label>
        </div>
        <div class='col-77'>
          <select name='arrangementnavn' id='arrangementnavn'>
          <option value=''>Velg Arrangement her...</option>
<?php
    include("kobletil.php");
    $sqlSetning="SELECT arrangementnavn, arrangementbeskrivelse, arrangementID FROM arrangement ORDER BY arrangementtid;";
    $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

    $antallRader=mysqli_num_rows($sqlResultat);

    for ($r=1;$r<=$antallRader;$r++)
    {
      $rad=mysqli_fetch_array($sqlResultat);
      $arrangementID = $rad["arrangementID"];
      $arrangementnavn=$rad["arrangementnavn"];
      $arrangementbeskrivelse=$rad["arrangementbeskrivelse"];

      print("<option value='$arrangementID'>$arrangementnavn</option>");
    }
    print("</select>");
    print("</div>");
    print("<input type='submit' name='searrangement' value='Se arrangement' class='searrangement' id='searrangement'>");
    print("</div>");


 ?>
</form>
</div>

      <?php
      if(isset($_POST["registrerarr"]))
        {

          $arrNavn=$_POST ["arrNavn"];
          $arrNavnsmå=strtolower($arrNavn);
          $arrangementnavn=ucwords($arrNavnsmå);
          $dato = $_POST ["arrTid"];
          $arrBeskrivelse = $_POST["arrBeskrivelse"];


        if (!$arrNavn)
          {
            print ("feltet må fylles ut!");
            die();
          }
        if (!ctype_alpha($arrNavn))
          {
            print("Interessen kan kun bestå av bokstaver");
            die();
          }
        else
          {
            include("kobletil.php");

            $sql="SELECT * FROM arrangement WHERE arrangementnavn=?;";
            $stmt= mysqli_stmt_init($db);
            if(!mysqli_stmt_prepare($stmt,$sql)) //Får ut error hvis sql setningen ikke kan kjøre
            {
              print("SQL ERROR 1");
              exit();
            }
            else
            {
              mysqli_stmt_bind_param($stmt, "s", $arrNavn);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_store_result($stmt);
              $resultat = mysqli_stmt_num_rows($stmt);

              if($resultat > 0)
              {
                die("Interessen er registrert fra før!");
                exit();
              }
              else
              {
                $sql = "INSERT INTO arrangement (arrangementnavn, arrangementbeskrivelse, arrangementtid) VALUES (?,?,?)";
                $stmt= mysqli_stmt_init($db);

                if(!mysqli_stmt_prepare($stmt,$sql)) //Får ut error hvis sql setningen ikke kan kjøre
                {
                  print("SQL ERROR 1");
                  exit();
                }
                else
                {
                  mysqli_stmt_bind_param($stmt, "sss", $arrangementnavn, $arrBeskrivelse, $dato);
                  mysqli_stmt_execute($stmt);
                  print("Følgende Arramgement er nå registrert: $arrangementnavn");
                  print("<meta http-equiv='refresh' content='10'>");
                }
              }
            }
          }
        }





       ?>
