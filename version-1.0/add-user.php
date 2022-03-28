<?php

if ($db && $S && $Data) {
    $Token = $db->checkStr($Data["token"]);
    $Name = trim($db->checkStr($Data["name"]));
    $Email = trim($db->checkStr($Data["email"]));
    $Number = trim($db->checkStr($Data["number"]));
    $Pass = trim($db->checkStr($Data["pass"]));

    $Row1 = $db->selectRow("SELECT `user_id` FROM `user` WHERE token(`user_id`,'user')=?", array($Token));
    if ($Row1 != NULL) {
        $CKEmail = $db->selectRow("SELECT `user_id` FROM `user` WHERE `email`=?  AND `user_id`!=?", array($Email, $Row1["user_id"]));
        if ($CKEmail != NULL) {
            throw new Exception("Email is already in use.");
        }
        $CKMobile = $db->selectRow("SELECT `user_id` FROM `user` WHERE `mobile`=?  AND `user_id`!=?", array($Number, $Row1["user_id"]));
        if ($CKMobile != NULL) {
            throw new Exception("Mobile Number is already in use.");
        }
        $db->update("UPDATE `user` SET `name`=?,`email`=?,`mobile`=?,`update_by`=? WHERE `user_id`=?", array($Name, $Email, $Number, $S["user_id"], $Row1["user_id"]));
        $Response["status"] = TRUE;
        $Response["message"] = "User updated successfully.";
    } else {
        $CKEmail = $db->selectRow("SELECT `user_id` FROM `user` WHERE `email`=?", array($Email));
        if ($CKEmail != NULL) {
            throw new Exception("Email is already in use.");
        }
        $CKMobile = $db->selectRow("SELECT `user_id` FROM `user` WHERE `mobile`=?", array($Number));
        if ($CKMobile != NULL) {
            throw new Exception("Mobile Number is already in use.");
        }
        $db->insert("INSERT INTO `user`(`name`, `email`, `mobile`, `password`, `type`, `status`,`add_by`) VALUES (?,?,?,sha1(md5(?)),2,1,?)", array($Name, $Email, $Number, $Pass, $S["user_id"]));
        $Response["status"] = TRUE;
        $Response["message"] = "User added successfully.";
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}