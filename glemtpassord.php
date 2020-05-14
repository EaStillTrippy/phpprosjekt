<!DOCTYPE html>
<html>
<head>
  <link title="stilark" href="csslogin.css" rel="stylesheet" type="text/css" media="screen" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Glemt Passord</title>
  <body>
      <div class="loginbox">
        <img src="avatar1.png" class="avatar">
          <h1> Glemt passord</h1>
          <form action="" id="glemtpassord" name="glemtpassord" method="post" autocomplete="off">

          <p>Skriv inn din epost adresse</p> <input name="epost" type="text" id="epost" class="epost" autofocus placeholder="Skriv inn din epost"> <br />

          <input type="submit" name="sendnytt" value="Send nytt passord" class="sendnytt">
          <input type="reset" name="nullstill" value="Nullstill" class="nullstill">
          <a href="login.php">Tilbake</a><br>


          <?php
  if(isset($_POST['sendnytt']) && $_POST['epost'])
  {
    $epost=$_POST["epost"];
  include("kobletil.php");
  $sqlSetning="SELECT * FROM bruker WHERE ePost LIKE '$epost';";
  $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
  $antallRader=mysqli_num_rows($sqlResultat);
  for ($r=1;$r<=$antallRader;$r++){
  $rad=mysqli_fetch_array($sqlResultat);
  $passord=sha1($rad["passord"]);
  $epost=$rad["ePost"];
  $brukernavn=$rad["brukerNavn"];

      $link="<a href='www.home.usn.no/216788/app/reset.php?key=".$epost."&reset=".$passord."'>Click To Reset password</a>";
      require_once('phpmail/PHPMailerAutoload.php');
      $mail = new PHPMailer();
      $mail->CharSet =  "utf-8";
      $mail->IsSMTP();
      // enable SMTP authentication
      $mail->SMTPAuth = true;
      // GMAIL username
      $mail->Username = "gruppe11usn@gmail.com";
      // GMAIL password
      $mail->Password = "lollsk97";
      $mail->SMTPSecure = "ssl";
      // sets GMAIL as the SMTP server
      $mail->Host = "smtp.gmail.com";
      // set the SMTP port for the GMAIL server
      $mail->Port = "465";
      $mail->From='gruppe11usn@gmail.com';
      $mail->FromName='Gruppe 11';
      $mail->AddAddress($epost, $brukernavn);
      $mail->Subject  =  'Reset Password';
      $mail->IsHTML(true);
      $mail->Body    = 'Click On This Link to Reset Password '.$pass.'';
      if($mail->Send())
      {
        echo "Check Your Email and Click on the link sent to your email";
      }
      else
      {
        echo "Mail Error - >".$mail->ErrorInfo;
      }
    }
  }
  ?>
      </div>
  </body>
</head>
</html>
