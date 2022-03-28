<?php

if ($db && $DID && $Data) {
    $userType = $db->userType($Data[0]);
    $db->update("UPDATE `api_session`,`user` SET `api_session`.`active`=2 WHERE `api_session`.`device_id`=? AND `user`.`user_id`=`api_session`.`user_id` AND `user`.`type`=?", array($DID, $userType));
    $Response["status"] = TRUE;
    $Response["message"] = "Successfully Logout";
} else {
    $Response["status"] = FALSE;
    $Response["message"] = "Something went wrong.Try again later";
}