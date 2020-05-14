<?php
if(!isset($_SESSION))
{
  session_start();
}
@$innloggetBruker=$_SESSION["brukerNavn"];
$adminstatus = $_SESSION["administrator"];
if (!$innloggetBruker)
{
header("Location: ../login.php");
}
if ($adminstatus != '1'){
  header("Location: ../login.php");
}
?>
<!-- Denne siden er utviklet av Erik-Andre Ørn, siste gang endret 06.12.2018 -->
<!-- Denne siden er kontrollert av Aziz Fazlagic-Pringanica , siste gang 07.12.2018 -->
