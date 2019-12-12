<?php
    require 'dbh.php';


    // To leave a review, we will need to see if the user has a specified
    // weight for each criteria, if not
    // after rating, need to add ratingId to userRatings

    // Read in data from json file
    $inData = getRequestInfo();
    $comment = $inData['comment'];
  
    $teacherId = $inData['teacherId'];
    $userId = $inData['userId'];

    
    // If one of the feilds is empty, return an error
    if (empty($comment) )
    {
        // Return error
        returnWithError("no comment");
        exit();
    }



    // Prepare sql query to preven sql injection attacks
    $sql = $conn->prepare("INSERT INTO Comments (data,userId,teacherId) VALUES (?,?,?)");
    $sql->bind_param("sii", $comment, $userId, $teacherId);
    $sql->execute();
    
    returnWithInfo( );

        function returnWithInfo( )
	{
		$retValue = '{"error":""}';
		sendResultInfoAsJson( $retValue );
	}

     function returnWithError( $err)
     {
        // Prepare json file to send error
        $retValue = '{"id":0,"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
     }

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
