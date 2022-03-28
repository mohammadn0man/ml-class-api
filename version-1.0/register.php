<?php

if ($db && $Data) {
    $Name = trim($db->checkStr($Data["name"]));
    $Email = trim($db->checkStr($Data["email"]));
    $Number = trim($db->checkStr($Data["number"]));
    $Pass = trim($db->checkStr($Data["pass"]));

    if ($Name && $Email && $Number && $Pass) {
        $CKEmail = $db->selectRow("SELECT `user_id` FROM `user` WHERE `email`=?", array($Email));
        if ($CKEmail != NULL) {
            throw new Exception("Email is already in use.");
        }
        $CKMobile = $db->selectRow("SELECT `user_id` FROM `user` WHERE `mobile`=?", array($Number));
        if ($CKMobile != NULL) {
            throw new Exception("Mobile Number is already in use.");
        }
        $db->insert("INSERT INTO `user`(`name`, `email`, `mobile`, `password`, `type`, `status`,`add_by`) VALUES (?,?,?,sha1(?),2,1,?)", array($Name, $Email, $Number, $Pass, 1));
        $Response["status"] = TRUE;
        $Response["message"] = "Successfully registered.";
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}