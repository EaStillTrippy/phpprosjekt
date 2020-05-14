<?php
if(!isset($_SESSION)){
  session_start();
}
$innloggetBruker=$_SESSION['brukerNavn'];
$innloggetBrukerID=$_SESSION['idbruker'];
if (!$innloggetBruker)
{
header("Location: defaultloggetinn.php");
}
?>
<!-- Denne siden er utviklet av Erik-Andre Ørn, siste gang endret 06.12.2018 -->
<!-- Denne siden er kontrollert av Aziz Fazlagic-Pringanica, siste gang 06.12.2018 -->
