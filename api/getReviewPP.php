<?php


    // PHP file to get a teachers (single) overall grade (all classes combined) and display that overall with users
    // personal weights, if none, then using the global average.
    require 'dbh.php';


    $inData = getRequestInfo();

    $searchCount = 0;

    $teacherId = $inData['teacherId'];
    $userId = $inData['userId'];

    //Get teacher overall scores
    $sql = $conn->prepare("SELECT * FROM Teachers WHERE teacherId = ?");
    $sql->bind_param("i",$teacherId);
    $sql->execute();
    $result = $sql->get_result();
    if($result->num_rows == 0)
    {
        returnWithError("This teacher's ratings were not found in the database.");
    }
    else {
      $row = $result->fetch_assoc();
      // Get numbers, do maf
      $teacherApproachTotal = $row["approachTotal"];
      $teacherAppropTotal = $row["appropWorkTotal"];
      $teacherGradeTotal = $row["gradeFairTotal"];
      $teacherKnowledgeTotal = $row["knowledgeTotal"];
      $teacherLectureTotal = $row["lectureEffectTotal"];
      $totalRatings = $row["totalRatingsDone"];

      // Calculate averages
      $teacherApproachTotal = ($teacherApproachTotal / $totalRatings) * 10;
      $teacherAppropTotal = ($teacherAppropTotal / $totalRatings) * 10;
      $teacherGradeTotal = ($teacherGradeTotal / $totalRatings) * 10;
      $teacherKnowledgeTotal = ($teacherKnowledgeTotal / $totalRatings) * 10;
      $teacherLectureTotal = ($teacherLectureTotal / $totalRatings) * 10;
      // May need to return teacher name too

      // Get user weights
      $sql = $conn->prepare("SELECT * FROM Users WHERE userId = ?");
      $sql->bind_param("i",$userId);
      $sql->execute();
      $result = $sql->get_result();
      $row = $result->fetch_assoc();
      $personalApproach = $row["personalApproach"];
      if ($personalApproach <= 0.000 || empty($userId))
      {
          // User has no personal weights, we need to use global.
          $sql = $conn->prepare("SELECT * FROM globalWeights");
          $sql->execute();
          $result = $sql->get_result();
          $row = $result->fetch_assoc();

          // Get average for each teacher criteria
          $globalApproach = $row["globalApproach"] / $row["globalReviewTotal"];
          $globalAppropriate = $row["globalApprop"] / $row["globalReviewTotal"];
          $globalGrade = $row["globalGradeFairness"] / $row["globalReviewTotal"];
          $globalKnowledge = $row["globalKnowledge"] / $row["globalReviewTotal"];
          $globalLecture = $row["globalLectureEffect"] / $row["globalReviewTotal"];

          // Calculate each criteria multiplied by weight and total.
          $globalApproach = $globalApproach * $teacherApproachTotal;
          $globalAppropriate = $globalAppropriate * $teacherAppropTotal;
          $globalGrade = $globalGrade * $teacherGradeTotal;
          $globalKnowledge = $globalKnowledge * $teacherKnowledgeTotal;
          $globalLecture = $globalLecture * $teacherLectureTotal;
          $finalGrade = $globalApproach + $globalAppropriate + $globalGrade
          + $globalKnowledge + $globalLecture;
      }
      else {
        // The user has his/her own personal weights
        $personalApproach = $teacherApproachTotal * $personalApproach;
        $personalAppropriate = $row["pesronalAppropriate"] * $teacherAppropTotal;
        $personalGrade = $row["personalGrade"] * $teacherGradeTotal;
        $personalKnowledge = $row["personalKnowledge"] * $teacherKnowledgeTotal;
        $personalLecture = $row["personalLecture"] * $teacherLectureTotal;
        $finalGrade = $personalApproach + $personalAppropriate + $personalGrade
        + $personalKnowledge + $personalLecture;
      }
      returnWithInfo($teacherApproachTotal, $teacherAppropTotal, $teacherGradeTotal,
        $teacherKnowledgeTotal, $teacherLectureTotal, $finalGrade);
    }

    // Prepare json file to send list of contacts
    function returnWithInfo( $teacherApproachPercent, $teacherAppropPercent, $teacherGradePercent, $teacherKnowledgePercent, $teacherLecturePercent, $teacherOverall)
	{
		$retValue = '{"teacherApproachPercent":[' . $teacherApproachPercent . '], "teacherAppropPercent":[' . $teacherAppropPercent . '], "teacherGradePercent":[' . $teacherGradePercent . '],"teacherKNowledgePercent":[' .
      $teacherKnowledgePercent . '],"teacherLecturePercent":[' . $teacherLecturePercent . '],"teacherOverall":[' . $teacherOverall . '],"error":""}';
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
