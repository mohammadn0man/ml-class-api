<?php

if ($db && $S && $Data) {
    $Id = intval($db->checkStr($Data["id"]));
    $Comment = $db->checkStr($Data["comment"]);
    $Row1 = $db->selectRow("SELECT `question_id` FROM `question` WHERE `question_id`=?", array($Id));
    if ($Row1 != NULL) {
        $Row2 = $db->selectRow("SELECT `report_id` FROM `report` WHERE `user_id`=? AND `comment`=? AND `question_id`=?", array($S["user_id"], $Comment, $Row1["question_id"]));
        if ($Row2 == NULL) {
            $db->insert("INSERT INTO `report`(`user_id`, `comment`, `question_id`) VALUES (?,?,?)", array($S["user_id"], $Comment, $Row1["question_id"]));
            $Response["status"] = true;
            $Response["message"] = "Successfully Reported.";
        } else {
            $Response["status"] = false;
            $Response["message"] = "Already Reported.";
        }
    } else {
        $Response["status"] = false;
        $Response["message"] = "Something went wrong.Try again later";
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}