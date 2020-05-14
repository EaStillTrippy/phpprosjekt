<?php
include("sjekkomloggetinn.php");
if(!$innloggetBruker){
  header("Location: default.php");
}
else{
  include("start2.html");
  include("slutt.html");
  print("<h1>Velkommen til startsiden, du er nå logget inn!</h1>");
}


?>

<!-- Denne siden er utviklet av Erik-Andre Ørn, siste gang endret 23.11.2018 -->
<!-- Denne siden er kontrollert av Tom Noodt, siste gang 03.12.2018 -->
