<?php

if ($db && $S && $Data) {
    $Code = $db->checkStr($Data["code"]);
    $Row1 = $db->selectRow("SELECT `user_id` FROM `user` WHERE `code`=? AND `user_id`=?", array($Code,$S["user_id"]));
    if ($Row1 != NULL) {
        $db->update("UPDATE `user` SET `expire`=DATE(DATE_ADD(NOW(), INTERVAL 180 DAY)) WHERE `user_id`=?", array($S["user_id"]));
        $Response["status"] = true;
        $Response["message"] = "Successfully Applied.";
    } else {
        $Response["status"] = false;
        $Response["message"] = "Code is wrong.";
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}