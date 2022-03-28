<?php

if ($db && $S && $Data) {
    $Type = intval($db->checkStr($Data["type"]));
    $TokenP = $db->checkStr($Data["token_p"]);
    $Token = $db->checkStr($Data["token"]);
    $Name = trim($db->checkStr($Data["name"]));
    if ($S["type"] == 1) {
        switch ($Type) {
            case 1:
                $Row1 = $db->selectRow("SELECT `course_id` FROM `course` WHERE token(`course_id`,'course')=?", array($Token));
                if ($Row1 != NULL) {
                    $CKName = $db->selectRow("SELECT `course_id` FROM `course` WHERE `name`=? AND `course_id`!=?", array($Name, $Row1["course_id"]));
                    if ($CKName != NULL) {
                        throw new Exception("Course name is already in use.");
                    }
                    $db->update("UPDATE `course` SET `name`=?,`update_by`=?,`status`=1 WHERE `course_id`=?", array($Name, $S["user_id"], $Row1["course_id"]));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Course updated successfully.";
                } else {
                    $CKName = $db->selectRow("SELECT `course_id` FROM `course` WHERE `name`=?", array($Name));
                    if ($CKName != NULL) {
                        throw new Exception("Course name is already in use.");
                    }
                    $db->insert("INSERT INTO `course`(`name`, `status`,`add_by`) VALUES (?,1,?)", array($Name, $S["user_id"]));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Course added successfully.";
                }
                break;
            case 2:
                $Row1 = $db->selectRow("SELECT `subject_id` FROM `subject` WHERE token(`subject_id`,'subject')=?", array($Token));
                if ($Row1 != NULL) {
                    $CKName = $db->selectRow("SELECT `subject_id` FROM `subject` WHERE `name`=? AND `subject_id`!=?", array($Name, $Row1["subject_id"]));
                    if ($CKName != NULL) {
                        throw new Exception("Subject name is already in use.");
                    }
                    $db->update("UPDATE `subject` SET `name`=?,`update_by`=?,`status`=1 WHERE `subject_id`=?", array($Name, $S["user_id"], $Row1["subject_id"]));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Subject updated successfully.";
                } else {
                    $Row1 = $db->selectRow("SELECT `course_id` FROM `course` WHERE token(`course_id`,'course')=?", array($TokenP));
                    if ($Row1 == NULL) {
                        throw new Exception("Security Error");
                    }
                    $CKName = $db->selectRow("SELECT `subject_id` FROM `subject` WHERE `name`=?", array($Name));
                    if ($CKName != NULL) {
                        throw new Exception("Subject name is already in use.");
                    }
                    $db->insert("INSERT INTO `subject`(`course_id`,`name`, `status`,`add_by`) VALUES (?,?,1,?)", array($Row1["course_id"], $Name, $S["user_id"]));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Subject added successfully.";
                }
                break;
            case 3:
                $Row1 = $db->selectRow("SELECT `chapter_id` FROM `chapter` WHERE token(`chapter_id`,'chapter')=?", array($Token));
                if ($Row1 != NULL) {
                    $CKName = $db->selectRow("SELECT `chapter_id` FROM `chapter` WHERE `name`=? AND `chapter_id`!=?", array($Name, $Row1["chapter_id"]));
                    if ($CKName != NULL) {
                        throw new Exception("Chapter name is already in use.");
                    }
                    $db->update("UPDATE `chapter` SET `name`=?,`update_by`=?,`status`=1 WHERE `chapter_id`=?", array($Name, $S["user_id"], $Row1["chapter_id"]));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Chapter updated successfully.";
                } else {
                    $Row1 = $db->selectRow("SELECT `subject_id` FROM `subject` WHERE token(`subject_id`,'subject')=?", array($TokenP));
                    if ($Row1 == NULL) {
                        throw new Exception("Security Error");
                    }
                    $CKName = $db->selectRow("SELECT `chapter_id` FROM `chapter` WHERE `name`=?", array($Name));
                    if ($CKName != NULL) {
                        throw new Exception("Chapter name is already in use.");
                    }
                    $db->insert("INSERT INTO `chapter`(`subject_id`,`name`, `status`,`add_by`) VALUES (?,?,1,?)", array($Row1["subject_id"], $Name, $S["user_id"]));
                    $Response["status"] = TRUE;
                    $Response["message"] = "Chapter added successfully.";
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