<?php
    require 'dbh.php';


    // To leave a review, we will need to see if the user has a specified
    // weight for each criteria, if not
    // after rating, need to add ratingId to userRatings

    // Read in data from json file
    $inData = getRequestInfo();
    $approachability = $inData['approachabilityRating'];
    $appropiateWork = $inData['appropriateWorkRating'];
    $gradeFairness = $inData['gradeFairnessRating'];
    $lectureEffectiveness = $inData['lectureEffectivenessRating'];
    $knowledgeOfMat = $inData['knowledgeOfMatRating'];
    $teacherId = $inData['teacherId'];
    $userId = $inData['userId'];
    // $courseId = $inData['courseId'];
    $courseId = 3;
    $one = 1;
    
    // If one of the feilds is empty, return an error
    if (empty($approachability) || empty($appropiateWork)|| empty($gradeFairness) || empty($lectureEffectiveness) || empty($knowledgeOfMat))
    {
        // Return error
        returnWithError("One of your values was empty, please redo your rating.");
        exit();
    }

    // If one of the feilds is empty, return an error
    if ($approachability < 0 || $appropiateWork < 0 || $gradeFairness < 0 || $lectureEffectiveness < 0 || $knowledgeOfMat < 0)
    {
        // Return error
        returnWithError("One of your values was negative, please redo your rating.");
        exit();
    }

    // If one of the feilds is empty, return an error
    if ($approachability > 10 || $appropiateWork > 10 || $gradeFairness > 10 || $lectureEffectiveness > 10 || $knowledgeOfMat > 10)
    {
        // Return error
        returnWithError("One of your values was above 10, please redo your rating.");
        exit();
    }

    // Prepare sql query to preven sql injection attacks
    $sql = $conn->prepare("INSERT INTO Rating (approachability, appropriateWork, gradeFairness, LectureEffect, knowledgeOfMaterial, userId, teacherId, courseId) VALUES (?,?,?,?,?,?,?,?)");
    $sql->bind_param("iiiiiiii", $approachability, $appropiateWork, $gradeFairness, $lectureEffectiveness, $knowledgeOfMat, $userId, $teacherId, $courseId);
    $sql->execute();

    $sql = $conn->prepare("UPDATE Teachers SET approachTotal = approachTotal + ?, appropWorkTotal = appropWorkTotal + ?, 
        gradeFairTotal = gradeFairTotal + ?, knowledgeTotal = knowledgeTotal + ?, lectureEffectTotal = lectureEffectTotal + ?,
        totalRatingsDone = totalRatingsDone + ? WHERE teacherId = ?");
    
    $sql->bind_param("iiiiiii", $approachability,$appropiateWork, $gradeFairness, $lectureEffectiveness, $knowledgeOfMat, $one,$teacherId);
    $sql->execute();
    
    returnWithInfo();

    function returnWithInfo()
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
