<?php

   $servername = "localhost";
   $dBUsername = "GCGuillen";
   $dBPassword = "PooptoPoos1.";

   // Change "Test" to new Database name
   $dBName = "TradeMyProfessor";

  // Establish connection
  $conn = new mysqli($servername, $dBUsername, $dBPassword, $dBName);
  
  // Check to see if connection had an error
   if ($conn->connect_error)
   {
      die("Connection failed: ".mysqli_connect_error());
   }

?>
