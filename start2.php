<?php
include("sjekkomloggetinn.php");
?>
<html>
    <head>
        <title>USN Alumni</title>
        <link title="stilark" href="stilark.css" rel="stylesheet" type="text/css" media="screen" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
<body class="body">
    <div id="hele">
        <header>
          <a href="defaultloggetinn.php">
            <img src="USN_logo_rgb.png" alt="USN logo">
          </a>

        </header>
        <nav class="navbar">

          <li style="float:right"><a href="loggut.php"> Logg ut</a></li>
          <li style="float:right"><a href="minside.php"> Min Side</a></li>
              <div class="dropdown">
                <button class="dropbtn" onclick="dropdown()">
                  Meny
                </button>
                <div class="dropdown-content" id="ourDropdown">
                <li><a href=".php"> TBD</a></li>
                <li><a href=".php"> TBD</a></li>
              </div>
            </div>
            <li class=><a href="defaultloggetinn.php"> Hjem </a></li>


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

<!-- Denne siden er utviklet av Jarle Stølsnes, siste gang endret 23.11.2018 -->
<!-- Denne siden er kontrollert av Tom Noodt, siste gang 04.12.2018 -->
