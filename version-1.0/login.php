<?php

try {
    if ($db && $Data) {
		//throw new Exception("Disabled by Softozin.");
        $username = trim($db->input($Data, "user"));
        $password = trim($db->input($Data, "pass"));
        $app = trim($db->input($Data, "app"));
        $userType = $db->userType($app);
        $token = trim($db->input($Data, "token"));
        if ($username && $password && $token) {
            $CK = $db->selectRow("SELECT `user_id`,`email`,`name`,`mobile`,IFNULL(CONCAT('profile/',`photo`),'') AS `photo`,`type`,`status` FROM `user` WHERE (md5(`mobile`)=? OR md5(`email`)=?) AND `password`=sha1(?)", array($username, $username, $password));
            if ($CK != NULL) {
                if ($CK["status"] != 1) {
                    throw new Exception("your account is blocked.");
                }
                if ($CK["type"] != $userType) {
                    throw new Exception("This application is not for you.");
                }
				$db->update("UPDATE `api_session` SET `active`=2 WHERE `user_id`=?",array($CK["user_id"]));
                $SK = sha1(time() . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9));
                $db->insert("INSERT INTO `api_session`(`session_id`, `user_id`, `device_id`, `ip`, `user_agent`,`notification_token`, `active`) VALUES (md5(?),?,?,?,?,?,1);", array($SK, $CK["user_id"], $DID, $IP, $UA, $token));
                $Response["sk"] = $SK;
                $Response["name"] = $CK["name"];
                $Response["number"] = $CK["mobile"];
                $Response["email"] = $CK["email"];
                $Response["photo"] = $CK["photo"];
                $Response["status"] = TRUE;
                $Response["message"] = "successfully login.";
            } else {
                $Response["status"] = false;
                $Response["message"] = "username or password may be wrong.";
            }
        } else {
            $Response["status"] = false;
            $Response["message"] = "username or password may be wrong.";
        }
    } else {
        $Response["status"] = false;
        $Response["message"] = "something went wrong.\nplease try again later.";
    }
} catch (Exception $e) {
    $Response["status"] = false;
    $Response["message"] = $e->getMessage();
}