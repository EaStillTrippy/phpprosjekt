<script>
function saveScroll(){
  document.getElementById("scroll") = document.getElementById("container").scrollTop; }
function scrollTil(){
  document.getElementById("container").scrollTop = <?php echo($src); ?>;}

if (<?php echo($scr); ?> > 0) {
  window.onload = scrollTil; }
</script>

//Denne siden er utviklet av Tom Noodt, siste gang endret 01.03.2019
// Denne siden er kontrollert av Erik-Andre Ørn, siste gang 03.03.2019 
