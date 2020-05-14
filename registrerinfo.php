

  <?php
  include("sjekkomloggetinn.php");
  header('Content-Type: text/html; charset=utf-8');
  $scr = 0;
  if(isset($_POST["scroll"])){
    $scr = $_POST["scroll"];}
  ?>
  <html>
      <head>
          <title>USN Alumni</title>
          <link title="stilark" href="stilark.css" rel="stylesheet" type="text/css" media="screen" />
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <script src="hendelser.js"></script>
          <script src="scroll.js"></script>
      </head>
  <body class="body">

      <div id="hele">
          <header>
            <a href="defaultloggetinn.php">
            </a>

          </header>
          <nav class="navbar">

            <li style="float:right"><a href="loggut.php"> Logg ut</a></li>
            <form method="post" action="search.php" id="søkidatabasenskjema" name="søkidatabasenskjema" class="sokidatabasenskjema">
            <li style="float:right;"> <button type="submit" id="search" name="search" class="search">Søk</button></li>
            <li style="float:right; margin-top:0.1%; height:"><input type="text" id="sokidatabasen" name="sokidatabasen" class="bar" placeholder="Søk..."></input></li>
            </form>


            <div class="venstre">
                <div class="dropdown">
                  <button class="dropbtn" onclick="dropdown()">
                    Meny 	&#8595;
                  </button>
                  <div class="dropdown-content" id="ourDropdown">
                  <li><a href="mail.php"> Send mail</a></li>
                  <li><a href="arrangementer.php">Se Arrangementer</a></li>
                </div>
              </div>
              <li ><a class="hjem" href="defaultloggetinn.php"> Hjem </a></li>
              <li ><a class="minside" href="registrerinfo.php"> Min Side</a></li>
            </div>

          </nav>
          <script>
          function dropdown(){
            document.getElementById("ourDropdown").classList.toggle("show");
          }
          window.onclick = function(e) {
            if (!e.target.matches('.dropbtn')) {
              var ourDropdown = document.getElementById("ourDropdown");
              if (ourDropdown.classList.contains('show')) {
                ourDropdown.classList.remove('show');

              }
            }
          }
          </script>



          <article>

  <div class="container">
    <div class="container2">
      <h1> Her kan du registrere informasjon om deg selv! </h1>
      <br>
      <form method="post" action="" id="registrerinfoskjema" name="registrerinfoskjema" class="registrerinfoskjema" enctype="multipart/form-data">
        <?php
        require 'kobletil.php';
        $innloggetBrukerNavn = $_SESSION['brukerNavn'];
        $innloggetBrukerID = $_SESSION['idbruker'];

        $sql = "SELECT bruker.brukerNavn, bruker.ePost, info.bio FROM bruker JOIN info ON bruker.brukerNavn = info.brukerNavn WHERE bruker.brukerNavn=?"; //Henter ut informasjon om epost og brukernavn
        $stmt = mysqli_stmt_init($db);

        if(!mysqli_stmt_prepare($stmt,$sql))
        {
          header("Location registrerinfo.php?error=sqlerror"); //Får ut error hvis sql setningen ikke kan kjøre
          exit();
        }
        else
        { //Avsluttes på linje 470
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
              print("<textarea id='bio' name='bio' placeholder='Skriv noe om deg selv...' cols='20' rows='5'>$bio</textarea>");
            print("</div>");
          print("</div>");


          print("<div class='rad'>");
            print("<br><h1> Studie </h1>");
            print("<div class='col-25'>");
              print("<label for='dinestudier'>Dine Studier</label>");
            print("</div>");
          print("<div class='col-77'>");

          $sql2="SELECT studier.studier, studier.campus, studier.studieID FROM studier INNER JOIN studiebruker ON studier.studieID = studiebruker.studieIDbruker AND studiebruker.studiebrukerID=?;";
          $stmt2= mysqli_stmt_init($db);
          if(!mysqli_stmt_prepare($stmt2, $sql2))
          {
            header("Location: registerinfo.php?error=sqlerror2"); //Får ut error hvis sql setningen ikke kan kjøre
            exit();
          }
          else
          { //Avsluttes på linje 469
            mysqli_stmt_bind_param($stmt2, "s", $innloggetBrukerID);
            mysqli_stmt_execute($stmt2);
            $resultat2 = mysqli_stmt_get_result($stmt2);
            print("<select name='dinestudier' id='dinestudier'>");
            while ($rad2 = mysqli_fetch_assoc($resultat2))
            {
              print("<option value=$rad2[studieID]>$rad2[studier] $rad2[campus]</option>");
            }

            print("</select>");
          print("</div>");
          print("<input type='submit' value='Slett valgt studie' id='slettstudieknapp' name='slettstudieknapp' class='slettstudieknapp'  onClick='return bekreft()'/>");
        print("</div>");

        print("<div class='rad'>");
          print("<div class='col-25'>");
            print("<label for='studie'>Studier</label>");
          print("</div>");
          print("<div class='col-75'>");
            print("<select name='studieID' id='studieID'>");
            print("<option value=''>Velg studier her...</option>");

              include("kobletil.php");

              $sqlSetning="SELECT * FROM studier ORDER BY studier;";
              $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

              $antallRader=mysqli_num_rows($sqlResultat);

              for ($r=1;$r<=$antallRader;$r++)
              {
              $rad=mysqli_fetch_array($sqlResultat);
              $studier=$rad["studier"];
              $campus=$rad["campus"];
              $studieID=$rad["studieID"];
              print("<option value='$studieID , $studier'>$studier $campus</option>");
              }
              print("</select>");
            print("</div>");
          print("</div>");

          print("<div class='rad'>");
            print("<br><h1> Interesse </h1>");
            print("<div class='col-25'>");
              print("<label for='dineinteresser'>Dine Interesser</label>");
            print("</div>");
            print("<div class='col-77'>");

              $sql1="SELECT interesser.interesse FROM interesser INNER JOIN interesserbruker ON interesser.interesseID = interesserbruker.interesseIDbruker AND interesserbruker.interesserbrukerID=?;";
              $stmt1= mysqli_stmt_init($db);
              if(!mysqli_stmt_prepare($stmt1, $sql1))
              {
                header("Location: registerinfo.php?error=sqlerror1"); //Får ut error hvis sql setningen ikke kan kjøre
                exit();
              }
              else
              { //Avsluttes på linje 468
                mysqli_stmt_bind_param($stmt1, "s", $innloggetBrukerID);
                mysqli_stmt_execute($stmt1);
                $resultat1 = mysqli_stmt_get_result($stmt1);
                print("<select name='dineinteresser' id='dineinteresser'>");
                while ($rad = mysqli_fetch_assoc($resultat1))
                {
                  print("<option value=$rad[interesse]>$rad[interesse]</option>");
                }

                print("</select>");
              print("</div>");
                print("<input type='submit' value='Slett valgt interesse' id='slettinteresseknapp' name='slettinteresseknapp' class='slettinteresseknapp'  onClick='return bekreft()'/>");
            print("</div>");

            print("<div class='rad'>");
              print("<div class='col-25'>");
                print("<label for='interesser'>Interesser</label>");
              print("</div>");
              print("<div class='col-75'>");
                print("<select name='interesse' id='interesse'>");
                print("<option value=''>Velg interesser her...</option>");
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
                print("</select>");
              print("</div>");
            print("</div>");


              print("<div class='rad'>");
                print("<div class='col-25'>");
                  print("<label for='lagny'>Lag en ny interesse</label>");
                print("</div>");
                print("<div class='col-76'>");
                  print("<input type='text' id='lagnyinteresse' name='lagnyinteresse' class='lagnyinteressefelt' placeholder='Skriv inn interessen du vil lage her...'/>");
                print("</div>");
                print("<input type='submit' value='Registrer ny interesse' name='laginteresseknapp' id='laginteresseknapp' class='laginteresseknapp'/>");
              print("</div>");


  ?>
    <div class='bildediv'> 
      <div class='rad'>
        <div class='col-26'>
          <label for='bilde'>Last opp profilbilde</label>
        </div>
        <div class='col-78'>
          <input type="file" name="file" id="file" class="file"/>
        </div>
      </div>
    </div>
    <input type="submit" value="Lagre brukerinnstillinger" id="registrerinfo" name="registrerinfo" class="registrerinfo" onclick="saveScroll();"/>
    <input type="hidden" id="scroll" name="scroll" value=""/>
  </div>
  </form>
</div>

  <?php
  if (isset($_POST ["registrerinfo"]))//Lagrer info om beskrivelse og interesser
  {
    $brukernavn=$_POST["brukernavn"];
    $bio=$_POST["bio"];
    $interesser=$_POST["interesse"];

    require 'kobletil.php';

    $sql="UPDATE info SET bio=? WHERE brukerNavn=?;";
    $stmt= mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt, $sql))  //Får ut error hvis sql setningen ikke kan kjøre
    {
      print("SQL ERROR 1");
      exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "ss", $bio, $innloggetBruker);
      mysqli_stmt_execute($stmt);
      $sql = "SELECT interesseID FROM interesser WHERE interesse=?";
      $stmt = mysqli_stmt_init($db);

      if(!mysqli_stmt_prepare($stmt, $sql))  //Får ut error hvis sql setningen ikke kan kjøre
      {
        print("SQL ERROR 1");
        exit();
      }
      else
      {
        mysqli_stmt_bind_param($stmt, "s", $interesser);
        mysqli_stmt_execute($stmt);
        $result1 = mysqli_stmt_get_result($stmt);
        $rad1 = mysqli_fetch_assoc($result1);
        $interesseID = $rad1['interesseID'];
        $sql1 = "INSERT INTO interesserbruker (interesserbrukerID, interesseIDbruker) VALUES (?,?)";
        $stmt1 = mysqli_stmt_init($db);


        if(!mysqli_stmt_prepare($stmt1, $sql1))  //Får ut error hvis sql setningen ikke kan kjøre
        {
          print("SQL ERROR 1");
          exit();
        }

        else
        {
          mysqli_stmt_bind_param($stmt1, "ii", $innloggetBrukerID, $interesseID);

          mysqli_stmt_execute($stmt1);

          print("<meta http-equiv='refresh' content='0'>");

        }
      }
    }
  }
  if (isset($_POST ["registrerinfo"])) //Lagrer info om studiet
  {
    $studieID=$_POST["studieID"];

    require 'kobletil.php';


    $sql1 = "INSERT INTO studiebruker VALUES (?,?)";
    $stmt1 = mysqli_stmt_init($db);

    if(!mysqli_stmt_prepare($stmt1, $sql1)) //Får ut error hvis sql setningen ikke kan kjøre
    {
        print("SQL ERROR 1");
          exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmt1, "ii", $innloggetBrukerID, $studieID);
      mysqli_stmt_execute($stmt1);
      print("<meta http-equiv='refresh' content='0'>");

    }
  }

