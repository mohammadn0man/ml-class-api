<?php

if ($db && $S && $Data) {
	
	if($S["type"]==1){
		$Token1 = $db->checkStr($Data["token1"]);
	}else{
		$Token1 = $S["token"];
	}
    $Token2 = $db->checkStr($Data["token2"]);
    $Token3 = $db->checkStr($Data["token3"]);

    $Row1 = $db->selectRow("SELECT `user_id`,`name` FROM `user` WHERE token(`user_id`,'user')=?", array($Token1));
    if ($Row1 != NULL) {
        $Row2 = $db->selectRow("SELECT `course_id` FROM `course` WHERE token(`course_id`,'course')=?", array($Token2));
        if ($Row2 != NULL) {
            $Row3 = $db->selectRow("SELECT `course_allot_id` FROM `course_allot` WHERE token(`course_allot_id`,'course_allot')=? AND `course_id`=? AND `user_id`=?", array($Token3, $Row2["course_id"], $Row1["user_id"]));
            if ($Row3 != NULL) {
                $db->update("DELETE FROM `course_allot` WHERE `course_allot_id`=?", array($Row3["course_allot_id"]));
                $Response["status"] = TRUE;
                $Response["message"] = "Course turn off for " . $Row1["name"] . ".";
                $Response["course_allot_token"] = "";
            } else {
                $db->insert("INSERT INTO `course_allot`(`course_id`, `user_id`, `add_by`) VALUES (?,?,?)", array($Row2["course_id"], $Row1["user_id"], $S["user_id"]));
                $Response["status"] = TRUE;
                $Response["message"] = "Course allot to " . $Row1["name"] . ".";
                $Row3 = $db->selectRow("SELECT token(`course_allot_id`,'course_allot') AS course_allot_token  FROM `course_allot` WHERE `course_id`=? AND `user_id`=?", array($Row2["course_id"], $Row1["user_id"]));
                $Response["course_allot_token"] = $Row3["course_allot_token"];
            }
        } else {
            $Response["status"] = false;
            $Response["message"] = "Something went wrong.Try again later";
        }
    } else {
        $Response["status"] = false;
        $Response["message"] = "Something went wrong.Try again later";
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}