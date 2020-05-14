<!-- /* Laget av Erik-Andre Ørn */  -->

<!-- Scriptseksjon før body taggen i html koden -->


<!DOCTYPE html>
<html>
<head>
  <link title="stilark" href="csslogin.css" rel="stylesheet" type="text/css" media="screen" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Logg Inn</title>
  <body>
      <div class="loginbox">
        <img src="avatar1.png" class="avatar">
          <h1> Logg Inn</h1>
          <form action="" id="innloggingSkjema" name="innloggingSkjema" method="post" autocomplete="off">

          <p>Brukernavn</p> <input name="username" type="text" id="username" class="username" autofocus placeholder="Skriv inn brukernavn"> <br />
          <p>Passord</p> <input name="password" type="password" id="password" class="password" placeholder="Skriv inn passord" >  <br />
          <input type="submit" name="logginnKnapp" value="Logg inn" class="logginn">
          <input type="reset" name="nullstill" value="Nullstill" class="nullstill">
          <a href="#">Glemt passordet?</a><br>
          <a href="registrerbruker.php">Registrer ny bruker</a><br>
          <a href="adminlogin.php">Administrator? Logg inn her</a><br>

          <?php
          if (isset($_POST ["logginnKnapp"]))
          {
            require 'kobletil.php';

          $brukernavn=$_POST["username"];
          $passord=$_POST["password"];
          $salt = "IT2_2019".$passord;
          $dekrypterpassord=sha1($salt);
           $dato = date("Y-m-d H:i:s");
           $tid = date('Y-m-d H:i:s',strtotime('-3 minutes',strtotime($dato)));







          if (!$brukernavn || !$passord)
          {
            print("Brukernavn eller passord er ikke fylt ut!");
            die();
          }
          else{
          $sql = "SELECT * FROM bruker WHERE brukerNavn=?";
          $stmt = mysqli_stmt_init($db);
          if(!mysqli_stmt_prepare($stmt,$sql)){
            header("Location: login.php?error=sqlerror1");
            exit();
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $brukernavn);
            mysqli_stmt_execute($stmt);
            $resultat = mysqli_stmt_get_result($stmt);

            if($rad = mysqli_fetch_assoc($resultat)){
              if($dekrypterpassord == $rad['passord']){
                $passordsjekk = true;
              }

              else{
                $passordsjekk = false;

              }
              if($passordsjekk == false){
                if($rad['feilLogginnnTeller'] > 3) {
                  if(($rad['feilLogginnSiste']) < $tid){
                    $feilsql = "UPDATE bruker SET feilLogginnnTeller=?, feilLogginnSiste=?, feilIP=? WHERE brukerNavn=?";
                    $stmt = mysqli_stmt_init($db);
                    if(!mysqli_stmt_prepare($stmt,$feilsql)){
                      header("Location: login.php?error=sqlerror1");
                      exit();
                    }
                    else {
                      mysqli_stmt_bind_param($stmt, "isss", $feilLogginnnTeller, $feilLogginnSiste, $feilIP, $brukernavn);
                      $feilLogginnnTeller = ($rad['feilLogginnnTeller'] = 1);
                      $feilLogginnSiste = $dato;
                      $feilIP = $_SERVER["REMOTE_ADDR"];
                      mysqli_stmt_execute($stmt);
                      header("Location: loggin.php?error=wrongpwd");
                      exit();
                    }
                  }
                  else {
                    print("Feil passord tastet inn for mange ganger, vent 3 minutter og prøv igjen!");
                    exit();
                  }
                }
                else{
                $feilsql = "UPDATE bruker SET feilLogginnnTeller=?, feilLogginnSiste=?, feilIP=? WHERE brukerNavn=?";
                $stmt = mysqli_stmt_init($db);
                if(!mysqli_stmt_prepare($stmt,$feilsql)){
                  header("Location: login.php?error=sqlerror2");
                  exit();
                }
                else {
                  mysqli_stmt_bind_param($stmt, "isss", $feilLogginnnTeller, $feilLogginnSiste, $feilIP, $brukernavn);
                  $feilLogginnnTeller = ($rad['feilLogginnnTeller']+1);
                  $feilLogginnSiste = $dato;
                  $feilIP = $_SERVER["REMOTE_ADDR"];
                  mysqli_stmt_execute($stmt);
                  header("Location: login.php?error=feilpassord1");
                  exit();
                }
              }
              }
              $status = $rad["status"];
              if($status == '2')
              {
                print("Denne brukeren er utestengt på ubestemt tid!");
                die();
              }
              else if($passordsjekk == true){
                if($brukernavn == 'admin'){
                  header("Location: adminlogin.php");
                  exit();
                }
              else{
                if($rad['feilLogginnnTeller'] > 3) {
                  if(($rad['feilLogginnSiste']) < $tid) {
                    $RiktigLogginnsql = "UPDATE bruker SET feilLogginnnTeller=?, feilLogginnSiste=?, feilIP=? WHERE brukerNavn=?";
                    $stmt = mysqli_stmt_init($db);
                    if (!mysqli_stmt_prepare($stmt, $RiktigLogginnsql)){
                      header("Location: login.php?error=sqlerror3");
                      exit();
                    }
                    else {
                      mysqli_stmt_bind_param($stmt, "isss",$feilLogginnnTeller, $feilLogginnSiste, $feilIP, $brukernavn);
                      $feilLogginnnTeller = ($rad['feilLogginnnTeller'] = 0);
                      $feilLogginnSiste = $dato;
                      $feilIP = $_SERVER["REMOTE_ADDR"];
                      mysqli_stmt_execute($stmt);
                      session_start();
                      $_SESSION['brukernavn'] = $rad['brukernavn'];
                      header("Location: defaultloggetinn.php");
                      exit();
                    }
                  }
                  print("Tastet feil passord for mange ganger, vent 3 minutter og prøv igjen!");
                  exit();
                }
                else {
                  $RiktigLogginnsql = "UPDATE bruker SET feilLogginnnTeller=?, feilLogginnSiste=?, feilIP=? WHERE brukerNavn=?";
                  $stmt = mysqli_stmt_init($db);
                  if(!mysqli_stmt_prepare($stmt, $RiktigLogginnsql)){
                    header("Location: login.php?error=sqlerror4");
                    exit();
                  }
                  else{
                    mysqli_stmt_bind_param($stmt, "isss",$feilLogginnnTeller,$feilLogginnSiste,$feilIP,$brukernavn);
                    $feilLogginnnTeller = ($rad['feilLogginnnTeller'] = 0);
                    $feilLogginnSiste = $dato;
                    $feilIP = $_SERVER["REMOTE_ADDR"];
                    mysqli_stmt_execute($stmt);
                    session_start();
                    $_SESSION['brukerNavn'] = $rad['brukerNavn'];
                    $_SESSION['idbruker'] = $rad['idbruker'];
                    header("Location: defaultloggetinn.php");
                    exit();
                  }
                }
              }
            }
          }
            else {
              header("Location: login.php?error=finnesikke");
              exit();
            }
          }
        }
      }


          ?>