if(isset($_POST["laginteresseknapp"])) //Legger til en ny interesse i databasen
  {

    $interesser=$_POST ["lagnyinteresse"];
    $interessersmå=strtolower($interesser);
    $interesse=ucwords($interessersmå);


  if (!$interesse)
    {
      print ("feltet må fylles ut!");
      die();
    }
  if (!ctype_alpha($interesse))
    {
      print("Interessen kan kun bestå av bokstaver");
      die();
    }
  else
    {
      include("kobletil.php");

      $sql="SELECT * FROM interesser WHERE interesse=?;";
      $stmt= mysqli_stmt_init($db);
      if(!mysqli_stmt_prepare($stmt,$sql)) //Får ut error hvis sql setningen ikke kan kjøre
      {
        print("SQL ERROR 1");
        exit();
      }
      else
      {
        mysqli_stmt_bind_param($stmt, "s", $interesse);
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
          $sql = "INSERT INTO interesser (interesse) VALUES (?)";
          $stmt= mysqli_stmt_init($db);

          if(!mysqli_stmt_prepare($stmt,$sql)) //Får ut error hvis sql setningen ikke kan kjøre
          {
            print("SQL ERROR 1");
            exit();
          }
          else
          {
            mysqli_stmt_bind_param($stmt, "s", $interesse);
            mysqli_stmt_execute($stmt);
            print("Følgende interesse er nå registrert: $interesse");
            print("<meta http-equiv='refresh' content='0'>");
          }
        }
      }
    }
  }
