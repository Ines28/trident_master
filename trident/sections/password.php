<?php 

	$core->setScript("scripts/search.js");
	$core->setStyle("styles/pass.css");
	
	ini_set('display_errors', 1);	
	$tplSection -> assign("__SITE_URL__", $config->cf_domain);
	session_start();

	$option = empty($_GET["option"])?'editPassword':$_GET["option"];

	if (!empty($userTriden["user"])) {
		if($core -> getMessage()){		
			$message = $core -> getMessage("loginUser");			
			$tplSection -> assign("__MESSAGE_TEXT__", $message["text"]);
			$tplSection -> assign("__MESSAGE_TYPE__", $message["type"]);				
			$tplSection  -> parse("main.message");		
		}
		$tplSection -> assign("__OPTIO__", "editPassword");
		$tplSection -> assign("__STATUS__", 1);		
		$tplSection  -> parse("main.edit");	
	

		$core->setMessage("");
		$error = $core -> getMessage();
		unset($error["text"]);
		unset($error["type"]);
	}
	else{
		$core->redirect($config->cf_domain);
	}

	
?>