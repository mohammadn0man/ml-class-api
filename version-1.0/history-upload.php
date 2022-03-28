<?php

/* @var $db Connection */
if ($db && $S && $Data) {
    $Ids = $Data["ids"];
    $Date = $db->checkStr($Data["date"]);
    $Time = $db->checkStr($Data["time"]);
    $Type = intval($db->checkStr($Data["type"]));
    $Exam = $db->checkStr($Data["exam"]);
    $From = intval($db->checkStr($Data["from"]));
    $To = intval($db->checkStr($Data["to"]));
    $db->insert("INSERT INTO `result`(`user_id`, `date`, `time`, `type`, `exam`, `from_row`, `to_row`) VALUES (?,?,?,?,?,?,?)", array($S["user_id"], $Date, $Time, $Type, $Exam, $From, $To));
    $Rid = $db->lastID();
    if ($Rid > 0) {
        foreach ($Ids as $Id) {
            $db->insert("INSERT INTO `result_detail`(`result_id`, `question_id`, `correct_ans`) VALUES (?,?,?)", array($Rid, $Id["qid"], $Id["cas"]));
        }
        $Response["status"] = true;
        $Response["message"] = "Successfully Uploaded.";
    } else {
        $Response["status"] = false;
        $Response["message"] = "Something went wrong.Try again later";
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}