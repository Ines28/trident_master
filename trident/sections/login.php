<?php 
	

	ini_set('display_errors', 1);
	$core->setTitle($config->cf_title_default);	
	$tplSection -> assign("__SITE_URL__", $config->cf_domain);
	session_start();

	$option = empty($_GET["option"])?'login':$_GET["option"];

	if (empty($userTriden["user"])) {
		if($core -> getMessage()){	
	
		$message = $core -> getMessage("loginUser");			
		$tplSection -> assign("__MESSAGE_TEXT__", $message["text"]);
		$tplSection -> assign("__MESSAGE_TYPE__", $message["type"]);				
		$tplSection  -> parse("main.message");		

		}
		
		if ($option  == "login") {
			$tplSection -> assign("__OPTIO__", "login");		
			$tplSection  -> parse("main.login");	
		}

		if ($option  == "recover") {
			$tplSection -> assign("__OPTIO__", "recover");	
			$tplSection  -> parse("main.recover");	
		}
		

		$core->setMessage("");
		$error = $core -> getMessage();
		unset($error["text"]);
		unset($error["type"]);

	}
	else{
		$core->redirect($config->cf_domain."index.php?section=home");
	}
	
?>