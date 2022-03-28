<?php

if ($db && $Data) {
    $Folder = $db->checkStr($Data[0]);
    $Image = $db->checkStr($Data[1]);
    header('Content-Type: image/png');
    $file = "img/" . $Folder . "/" . $Image;
    if (file_exists($file)) {
        $OPT = FALSE;
        $size = filesize($file);
        header("Content-Length: " . $size);
        readfile($file);
        die();
    } else {
        $Response["status"] = false;
        $Response["message"] = "Not Found";
    }
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}