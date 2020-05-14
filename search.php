<?php
include("sjekkomloggetinn.php");
include("start2.html");

print("<h1> Treff på ditt søk: </h1>");
if (isset($_POST ["search"]))
  {
    $sokidatabasen=$_POST ["sokidatabasen"];

    include("kobletil.php");

    print ("Treff for søket <strong>$sokidatabasen</strong> i databasen  <br /><br />");
    $sql="SELECT DISTINCT bruker.idbruker FROM bruker
    LEFT JOIN studiebruker ON bruker.idbruker = studiebruker.studiebrukerID
    LEFT JOIN studier ON  studiebruker.studieIDbruker = studier.studieID
    LEFT JOIN interesserbruker ON bruker.idbruker = interesserbruker.interesserbrukerID
    LEFT JOIN interesser ON interesserbruker.interesseIDbruker = interesser.interesseID
    WHERE bruker.brukerNavn=? OR interesser.interesse=? OR studier.studier=?;";
	
    print ("<table border=1 align='center'");
    print ("<tr><th align=left>Brukernavn </th> <th align=left> Epost</th> <th align=left> Beskrivelse </th> <th align=left> Bilde </th></tr>");
    $stmt= mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt, $sql))  //Får ut error hvis sql setningen ikke kan kjøre
    {
      print("SQL ERROR 1");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt, "sss", $sokidatabasen, $sokidatabasen, $sokidatabasen);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);


      while($rad = mysqli_fetch_assoc($result))
     {
        $idbruker = $rad['idbruker'];
        $sql1="SELECT bruker.brukerNavn, bruker.ePost, info.bio, info.bilde FROM bruker LEFT JOIN info ON bruker.brukerNavn = info.brukerNavn WHERE bruker.idbruker=?";
        $stmt1= mysqli_stmt_init($db);
        if(!mysqli_stmt_prepare($stmt1, $sql1))  //Får ut error hvis sql setningen ikke kan kjøre
        {
          print("SQL ERROR 1");
          exit();
        }
        else{
          mysqli_stmt_bind_param($stmt1, "i", $idbruker);
          mysqli_stmt_execute($stmt1);
          $resultat1 = mysqli_stmt_get_result($stmt1);

          while($rad1 = mysqli_fetch_assoc($resultat1)){
            $brukernavn = $rad1['brukerNavn'];
            $ePost = $rad1['ePost'];
            $bio = $rad1['bio'];
            $bilde = $rad1['bilde'];
            print("<tr> <td> $brukernavn  </td> <td> $ePost </td> <td> $bio </td> <td> <img src='bilder/profilbilde/$bilde' style='width:100%; max-width:200px; max-height:200px;'/></tr>");
          }
        }
      }
        print ("</table> </br />");
}
}

      include("slutt.html");
?>
