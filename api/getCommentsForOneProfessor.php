<?php

    require 'dbh.php';


    $inData = getRequestInfo();

    $searchCount = 0;

    //$id = $inData['userId'];
    $teacherId = $inData['teacherId'];
    // $search = '%' . $search . '%';

    // Prepare statement to prevent SQL injection attacks
    $sql = $conn->prepare("SELECT * FROM Comments WHERE teacherId = ?");

    $sql->bind_param("s",$teacherId);
    $sql->execute();
    $result = $sql->get_result();

    // Search came up empty in database
    if($result->num_rows == 0)
    {
        returnWithError("SQL ERROR");
    }
    else
    {
        // While theres another row in search, store it in $row variable
        while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$commentResults .=",";
				$userIdResults .=",";
			}
			$searchCount++;

            $userIdResults .= '"' . $row["userId"] . '"';
			$commentResults .= '"' . $row["data"] . '"';
		}
		returnWithInfo( $userIdResults, $commentResults);
    }

    
    
    
    
    
    

    // Prepare json file to send list of contacts
    function returnWithInfo( $userIdResults, $commentResults)
	{
		$retValue = '{"userId":[' . $userIdResults . '], "comment":[' . $commentResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}

	// Prepare a json file to send an error
	function returnWithError( $err)
    {
      $retValue = '{"id":0,"error":"' . $err . '"}';
      sendResultInfoAsJson($retValue);
    }
    // Send off json
     function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

    // Receive user's id and contact's email, phone number, first and last name
    function getRequestInfo()
    {
		return json_decode(file_get_contents('php://input'),true);
    }
