 <?php
    // Gives all reviews with percentages according to user's weights (if user
    // has no weights, then uses global weights)
    require 'dbh.php';
    $inData = getRequestInfo();
    $searchCount = 0;

    $teacherId = $inData['teacherId'];
    $userId = $inData['userId'];

    // Get user weights
    $sql = $conn->prepare("SELECT * FROM Users WHERE userId = ?");
    $sql->bind_param("i",$userId);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    if(empty($userId) || $row["personalApproach"] <= 0.000)
    {
        // User has no personal weights, use globa weights
        // Search for global weights, calculate percentages then calculate each 
        // teachers grade
        $sql = $conn->prepare("SELECT * FROM globalWeights");
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();
        // Global Weights
        $globalApproach = $row["globalApproach"] / $row["globalReviewTotal"];
        $globalAppropriate = $row["globalApprop"] / $row["globalReviewTotal"];
        $globalGrade = $row["globalGradeFairness"] / $row["globalReviewTotal"];
        $globalKnowledge = $row["globalKnowledge"] / $row["globalReviewTotal"];
        $globalLecture = $row["globalLectureEffect"] / $row["globalReviewTotal"];
        $sql = $conn->prepare("SELECT * FROM Teachers");
        $sql->execute();
        $result = $sql->get_result();
        if($result->num_rows == 0)
        {
            returnWithError("No teachers were found");
            exit();
        }
        while($row = $result->fetch_assoc())
        {
          // Get numbers, do maf
          $teacherRatings = $row["totalRatingsDone"]; // Dont need for front-end
          $teacherApproachTotal = 10 * ($row["approachTotal"] / $teacherRatings) * $globalApproach;
          $teacherAppropTotal = 10 * ($row["appropWorkTotal"] / $teacherRatings) * $globalAppropriate;
          $teacherGradeTotal = 10 * ($row["gradeFairTotal"] / $teacherRatings) * $globalGrade;
          $teacherKnowledgeTotal = 10 * ($row["knowledgeTotal"] / $teacherRatings) * $globalKnowledge;
          $teacherLectureTotal = 10 * ($row["lectureEffectTotal"] / $teacherRatings) * $globalLecture;
          $teacherFirst = $row["firstName"];
          $teacherLast = $row["lastName"];
          $teacherId = $row["teacherId"];
          $finalGrade = $teacherApproachTotal + $teacherAppropTotal + $teacherGradeTotal + $teacherKnowledgeTotal + $teacherLectureTotal;
          
          if( $searchCount > 0 )
			{
				
				$firstNameResults .=",";
				$lastNameRestults .=",";
				$teacherIdResults .=",";
				$teacherFinalResults .=",";
				$teacherApproachResults .=",";
				$teacherAppropResults .=",";
				$teacherGradeResults .=",";
				$teacherKnowledgeResults .=",";
				$teacherLectureResults .=",";
			}
			$searchCount++;
			$firstNameResults .= '"' . $teacherFirst . '"';
			$lastNameRestults .= '"' . $teacherLast . '"';
			$teacherIdResults .= '"' . $teacherId . '"';
			$teacherFinalResults .= '"' . $finalGrade . '"';
			$teacherApproachResults .= '"' . $teacherApproachTotal . '"';
			$teacherAppropResults .= '"' . $teacherAppropTotal . '"';
			$teacherGradeResults .= '"' . $teacherGradeTotal . '"';
			$teacherKnowledgeResults .= '"' . $teacherKnowledgeTotal . '"';
			$teacherLectureResults .= '"' . $teacherLectureTotal . '"';
          }
          returnWithInfo($firstNameResults, $lastNameRestults, $teacherIdResults,
            $teacherFinalResults, $teacherApproachResults, $teacherAppropResults, $teacherGradeResults, $teacherKnowledgeResults, $teacherLectureResults);
        }
    else {
        // User has personal weights, use their weights
        
        // Assign percentages to variables for personal weights
        $personalApproach = $row["personalApproach"];
        $personalAppropriate = $row["pesronalAppropriate"];
        $personalGrade = $row["personalGrade"];
        $personalKnowledge = $row["personalKnowledge"];
        $personalLecture = $row["personalLecture"];
        
        // Search for teachers
        $sql = $conn->prepare("SELECT * FROM Teachers");
        $sql->execute();
        $result = $sql->get_result();
        if($result->num_rows == 0)
        {
            returnWithError("No teachers were found");
            exit();
        }
        while($row = $result->fetch_assoc())
        {
          // Get numbers, do maf
          $teacherRatings = $row["totalRatingsDone"]; // Dont need for front-end
          $teacherApproachTotal = 10 * ($row["approachTotal"] / $teacherRatings) * $personalApproach;
          $teacherAppropTotal = 10 * ($row["appropWorkTotal"] / $teacherRatings) * $personalAppropriate;
          $teacherGradeTotal = 10 * ($row["gradeFairTotal"] / $teacherRatings) * $personalGrade;
          $teacherKnowledgeTotal = 10 * ($row["knowledgeTotal"] / $teacherRatings) * $personalKnowledge;
          $teacherLectureTotal = 10 * ($row["lectureEffectTotal"] / $teacherRatings) * $personalLecture;
          $teacherFirst = $row["firstName"];
          $teacherLast = $row["lastName"];
          $teacherId = $row["teacherId"];
          $finalGrade = $teacherApproachTotal + $teacherAppropTotal + $teacherGradeTotal + $teacherKnowledgeTotal + $teacherLectureTotal;
          
          if( $searchCount > 0 )
			{
				
				$firstNameResults .=",";
				$lastNameRestults .=",";
				$teacherIdResults .=",";
				$teacherFinalResults .=",";
				$teacherApproachResults .=",";
				$teacherAppropResults .=",";
				$teacherGradeResults .=",";
				$teacherKnowledgeResults .=",";
				$teacherLectureResults .=",";
			}
			$searchCount++;
			$firstNameResults .= '"' . $teacherFirst . '"';
			$lastNameRestults .= '"' . $teacherLast . '"';
			$teacherIdResults .= '"' . $teacherId . '"';
			$teacherFinalResults .= '"' . $finalGrade . '"';
			$teacherApproachResults .= '"' . $teacherApproachTotal . '"';
			$teacherAppropResults .= '"' . $teacherAppropTotal . '"';
			$teacherGradeResults .= '"' . $teacherGradeTotal . '"';
			$teacherKnowledgeResults .= '"' . $teacherKnowledgeTotal . '"';
			$teacherLectureResults .= '"' . $teacherLectureTotal . '"';
          }
          returnWithInfo($firstNameResults, $lastNameRestults, $teacherIdResults,
            $teacherFinalResults, $teacherApproachResults, $teacherAppropResults, $teacherGradeResults, $teacherKnowledgeResults, $teacherLectureResults);
        }

    // Prepare json file to send list of contacts
    function returnWithInfo($firstNameResults, $lastNameRestults, $teacherIdResults,
            $teacherFinalResults, $teacherApproachResults, $teacherAppropResults, $teacherGradeResults, $teacherKnowledgeResults, $teacherLectureResults)
	{
		$retValue = '{"firstName":[' . $firstNameResults . '], "lastName":[' . $lastNameRestults . '], "teacherId":[' . $teacherIdResults . '],"teacherOverall":[' .
      $teacherFinalResults . '],"teacherApproach":[' . $teacherApproachResults . '],"teacherApprop":[' . $teacherAppropResults . '],"teacherGradeFair":[' . $teacherGradeResults . '],
      "teacherKnowledge":[' . $teacherKnowledgeResults . '],"teacherLecture":[' . $teacherLectureResults . '],"error":""}';
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
