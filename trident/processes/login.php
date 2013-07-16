<?php

	session_start();
	
	$core->logout();
	$type = empty($_GET["type"])?"core":$_GET["type"];

	$username = mysql_real_escape_string(trim($_POST["user"]));
	$password = md5(mysql_real_escape_string(trim($_POST["password"])));
					
	$sqlUser = "SELECT id, status, type
				FROM oadm_users
				WHERE user = '$username'
				AND password = '$password'
				LIMIT 1"; echo $sqlUser;
								
	$user = $queryObj->query($sqlUser, "select"); 
					
	if(empty($user["id"])){
		$core->setMessage("El usuario o password son incorrectos");
	}
	else if($user["status"] == "inactive"){
		$core->setMessage("El usuario está inactivo");
	}
	else{
	
		session_start();
						
		$_SESSION["user"] = array(
									"id" => $user["id"],
									"username" => $username,
									"type" => $user["type"]
								);
								
								
	}
					
	$core->redirect($config->cf_domain."administrator/");
	
	
?>