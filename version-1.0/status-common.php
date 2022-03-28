<?php

if ($db && $Data) {
    $Type = intval($db->checkStr($Data["type"]));
    $Token = $db->checkStr($Data["token"]);
    if ($S["type"] == 1) {
        switch ($Type) {
            case 1:
                $Row1 = $db->selectRow("SELECT `course_id`,`status` FROM `course` WHERE token(`course_id`,'course')=?", array($Token));
                if ($Row1 != NULL) {
                    $NS = $Row1["status"] == 2 ? 1 : 2;
                    $db->delete("update `course` SET `status`=$NS WHERE `course_id`=?", array($Row1['course_id']));
                    $Response["status"] = TRUE;
                    $Response["current_status"] = $NS;
                    $Response["message"] = "Course " . ($NS == 1 ? "activate" : "deactivate") . " successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "Course not found...";
                }
                break;
            case 2:
                $Row1 = $db->selectRow("SELECT `subject_id`,`status` FROM `subject` WHERE token(`subject_id`,'subject')=?", array($Token));
                if ($Row1 != NULL) {
                    $NS = $Row1["status"] == 2 ? 1 : 2;
                    $db->delete("update `subject` SET `status`=$NS where `subject_id`=?", array($Row1['subject_id']));
                    $Response["status"] = TRUE;
                    $Response["current_status"] = $NS;
                    $Response["message"] = "Subject " . ($NS == 1 ? "activate" : "deactivate") . " successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "Subject not found...";
                }
                break;
            case 3:
                $Row1 = $db->selectRow("SELECT `chapter_id`,`status` FROM `chapter` WHERE token(`chapter_id`,'chapter')=?", array($Token));
                if ($Row1 != NULL) {
                    $NS = $Row1["status"] == 2 ? 1 : 2;
                    $db->delete("update `chapter` SET `status`=$NS where `chapter_id`=?", array($Row1['chapter_id']));
                    $Response["status"] = TRUE;
                    $Response["current_status"] = $NS;
                    $Response["message"] = "Chapter " . ($NS == 1 ? "activate" : "deactivate") . " successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "Chapter not found...";
                }
                break;
            case 4:
                $Row1 = $db->selectRow("SELECT `question_id`,`status` FROM `question` WHERE token(`question_id`,'question')=?", array($Token));
                if ($Row1 != NULL) {
                    $NS = $Row1["status"] == 2 ? 1 : 2;
                    $db->delete("update `question` SET `status`=$NS where `question_id`=?", array($Row1['question_id']));
                    $Response["status"] = TRUE;
                    $Response["current_status"] = $NS;
                    $Response["message"] = "Question " . ($NS == 1 ? "activate" : "deactivate") . " successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "Question not found...";
                }
                break;
            case 5:
                $Row1 = $db->selectRow("SELECT `user_id`,`status` FROM `user` WHERE token(`user_id`,'user')=? AND `type`!=1", array($Token));
                if ($Row1 != NULL) {
                    $NS = $Row1["status"] == 2 ? 1 : 2;
                    $db->delete("update `user` SET `status`=$NS where `user_id`=?", array($Row1['user_id']));
                    $Response["status"] = TRUE;
                    $Response["current_status"] = $NS;
                    $Response["message"] = "User " . ($NS == 1 ? "activate" : "deactivate") . " successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "User not found...";
                }
                break;
            default :
                throw new Exception("Security Error");
        }
    } else {
        throw new Exception("Security Error");
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}