<?php

if ($db && $Data) {
    $Token = $db->checkStr($Data["token"]);
    if ($S["type"] == 1) {
        $Row1 = $db->selectRow("SELECT `report_id`,`solve` FROM `report` WHERE token(`report_id`,'report')=?", array($Token));
        if ($Row1 != NULL) {
            if ($Row1["solve"] == 2) {
                $db->update("update `report` SET `solve`=1 WHERE `report_id`=?", array($Row1['report_id']));
                $Response["status"] = TRUE;
                $Response["message"] = "Solved successfully.";
            } else {
                $Response["status"] = false;
                $Response["message"] = "Already solved...";
            }
        } else {
            $Response["status"] = false;
            $Response["message"] = "Report not found...";
        }
    } else {
        throw new Exception("Security Error");
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}