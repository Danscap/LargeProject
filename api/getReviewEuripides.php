<?php

    // PHP file to get Niel stats
    require 'dbh.php';
    $inData = getRequestInfo();

    $searchCount = 0;
    //Get teacher overall scores

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

    // Get paul gazillos stats
    $sql = $conn->prepare("SELECT * FROM Teachers WHERE teacherId = 8");
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();

    // Get numbers, do maf
    $teacherApproachTotal = $row["approachTotal"];
    $teacherAppropTotal = $row["appropWorkTotal"];
    $teacherGradeTotal = $row["gradeFairTotal"];
    $teacherKnowledgeTotal = $row["knowledgeTotal"];
    $teacherLectureTotal = $row["lectureEffectTotal"];
    $totalRatings = $row["totalRatingsDone"];

    // Calculate averages
    $teacherApproachTotal = $globalApproach * ($teacherApproachTotal / $totalRatings) * 10;
    $teacherAppropTotal = $globalAppropriate * ($teacherAppropTotal / $totalRatings) * 10;
    $teacherGradeTotal = $globalGrade * ($teacherGradeTotal / $totalRatings) * 10;
    $teacherKnowledgeTotal = $globalKnowledge * ($teacherKnowledgeTotal / $totalRatings) * 10;
    $teacherLectureTotal = $globalLecture * ($teacherLectureTotal / $totalRatings) * 10;
    $finalGrade = $teacherApproachTotal + $teacherAppropTotal + $teacherGradeTotal + $teacherKnowledgeTotal + $teacherLectureTotal;
    returnWithInfo($teacherApproachTotal, $teacherAppropTotal, $teacherGradeTotal,
      $teacherKnowledgeTotal, $teacherLectureTotal, $finalGrade);
      
      
      function returnWithInfo( $teacherApproachPercent, $teacherAppropPercent, $teacherGradePercent, $teacherKnowledgePercent, $teacherLecturePercent, $teacherOverall)
	{
		$retValue = '{"teacherApproachPercent":' . $teacherApproachPercent . ', "teacherAppropPercent":' . $teacherAppropPercent . ', "teacherGradePercent":' . $teacherGradePercent . ',"teacherKNowledgePercent":' .
      $teacherKnowledgePercent . ',"teacherLecturePercent":' . $teacherLecturePercent . ',"teacherOverall":' . $teacherOverall . ',"error":""}';
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