<?php

if ($db && $S) {
	$Response["status"] = true;
	$Response["message"] = "Fine.";
	if($S["prime"]==1){
		if($S["expire"]==""){
			if($S["days"]>4){
				$Response["status"] = false;
				$Response["code"] = true;
				$Response["message"] = "Account Expired.";
			}
		}
	}else{
		$Response["status"] = false;
		$Response["code"] = true;
		$Response["message"] = "Account Expired.";
	}
} else {
    $Response["status"] = false;
    $Response["message"] = "Something went wrong.Try again later";
}