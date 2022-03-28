<?php

if ($db && $S && $Data) {
    $Image = $db->inputNonR($Data, "image");
    $TokenP = $db->checkStr($Data["token_p"]);
    $Token = $db->checkStr($Data["token"]);
    $Token1 = $db->checkStr($Data["token1"]);
    $q = trim($db->checkStr($Data["q"]));
    $t = trim($db->checkStr($Data["t"]));
    $h = trim($db->checkStr($Data["h"]));
    $a = trim($db->checkStr($Data["a"]));
    $b = trim($db->checkStr($Data["b"]));
    $c = trim($db->checkStr($Data["c"]));
    $d = trim($db->checkStr($Data["d"]));
    $e = trim($db->checkStr($Data["e"]));
    $f = trim($db->checkStr($Data["f"]));
    $ca = intval($db->checkStr($Data["ca"]));

    if ($Image != "") {
        $ImageName = md5(sha1(time() . rand(0, 1000) . rand(0, 1000))) . ".png";
        $Drive = "img/question/";
        $ImagePath = $Drive . $ImageName;
        $File = fopen($ImagePath, 'wp');
        fwrite($File, base64_decode($Image)); //$IsWritten = 
        fclose($File);
    } else {
        $ImageName = NULL;
    }

    $Row1 = $db->selectRow("SELECT `question_id` FROM `question` WHERE token(`question_id`,'question')=?", array($Token));
    if ($Row1 != NULL) {
        $CKName = $db->selectRow("SELECT `question_id` FROM `question` WHERE `body`=? AND `question_id`!=?", array($q, $Row1["question_id"]));
        if ($CKName != NULL) {
            throw new Exception("Question is already available.");
        }
        if ($ImageName == NULL) {
            $db->update("UPDATE `question` SET `body`=?,tag=?,hint=?,`option1`=?,`option2`=?,`option3`=?,`option4`=?,`option5`=?,`option6`=?,`selected`=?,`update_by`=? WHERE `question_id`=?", array($q, $t, $h, $a, $b, $c, $d, $e, $f, $ca, $S["user_id"], $Row1["question_id"]));
        } else {
            $db->update("UPDATE `question` SET `body`=?,tag=?,hint=?,`image`=?,`option1`=?,`option2`=?,`option3`=?,`option4`=?,`option5`=?,`option6`=?,`selected`=?,`update_by`=? WHERE `question_id`=?", array($q, $t, $h, $ImageName, $a, $b, $c, $d, $e, $f, $ca, $S["user_id"], $Row1["question_id"]));
        }
        $Response["status"] = TRUE;
        $Response["title"] = "Updated Successfully.";
        $Response["message"] = "Question updated successfully.";
        $Response["question"]=$db->selectRow("SELECT token(`question`.`question_id`,'question') AS token,`question`.`type_id`, `question`.`body`, `question`.`tag`, IFNULL(`question`.`hint`,'') AS `hint`, IFNULL(CONCAT('web-photo/question/',`question`.`image`),'') AS `image`, `question`.`option1`, IFNULL(`question`.`option2`,'') AS `option2`, IFNULL(`question`.`option3`,'') AS `option3`, IFNULL(`question`.`option4`,'') AS `option4`, IFNULL(`question`.`option5`,'') AS `option5`, IFNULL(`question`.`option6`,'') AS `option6`, `question`.`selected`,CONCAT('Add By : ',`user`.`name`,' on ',DATE_FORMAT(`question`.`add_date`,'%e %b, %Y %h:%i:%s %p')) AS add_date,`question`.`status`,`question`.`add_by`=" . $S["user_id"] . "  AS is_delete FROM `question` INNER JOIN `user` ON `user`.`user_id`=`question`.`add_by` WHERE `question_id`=?",array($Row1["question_id"]));    
    } else {
        $CKType = $db->selectRow("SELECT `type_id` FROM `type` WHERE `type_id`=?", array($Token1));
        if ($CKType == NULL) {
            throw new Exception("Security Error");
        }
        $Row1 = $db->selectRow("SELECT `chapter_id` FROM `chapter` WHERE token(`chapter_id`,'chapter')=?", array($TokenP));
        if ($Row1 == NULL) {
            throw new Exception("Security Error");
        }
        $CKName = $db->selectRow("SELECT `question_id` FROM `question` WHERE `body`=?", array($q));
        if ($CKName != NULL) {
            throw new Exception("Question is already available.");
        }
        $db->insert("INSERT INTO `question`(`type_id`,`chapter_id`, `body`,`tag`,`hint`,`image`, `option1`, `option2`, `option3`, `option4`, `option5`, `option6`, `selected`, `status`, `add_by`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,1,?)", array($CKType["type_id"],$Row1["chapter_id"], $q, $t, $h, $ImageName, $a, $b, $c, $d, $e, $f, $ca, $S["user_id"]));
        $Response["status"] = TRUE;
        $Response["title"] = "Added Successfully.";
        $Response["message"] = "Question added successfully.";
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}