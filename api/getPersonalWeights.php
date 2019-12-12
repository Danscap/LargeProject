<?php
    require 'dbh.php';

    // Read in data from json file
    $inData = getRequestInfo();
    $username = $inData['username'];
    // $userId = $inData['userId'];
    
    // If one of the feilds is empty, return an error
    // if (empty($userId) || empty($username))
    // {
    //     // Return error
    //     returnWithError("One of your values was empty, please redo your rating.");
    //     exit();
    // }


    $sql = $conn->prepare("SELECT * FROM Users WHERE username =?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();
    
    // Search came up empty in database
    if($result->num_rows == 0)
    {
        returnWithError("Could not get Personal Weights from Database");
    }
    else
    {
        // While theres another row in search, store it in $row variable
        $row = $result->fetch_assoc();
        
		$personalApproach = '"'. $row["totalApproach"] . '"';
        $personalGrade = '"' . $row["totalGrade"] . '"';
        $personalKnowledge = '"' . $row["totalKnowledge"] . '"';
        $personalLecture = '"' . $row["totalLecture"] . '"';
        $personalAppropriate = '"' . $row["totalAppropriate"] . '"';
        
        // $personalApproach =  $row["personalApproach"];
        // $personalGrade =  $row["personalGrade"] ;
        // $personalKnowledge =  $row["personalKnowledge"] ;
        // $personalLecture =  $row["personalLecture"] ;
        // $personalAppropriate =  $row["personalAppropriate"];
		
		returnWithInfo($personalApproach,$personalGrade, $personalKnowledge, $personalLecture, $personalAppropriate );
    }
    
    function returnWithInfo($personalApproach,$personalGrade, $personalKnowledge, $personalLecture, $personalAppropriate )
	{
		$retValue = '{"personalApproach":' . $personalApproach . ', "personalGrade":' . $personalGrade . ', "personalKnowledge":' . $personalKnowledge . ', "personalLecture":' . $personalLecture . ', "personalAppropriate":' . $personalAppropriate . ', "error":""}';
		sendResultInfoAsJson( $retValue );
	}
    // Return info with error
    function returnWithError( $err)
    {
       // Prepare json file to send error
       $retValue = '{"id":0,"error":"' . $err . '"}';
       sendResultInfoAsJson($retValue);
    }

    // Send json file
    function sendResultInfoAsJson( $obj )
    {
      // Send json file
      header('Content-type: application/json');
      echo $obj;
    }

   // Receive user's id and contact's email, phone number, first and last name
   function getRequestInfo()
   {
   return json_decode(file_get_contents('php://input'),true);
   }
