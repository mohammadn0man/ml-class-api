<?php
// $version = '1.0';
error_reporting(0);
try {
    include_once './connection.php';
    $db = new Connection("OK");
    $IP = getenv('HTTP_CLIENT_IP') ?: getenv('HTTP_X_FORWARDED_FOR') ?: getenv('HTTP_X_FORWARDED') ?: getenv('HTTP_FORWARDED_FOR') ?: getenv('HTTP_FORWARDED') ?: getenv('REMOTE_ADDR');
    if (filter_input(INPUT_GET, "url__id")) {
        try {
            $URL1 = filter_input(INPUT_GET, "url__id");
            $GDT = explode("/", $URL1);
            $MTD = filter_input(INPUT_SERVER, "REQUEST_METHOD");
            $version = array_shift($GDT);
            $URL = array_shift($GDT);
            $Data = ($MTD == "POST") ? json_decode(file_get_contents("php://input"), true) : $GDT;
            $DID = filter_input(INPUT_SERVER, "HTTP_DID");
            $SK = filter_input(INPUT_SERVER, "HTTP_SK");
            $UA = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
            $Response = array();
            $S = $db->selectRow("SELECT `api_session`.`session_id`,token(`user`.`user_id`,'user') AS token,`user`.`user_id`,`user`.`name`,`user`.`mobile`,`user`.`email`,IFNULL(`user`.`photo`,'') AS `photo`,`user`.`type`,IFNULL(`user`.`expire`,'') AS `expire`,`code`,NOW()<=IFNULL(`user`.`expire`,NOW()) AS `prime`,DATEDIFF(NOW(),`user`.`add_date`) AS `days` FROM `api_session` INNER JOIN `user` ON `user`.`user_id`=`api_session`.`user_id` AND `api_session`.`session_id`=md5(?) AND `api_session`.`device_id`=? AND `api_session`.`active`=1", array($SK, $DID));
            if ($S != NULL || $URL == "register" || $URL == "login" || $URL == "logout") {
                try {
                    $file = './version-' . $version . ".0/"  . $URL . '.php';
                    if (file_exists($file)) {
//                        $KID = "rzp_live_wiSCQtjT4P2FLN";
//                        $ttt = $KID . ":kDLYxk3WKUQ0ov9RDoFyITvT";
                        $FBIKEY = "AAAAkgrswkw:APA91bFczm-f1FHTmBDC5Vm7gYwnn5YxUWTucI3s62dWZGiDCsh_EE0G55VN1UgCqq98E8nUT8SqVGozxihu1MVffH8YM3W5GkjXBVI1vbUwBqMCc8uYX262lflTIvasp4JLocbPWVRz";
                        $FBIID = "627248513612";
                        include $file;
//                        unset($KID);
//                        unset($ttt);
                        unset($FBIKEY);
                        unset($FBIID);
                    } else {
                        $Response["status"] = false;
                        $Response["message"] = "API not exists.";
                    }
                } catch (PDOException $e) {
                    $err = $db->error();
                    $Response["status"] = false;
                    $Response["message"] = $err == NULL ? $e->getMessage() : $err;
                } catch (Exception $e) {
                    $Response["status"] = false;
                    $Response["message"] = $e->getMessage();
                }
            } else {
                $Response["status"] = FALSE;
                $Response["message"] = "session expired";
            }
        } catch (PDOException $e) {
            $err = $db->error();
            $Response["status"] = false;
            $Response["message"] = $err == NULL ? $e->getMessage() : $err;
        } catch (Exception $e) {
            $Response["status"] = false;
            $Response["message"] = $e->getMessage();
        }
    } else {
        $Response["status"] = FALSE;
        $Response["message"] = "welcome to ML Class API";
    }
} catch (PDOException $e) {
    $err = $db->error();
    $Response["status"] = false;
    $Response["message"] = $err == NULL ? $e->getMessage() : $err;
} catch (Exception $e) {
    $Response["status"] = FALSE;
    $Response["message"] = $e->getMessage();
}

$content = json_encode($Response);

$length = mb_strlen($content, 'UTF-8');

header('Content-Length: '.$length);

echo $content;