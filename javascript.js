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
// Denne siden er utviklet av Erik-Andre Ørn, siste gang endret 14.11.2018 //
// Denne siden er kontrollert av Jarle Stølsnes, siste gang 15.11.2018 //
