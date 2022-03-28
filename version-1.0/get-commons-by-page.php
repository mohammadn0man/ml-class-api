<?php

if ($db && $S && $Data) {
    $Type = intval($db->checkStr($Data["type"]));
    $Token = $db->input($Data, "token");
    $Token1 = $db->inputNonR($Data, "token1");
    $Page = intval($db->input($Data, "page"));
    $Start = ($Page - 1) * 10;
    $Search = $db->inputNonR($Data, "search");
    $arr = array();
    if ($Search) {
        switch ($Type) {
            case 1:
                $DataBySearch = "AND `course`.`name` Like concat('%',?,'%') ";
                break;
            case 2:
                $DataBySearch = "AND `subject`.`name` Like concat('%',?,'%') ";
                break;
            case 3:
                $DataBySearch = "AND `chapter`.`name` Like concat('%',?,'%') ";
                break;
            case 4:
                $DataBySearch = "AND `question`.`body` Like concat('%',?,'%') ";
                break;
            case 5:
                $DataBySearch = "AND `user`.`name` Like concat('%',?,'%') ";
                break;
            default:
                throw new Exception("Security error.");
        }
        array_push($arr, $Search);
    } else {
        $DataBySearch = "";
    }
    switch ($Type) {
        case 1:
            $Row1 = $db->select("SELECT token(`course`.`course_id`,'course') AS token,`course`.`name`,CONCAT('Total Subject : ',subject_count(`course`.`course_id`)) AS total,`course`.`status`,CONCAT('Add By : ',`user`.`name`,' on ',DATE_FORMAT(`course`.`add_date`,'%e %b, %Y %h:%i:%s %p')) AS add_date,subject_count(`course`.`course_id`)=0 AS is_delete,`course`.`status`,`report_count`(`course`.`course_id`,1,1) AS reports FROM `course` INNER JOIN `user` ON `user`.`user_id`=`course`.`add_by` WHERE 1 $DataBySearch LIMIT $Start,10", $arr);
            break;
        case 2:
            array_push($arr, $Token);
            $Row1 = $db->select("SELECT token(`subject`.`subject_id`,'subject') AS token,`subject`.`name`,CONCAT('Total Chapter : ',chapter_count(`subject`.`subject_id`)) AS total,`subject`.`status`,CONCAT('Add By : ',`user`.`name`,' on ',DATE_FORMAT(`subject`.`add_date`,'%e %b, %Y %h:%i:%s %p')) AS add_date,chapter_count(`subject`.`subject_id`)=0 AS is_delete,`subject`.`status`,`report_count`(`subject`.`subject_id`,2,1) AS reports FROM `subject` INNER JOIN `user` ON `user`.`user_id`=`subject`.`add_by` WHERE token(`subject`.`course_id`,'course')=? $DataBySearch LIMIT $Start,10", $arr);
            break;
        case 3:
            array_push($arr, $Token);
            $Row1 = $db->select("SELECT token(`chapter`.`chapter_id`,'chapter') AS token,`chapter`.`name`,CONCAT('Total Question : ',question_count(`chapter`.`chapter_id`,0)) AS total,`chapter`.`status`,CONCAT('Add By : ',`user`.`name`,' on ',DATE_FORMAT(`chapter`.`add_date`,'%e %b, %Y %h:%i:%s %p')) AS add_date,question_count(`chapter`.`chapter_id`,0)=0 AS is_delete,`chapter`.`status`,`report_count`(`chapter`.`chapter_id`,3,1) AS reports FROM `chapter` INNER JOIN `user` ON `user`.`user_id`=`chapter`.`add_by` WHERE token(`chapter`.`subject_id`,'subject')=? $DataBySearch LIMIT $Start,10", $arr);
            break;
        case 4:
            if (!$Token1) {
                throw new Exception("Security token issue\nplease update your app");
            }
            array_push($arr, $Token1);
            array_push($arr, $Token);
            $Row1 = $db->select("SELECT token(`question`.`question_id`,'question') AS token,`question`.`type_id`, `question`.`body`, `question`.`tag`, IFNULL(`question`.`hint`,'') AS `hint`, IFNULL(CONCAT('web-photo/question/',`question`.`image`),'') AS `image`, `question`.`option1`,  IFNULL(`question`.`option2`,'') AS `option2`, IFNULL(`question`.`option3`,'') AS `option3`, IFNULL(`question`.`option4`,'') AS `option4`, IFNULL(`question`.`option5`,'') AS `option5`, IFNULL(`question`.`option6`,'') AS `option6`, `question`.`selected`,CONCAT('Add By : ',`user`.`name`,' on ',DATE_FORMAT(`question`.`add_date`,'%e %b, %Y %h:%i:%s %p')) AS add_date,`question`.`status`,`question`.`add_by`=" . $S["user_id"] . "  AS is_delete,`report_count`(`question`.`question_id`,5,1) AS reports FROM `question` INNER JOIN `user` ON `user`.`user_id`=`question`.`add_by` WHERE `question`.`type_id`=? AND token(`question`.`chapter_id`,'chapter')=? $DataBySearch LIMIT $Start,10", $arr);
            break;
        case 5:
            $Row1 = $db->select("SELECT token(`user`.`user_id`,'user') AS token,`user`.`name`,`user`.`email`,`user`.`mobile`,CONCAT('Add By : ',IFNULL(`u1`.`name`,'N/A'),' \nON ',DATE_FORMAT(`user`.`add_date`,'%e %b, %Y %h:%i:%s %p'),'\nCode : ',`user`.`code`) AS add_date,`user`.`status`,CASE WHEN `user`.`type`=2 THEN 'user' WHEN `user`.`type`=1 THEN 'Admin' else 'SubAdmin' end as type FROM `user` left join `user` AS `u1` ON `u1`.`user_id`=`user`.`add_by` $DataBySearch LIMIT $Start,10", $arr);
            break;
        case 6:
            $CPTR = $db->selectRow("SELECT `chapter`.`chapter_id` FROM `chapter` WHERE token(`chapter`.`chapter_id`,'chapter')=?", array($Token));
            $Row1 = $db->select("SELECT `type`.`type_id` AS token, `type`.`name`,CONCAT('Total Question : ',question_count(?,`type`.`type_id`)) AS total,CONCAT('Add By : ',`user`.`name`,' on ',DATE_FORMAT(`type`.`add_date`,'%e %b, %Y %h:%i:%s %p')) AS add_date,1 AS `status`,0 AS is_delete,`report_count`(?,4,`type`.`type_id`) AS reports FROM `type` INNER JOIN `user` ON `user`.`user_id`=`type`.`add_by` LIMIT $Start,10", array($CPTR["chapter_id"], $CPTR["chapter_id"]));
            break;
        case 7:
            $Row1 = $db->select("SELECT token(`report`.`report_id`,'report') AS token,`user`.`name`,`user`.`mobile`,`report`.`comment`,`report`.`solve`,DATE_FORMAT(`report`.`add_date`,'%e %b, %Y %h:%i:%s %p') AS add_date FROM `report` INNER JOIN `user` ON `user`.`user_id`=`report`.`user_id` AND token(`report`.`question_id`,'question')=?", array($Token));
            break;
        default:
            throw new Exception("Security error.");
    }
    $Response["commons"] = ($Row1 == NULL) ? array() : $Row1;
    $Response["status"] = true;
    $Response["message"] = "data.";
} else {
    $Response["status"] = false;
    $Response["message"] = "something went wrong.\nplease try again later.";
}