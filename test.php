

  <?php
  include("sjekkomloggetinn.php");
  include("start2.html");
  header('Content-Type: text/html; charset=utf-8');
  ?>
    <link title="stilark" href="stilark.css" rel="stylesheet" type="text/css" media="screen" />

  <div class="container">
    <div class="container2">
  <h1> Her kan du registrere informasjon om deg selv! </h1>
  <form method="post" action="" id="registrerinfoskjema" name="registrerinfoskjema" class="registrerinfoskjema">
    <?php
    require 'kobletil.php';


    $sql = "SELECT bruker.brukerNavn, bruker.ePost, info.bio FROM bruker JOIN info ON bruker.brukerNavn = info.brukerNavn WHERE bruker.brukerNavn=?";
    $stmt = mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt,$sql)){
      header("Location minside2.php?error=sqlerror");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt, "s", $innloggetBruker);
      mysqli_stmt_execute($stmt);
      $resultat = mysqli_stmt_get_result($stmt);
      $rad = mysqli_fetch_assoc($resultat);
      $brukernavn=$rad["brukerNavn"];
      $epost=$rad["ePost"];
      $bio = $rad["bio"];

    print("<div class='rad'>");
    print("<div class='col-25'>");
    print("<label for='brukernavn'>Brukernavn</label>");
    print("</div>");
    print("<div class='col-75'>");
    print("<input type='text' id='brukernavn' name='brukernavn' value='$brukernavn' readonly>");
    print("</div>");
    print("</div>");
    print("<div class='rad'>");
    print("<div class='col-25'>");
    print("<label for='epost'>E-post</label>");
    print("</div>");
    print("<div class='col-75'>");
    print("<input type='email' id='epost' name='epost' value='$epost'readonly>");
    print("</div>");
    print("</div>");
    print("<div class='rad'>");
    print("<div class='col-25'>");
    print("<label for='dintekst'>Din Tekst</label>");
    print("</div>");
    print("<div class='col-75'>");
    print("<textarea id='bio' name='bio' placeholder='Skriv noe om deg selv...' cols='30' rows='10'>$bio</textarea>");
    print("</div>");
    print("</div>");
  print("<div class='rad'>");
  print("<div class='col-25'>");
  print("<label for='dininteresse'>Interessen din</label>");
  print("</div>");
  print("<div class='col-75'>");
  print("<select name='interesse' id='interesse'>");
  print("<option value='$interesse'>$interesse</option>");
  include("dynamiskefunskjoner.php"); listeboksinteresser();
  print("</select>");
  print("</div>");
  print("</div>");
  print("<div class='interesse'");
  print("<div class='rad'>");
  print("<div class='col-25'>");
  print("<label for='lagny'>Lag en ny interesse</label>");
  print("</div>");
  print("<div class='col-76'>");
  print("<input type='text' id='lagnyinteresse' name='lagnyinteresse' class='lagnyinteressefelt'/>");
  print("</div>");
  print("</div>");

  }
  ?>
  <input type="submit" value='Lag ny' id="laginteresseknapp" name="laginteresseknapp" class="laginteresseknapp"/>
      <div class="knapper">
        <button class="tilbake" onclick="location.href='registrerinfo.php'" type="button">Tilbake</button><br />
    <input type="submit" value="Lagre" id="registrerinfo" name="registrerinfo" class="registrerinfo" />
  </div>
  </form>
  </div>
  </div>
  <?php
  if (isset($_POST ["registrerinfo"])){
    $brukernavn=$_POST["brukernavn"];
    $bio=$_POST["bio"];
    $interesser=$_POST["interesse"];
    require 'kobletil.php';
    $sql="UPDATE info SET bio=? WHERE brukerNavn=?;";
    $stmt= mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: test.php?error=sqlerror1");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt, "ss", $bio, $innloggetBruker);
      mysqli_stmt_execute($stmt);
      print("<meta http-equiv='refresh' content='0;url=registrerinfo.php'>");
    }


  }
  if(isset($_POST["laginteresseknapp"]))
  {

    $interesser=$_POST ["lagnyinteresse"];
    $interessersmå=strtolower($interesser);
    $interesse=ucwords($interessersmå);


    if (!$interesse)
      {
        print ("feltet må fylles ut!");

      }
      if (!ctype_alpha($interesse))
      {
        print("Interessen kan kun bestå av bokstaver");
      }
    else
      {
        include("../kobletil.php");

        $sqlSetning="SELECT * FROM diverse WHERE interesser='$interesse';";
        $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig å hente data fra databasen");
        $antallRader=mysqli_num_rows($sqlResultat);

        if ($antallRader!=0)
          {
            print ("Interessen er registrert fra før");
          }
        else
          {
            $sqlSetning="INSERT INTO diverse (interesser) VALUES ('$interesse');";
            mysqli_query($db,$sqlSetning) or die ("ikke mulig å registrere data i databasen");


             print ("Følgende interesse er nå registrert: $interesse");
             print("<meta http-equiv='refresh' content='0;url=registrerinfo.php'>");
          }

        }

      }



  include("slutt.html");
  ?>
