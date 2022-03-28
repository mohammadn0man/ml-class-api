<?php

if ($db && $Data) {
    $Type = intval($db->checkStr($Data["type"]));
    $Token = $db->checkStr($Data["token"]);
    if ($S["type"] == 1) {
        switch ($Type) {
            case 1:
                $Row1 = $db->selectRow("SELECT `course_id` FROM `course` WHERE token(`course_id`,'course')=?", array($Token));
                if ($Row1 != NULL) {
                    $db->delete("delete from `course` where `course_id`=?", array($Row1['course_id']));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Course deleted successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "Course not found...";
                }
                break;
            case 2:
                $Row1 = $db->selectRow("SELECT `subject_id` FROM `subject` WHERE token(`subject_id`,'subject')=?", array($Token));
                if ($Row1 != NULL) {
                    $db->delete("delete from `subject` where `subject_id`=?", array($Row1['subject_id']));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Subject deleted successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "Subject not found...";
                }
                break;
            case 3:
                $Row1 = $db->selectRow("SELECT `chapter_id` FROM `chapter` WHERE token(`chapter_id`,'chapter')=?", array($Token));
                if ($Row1 != NULL) {
                    $db->delete("delete from `chapter` where `chapter_id`=?", array($Row1['chapter_id']));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Chapter deleted successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "Chapter not found...";
                }
                break;
            case 4:
                $Row1 = $db->selectRow("SELECT `question_id` FROM `question` WHERE token(`question_id`,'question')=? AND `add_by`=?", array($Token,$S["user_id"]));
                if ($Row1 != NULL) {
                    $db->delete("delete from `question` where `question_id`=?", array($Row1['question_id']));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Question deleted successfully.";
                } else {
                    $Response["status"] = false;
                    $Response["message"] = "Question not found...";
                }
                break;
            case 5:
                $Row1 = $db->selectRow("SELECT `user_id` FROM `user` WHERE token(`user_id`,'user')=? AND `type`!=1", array($Token));
                if ($Row1 != NULL) {
                    $db->delete("delete from `user` where `user_id`=?", array($Row1['user_id']));
                    $Response["status"] = TRUE;
                    $Response["message"] = "User deleted successfully.";
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