if (isset($_POST ["slettinteresseknapp"]))  //Sletter valgt interesse på brukerID
{
  $interesser=$_POST["dineinteresser"];

  require 'kobletil.php';

  $sql = "SELECT interesseID FROM interesser WHERE interesse=?";
  $stmt = mysqli_stmt_init($db);

  if(!mysqli_stmt_prepare($stmt, $sql)) //Får ut error hvis sql setningen ikke kan kjøre
  {
    print("SQL ERROR 1");
    exit();
  }

  else
  {
    mysqli_stmt_bind_param($stmt, "s", $interesser);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rad = mysqli_fetch_assoc($result);

    $interesseID = $rad['interesseID'];

    $sql = "DELETE FROM interesserbruker WHERE interesserbrukerID=? AND interesseIDbruker=?";
    $stmt = mysqli_stmt_init($db);

    if(!mysqli_stmt_prepare($stmt, $sql)) //Får ut error hvis sql setningen ikke kan kjøre
    {
      print("SQL ERROR 1");
      exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "is", $innloggetBrukerID, $interesseID);
      mysqli_stmt_execute($stmt);
      print("<meta http-equiv='refresh' content='0'>");
      print("Interessen :$interesser er nå slettet!");
    }
  }
}


if (isset($_POST ["slettstudieknapp"])) //Sletter valgt studie på brukerID
{
  $studier=$_POST["dinestudier"];
  $innloggetBrukerNavn = $_SESSION['brukerNavn'];
  $innloggetBrukerID = $_SESSION['idbruker'];

  require 'kobletil.php';

  $sql = "SELECT studieID FROM studier WHERE studieID=?";
  $stmt = mysqli_stmt_init($db);

  if(!mysqli_stmt_prepare($stmt, $sql)) //Får ut error hvis sql setningen ikke kan kjøre
  {
    print("SQL ERROR 1");
    exit();
  }
  else
  {
    mysqli_stmt_bind_param($stmt, "s", $studier);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rad = mysqli_fetch_assoc($result);

    $studieID = $rad['studieID'];

    $sql = "DELETE FROM studiebruker WHERE studiebrukerID=? AND studieIDbruker=?";
    $stmt = mysqli_stmt_init($db);

    if(!mysqli_stmt_prepare($stmt, $sql))//Får ut error hvis sql setningen ikke kan kjøre
    {
      print("SQL ERROR 1");
      exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "is", $innloggetBrukerID, $studieID);
      mysqli_stmt_execute($stmt);
      print("<meta http-equiv='refresh' content='0'>");
      print("Interessen :$studier er nå slettet!");
    }
  }
}
if (isset($_POST ["registrerinfo"])) //Lagrer profilbilde av personen
{
  $file = $_FILES['file'];
  $innloggetBruker = $_SESSION['brukerNavn'];
  $lovlig = array("jpg", "jpeg", "png", "gif");
  $fileName = $file["name"];
  $fileType = $file["type"];
  $fileTempName = $file["tmp_name"];
  $filesize = $file["size"];

  $explode = explode(".", $fileName);
  $filnavn = strtolower(end($explode));

  if(in_array($filnavn, $lovlig))
  {
    if($filesize < 400000)
    {
      $navnfil = $innloggetBruker . "." . $filnavn;
      $filedestinasjon = "bilder/profilbilde/" . $navnfil;

      require 'kobletil.php';

      $sql1 = "UPDATE info SET bilde=? WHERE brukerNavn='$innloggetBruker'";
      $stmt1 = mysqli_stmt_init($db);

        if(!mysqli_stmt_prepare($stmt1, $sql1))//Får ut error hvis sql setningen ikke kan kjøre
        {
          print("SQL ERROR 17");
          exit();
        }
        else
        {
          mysqli_stmt_bind_param($stmt1, "s", $navnfil);
          mysqli_stmt_execute($stmt1);

          move_uploaded_file($fileTempName, $filedestinasjon);
          print("<meta http-equiv='refresh' content='0'>");

        }
      }
    }
  }
}
}
}


  ?>
