<?php

/* @var $db Connection */
if ($db && $S) {
	if($S["prime"]==1){
		$run=true;
		if($S["expire"]==""){
			if($S["days"]>4){
				$run=false;
				$Response["status"] = false;
				$Response["code"] = true;
				$Response["message"] = "Account Expired.";
			}else{
				$Row = $db->select("SELECT `question`.`question_id`,`course`.`name` AS course,`subject`.`name` AS subject,`chapter`.`name` AS `chapter`,`question`.`type_id` AS `type`, `question`.`body`, `question`.`tag`, IFNULL(`question`.`hint`,'') AS `hint`, IFNULL(CONCAT('web-photo/question/',`question`.`image`),'') AS `image`, `question`.`option1`,  IFNULL(`question`.`option2`,'') AS `option2`, IFNULL(`question`.`option3`,'') AS `option3`, IFNULL(`question`.`option4`,'') AS `option4`, IFNULL(`question`.`option5`,'') AS `option5`, IFNULL(`question`.`option6`,'') AS `option6`, `selected` FROM `question` INNER JOIN `chapter` ON `question`.`status`=1 AND `chapter`.`chapter_id`=`question`.`chapter_id` AND  `chapter`.`status`=1 INNER JOIN `subject` ON `subject`.`subject_id`=`chapter`.`subject_id` AND `subject`.`status`=1 INNER JOIN `course` ON `course`.`course_id`=`subject`.`course_id` AND `course`.`status`=1 INNER JOIN `course_allot` ON `course_allot`.`course_id`=`course`.`course_id` AND `course_allot`.`user_id`=?", array($S["user_id"]));
			}
		}else {
			$Row = $db->select("SELECT `question`.`question_id`,`course`.`name` AS course,`subject`.`name` AS subject,`chapter`.`name` AS `chapter`,`question`.`type_id` AS `type`, `question`.`body`, `question`.`tag`, IFNULL(`question`.`hint`,'') AS `hint`, IFNULL(CONCAT('web-photo/question/',`question`.`image`),'') AS `image`, `question`.`option1`,  IFNULL(`question`.`option2`,'') AS `option2`, IFNULL(`question`.`option3`,'') AS `option3`, IFNULL(`question`.`option4`,'') AS `option4`, IFNULL(`question`.`option5`,'') AS `option5`, IFNULL(`question`.`option6`,'') AS `option6`, `selected` FROM `question` INNER JOIN `chapter` ON `question`.`status`=1 AND `chapter`.`chapter_id`=`question`.`chapter_id` AND  `chapter`.`status`=1 INNER JOIN `subject` ON `subject`.`subject_id`=`chapter`.`subject_id` AND `subject`.`status`=1 INNER JOIN `course` ON `course`.`course_id`=`subject`.`course_id` AND `course`.`status`=1 INNER JOIN `course_allot` ON `course_allot`.`course_id`=`course`.`course_id` AND `course_allot`.`user_id`=?", array($S["user_id"]));
		}
		if($run){
			if ($Row == NULL) {
				$Response["status"] = FALSE;
				$Response["course"] = true;
				$Response["message"] = "Course not selected.";
			} else {
				$Response["questions"] = $Row;
				$Rows = $db->select("SELECT `result_id`,`date`,`time`,`type`,`exam`,`from_row`,`to_row` FROM `result` WHERE `user_id`=?", array($S["user_id"]));
				if ($Rows != null) {
					for ($i = 0; $i < count($Rows); $i++) {
						$Questions = $db->select("SELECT `question`.`question_id`,`course`.`name` AS course,`subject`.`name` AS subject,`chapter`.`name` AS `chapter`,`question`.`type_id` AS `type`, `question`.`body`, `question`.`tag`, IFNULL(`question`.`hint`,'') AS `hint`, IFNULL(CONCAT('web-photo/question/',`question`.`image`),'') AS `image`, `question`.`option1`,  IFNULL(`question`.`option2`,'') AS `option2`, IFNULL(`question`.`option3`,'') AS `option3`, IFNULL(`question`.`option4`,'') AS `option4`, IFNULL(`question`.`option5`,'') AS `option5`, IFNULL(`question`.`option6`,'') AS `option6`, `selected`,`result_detail`.`correct_ans` FROM `result_detail` INNER JOIN `question` ON `result_detail`.`result_id`=? AND `result_detail`.`question_id`=`question`.`question_id` INNER JOIN `chapter` ON `chapter`.`chapter_id`=`question`.`chapter_id` INNER JOIN `subject` ON `subject`.`subject_id`=`chapter`.`subject_id` INNER JOIN `course` ON `course`.`course_id`=`subject`.`course_id`", array($Rows[$i]["result_id"]));
						unset($Rows[$i]["result_id"]);
						$Rows[$i]["questions"] = $Questions == NULL ? array() : $Questions;
					}
					$Response["history"] = $Rows;
				}
				$Response["status"] = true;
				$Response["message"] = "Successfully Synchronized.";
			}
		}
	}else{
		$Response["status"] = false;
		$Response["code"] = true;
		$Response["message"] = "Account Expired.";
	}
} else {
    $Response["status"] = false;
    $Response["message"] = "something went wrong.\nplease try again later.";
}