<?php

    require 'dbh.php';


    $inData = getRequestInfo();

    $searchCount = 0;

    //$id = $inData['userId'];
    // $search = $inData['search'];
    $search = '%' . $search . '%';

    // Prepare statement to prevent SQL injection attacks
    $sql = $conn->prepare("SELECT * FROM Courses");

    // $sql->bind_param("ss",$search, $search);
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
				$courseNameResults .=",";
				$courseTitleRestults .=",";
				
			}
			$searchCount++;
			$courseNameResults .= '"' . $row["courseName"] . '"';
			$courseTitleRestults .= '"' . $row["courseTitle"] . '"';
			
		}
		returnWithInfo( $courseNameResults, $courseTitleRestults);
    }

    
    
    
    
    
    
    
    

    // Prepare json file to send list of contacts
    function returnWithInfo( $courseNameResults, $courseTitleRestults )
	{
		$retValue = '{"courseName":[' . $courseNameResults . '], "courseTitle":[' . $courseTitleRestults . '],"error":""}';
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
