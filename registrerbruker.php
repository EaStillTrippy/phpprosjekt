<!DOCTYPE html>
<html>
<head>
  <link title="stilark" href="stilark.css" rel="stylesheet" type="text/css" media="screen" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register ny bruker</title>
  <body>
      <div class="loginbox2">
        <img src="avatar1.png" class="avatar">
          <h1> Register ny bruker</h1>
          <form action="" id="registrerBrukerSkjema" name="registrerBrukerSkjema" method="post" autocomplete="off">
          Brukernavn: <br> <input name="brukernavn" type="text" id="brukernavn" class="brukernavn" autofocus placeholder="Skriv inn brukernavn" autocomplete="off" required><br />
          E-post: <br> <input name="epost" type="email" id="epost" class="epost" placeholder="Skriv eposten din her" autocomplete="off" required> <br />
          Passord (min 6 tegn): <br> <input name="passord" type="password" id="passord" class="passord" placeholder="Skriv inn passord" autocomplete="off" required>  <br />
          gjenta passord: <br> <input name="passord2" type="password" id="passord2" class="passord2" placeholder="Skriv inn passord på nytt" autocomplete="off" required>  <br />
          <input type="submit" name="registrerBrukerKnapp" value="Registrer bruker" class="registrer"> <br>
            <a href="default.php">Tilbake til Logg Inn</a><br>
          <?php
          if (isset($_POST ["registrerBrukerKnapp"]))
          {
          include("kobletil.php");
          $brukernavn=$_POST ["brukernavn"];
          $passord=$_POST["passord"];
          $epost=$_POST["epost"];
          $passord2=$_POST["passord2"];
          $salt = "IT2_2019".$passord;
          $kryptertpassord=sha1($salt);
           if (!$brukernavn || !$passord || !$epost || !$passord2)
            {
            die("Alle feltene må fylles ut! <br />");
            }
            else if (strlen($passord) < 6){
              die('Passord må inneholde minst 6 tegn');
            }
            else if ($passord !== $passord2){
              die('Passordene matcher ikke, prøv igjen!');
            }
            else
            {
              $sql = "SELECT brukerNavn FROM bruker WHERE brukerNavn=?";
              $stmt = mysqli_stmt_init($db);
              if(!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: registrerbruker.php?error=sqlerror");
                exit();
              }
              else{
                mysqli_stmt_bind_param($stmt, "s", $brukernavn);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultat = mysqli_stmt_num_rows($stmt);
                if($resultat > 0) {
                  die("Brukernavnet er registrert fra før <br />");

                }
                else {
                  $sql2 = "SELECT ePost FROM bruker WHERE ePost=?";
                  $stmt2 = mysqli_stmt_init($db);
                  if(!mysqli_stmt_prepare($stmt2, $sql2)){
                  header("Location: registrerbruker.php?error=sqlerror");
                  exit();
                  }
                  mysqli_stmt_bind_param($stmt2, "s", $epost);
                  mysqli_stmt_execute($stmt2);
                  mysqli_stmt_store_result($stmt2);
                  $resultat2 = mysqli_stmt_num_rows($stmt2);
                  if ($resultat2 > 0) {
                      die("Eposten er registrert fra før <br />");
                      exit();
                }
                else {
                  $sql = "INSERT INTO bruker (brukerNavn, ePost, passord, status, administrator) VALUES (?,?,?,?,?)";
                  $stmt = mysqli_stmt_init($db);
                  if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: registrerbruker.php?error=sqlerror1");
                    exit();
                  }
                  else {
                    $status = '0';
                    $administrator = '0';
                    $sql1 = "INSERT INTO info (brukerNavn) VALUES (?)";
                    $stmt1 = mysqli_stmt_init($db);
                    mysqli_stmt_prepare($stmt1, $sql1);
                    mysqli_stmt_bind_param($stmt1, "s", $brukernavn);
                    mysqli_stmt_execute($stmt1);
                    mysqli_stmt_bind_param($stmt, "sssii", $brukernavn, $epost, $kryptertpassord, $status, $administrator);
                    mysqli_stmt_execute($stmt);

                    print ("Følgende data er nå registrert: <br /> ");
                    print ("Brukernavn: $brukernavn <br /> Epost: $epost <br> Passord: $passord <br />  <br />");
                    session_start();$_SESSION["brukernavn"]=$brukernavn;
                    print ("<a href='default.php'>Går til hovedside </a>");
                    header("refresh:10 default.php");
                    die();
                  }
                }
              }
            }
          }
        }

            ?>

          </form>
      </div>
  </body>

  <?php
  include("slutt.html")
   ?>
<!-- Denne siden er utviklet av Jarle Stølsnes, siste gang endret 06.12.2018 -->
<!-- Denne siden er kontrollert av Aziz Fazlagic-Pringanica, siste gang 07.12.2018 -->
