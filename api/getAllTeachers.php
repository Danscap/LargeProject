<?php

    require 'dbh.php';


    $inData = getRequestInfo();

    $searchCount = 0;

    //$id = $inData['userId'];
    // $search = $inData['search'];
    $search = '%' . $search . '%';

    // Prepare statement to prevent SQL injection attacks
    $sql = $conn->prepare("SELECT * FROM Teachers");

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
				$firstNameResults .=",";
				$lastNameRestults .=",";
				$coursesRestults .=",";
			}
			$searchCount++;
			$firstNameResults .= '"' . $row["firstName"] . '"';
			$lastNameRestults .= '"' . $row["lastName"] . '"';
			$coursesRestults .= '"' . $row["courses"] . '"';
		}
		returnWithInfo( $firstNameResults, $lastNameRestults, $coursesRestults);
    }
//     $result->free();
//     $searchCount = 0;
    
//     $sql = $conn->prepare("SELECT courses FROM Teachers");

//     // $sql->bind_param("ss",$search, $search);
//     $sql->execute();
//     $result = $sql->get_result();

//     // Search came up empty in database
//     if($result->num_rows == 0)
//     {
//         returnWithError("SQL ERROR");
//     }
//     else
//     {
//         // While theres another row in search, store it in $row variable
//         while($row = $result->fetch_assoc())
// 		{
// 			if( $searchCount > 0 )
// 			{

// 				$CoursesRestults .=",";
// 			}
// 			$searchCount++;

// 			$CoursesRestults .= '"' . $row["courses"] . '"';
// 		}
// 		returnWithInfo( $firstNameResults, $lastNameRestults,$CoursesRestults );
//     }
    
    
    
    
    
    
    
    

    // Prepare json file to send list of contacts
    function returnWithInfo( $firstNameResults, $lastNameRestults, $coursesRestults)
	{
		$retValue = '{"firstName":[' . $firstNameResults . '], "lastName":[' . $lastNameRestults . '],"courses":[' . $coursesRestults . '],"error":""}';
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
