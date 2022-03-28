<?php

if ($db && $S && $Data) {
	if($S["type"]==1){
		$Token = $db->input($Data, "token");
		$ExtraW = "";
	}else{
		$Token = $S["token"];
		$ExtraW = "WHERE `course`.`status`=1";
	}
    $Page = intval($db->input($Data, "page"));
    $Start = ($Page - 1) * 10;
    $Search = $db->inputNonR($Data, "search");
    $arr = array($Token);
    if ($Search) {
        $DataBySearch = "WHERE `course`.`name` Like concat('%',?,'%')";
        array_push($arr, $Search);
    } else {
        $DataBySearch = "";
    }
    $Row1 = $db->select("SELECT token(`course`.`course_id`,'course') AS token,`course`.`name`,IFNULL(token(`course_allot`.`course_allot_id`,'course_allot'),'') AS `course_allot_token` FROM `course` LEFT JOIN `course_allot` ON `course_allot`.`course_id`=`course`.`course_id` AND token(`course_allot`.`user_id`,'user')=? ".$ExtraW." ORDER BY IFNULL(`course_allot`.`course_allot_id`,0) $DataBySearch DESC LIMIT $Start,10", $arr);
    $Response["courses"] = ($Row1 == NULL) ? array() : $Row1;
    $Response["status"] = true;
    $Response["message"] = "data.";
} else {
    $Response["status"] = false;
    $Response["message"] = "something went wrong.\nplease try again later.";
}