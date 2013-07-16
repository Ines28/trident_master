<?php 
	
	$config = new Configuration();
	$core = new Core(&$tplIndex);
	

	$tplModule -> assign("__SITE_URL__", $config->cf_domain);

	global $userTriden;

	if (empty($_GET["option"])) {
		$tplModule -> assign("__OPTIO__", "login");		
		$tplModule  -> parse("main.user");	
	}


	if ($_GET["option"] == "editPassword") {
		$tplModule -> assign("__OPTIO__", "editPassword");
		
		$tplModule  -> parse("main.edit");	
	}


?>