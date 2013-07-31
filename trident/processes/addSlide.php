<?php
	session_start();

	if(!$core->getLoggedUser()){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	$errors = array();
	$fieldValues = array();
	
	$fieldValues["title"] =  $_POST["title"];
	$fieldValues["subtitle"] =  $_POST["subtitle"];
	$fieldValues["date_from"] =  $_POST["date_from"];
	$fieldValues["date_to"] =  $_POST["date_to"];
	$fieldValues["classification"] =  $_POST["classification"];
	$fieldValues["url"] =  $_POST["url"];
	$fieldValues["target"] =  $_POST["target"];
	$fieldValues["slide"] =  $_POST["slide"];
	$fieldValues["published"] =  $_POST["published"];
	

	if(empty($_POST["title"])){
		$errors[] = "El título está vacío";
		echo "entro";
	}
	
	if(count($errors) > 0){
		
		session_start();
		$_SESSION["fieldValues"] = $fieldValues;
		$core->redirect($config->cf_domain."index.php?section=administrator&view=newSlide");
		
	}
	else{
		
		session_start();
		
		$datetime = date("Y-m-d H:i:s");
		$user = $core->getLoggedUser();
		$author = $user["id"];
		
		if($_POST["date_to"] == "nunca" or empty($_POST["date_to"])){
			$date_to = "0000-00-00 00:00:00";
		}
		else{
			$date_to = $_POST["date_to"];
		}
		
		$sqlInsert = "INSERT INTO slider (id,title,subtitle,url,target,ordering,date_from,date_to,author,published,created)
					   VALUES (0,'$_POST[title]','$_POST[subtitle]','$_POST[url]','$_POST[target]',0,'$_POST[date_from]','$date_to',$author,'$_POST[published]','$datetime')";
					   
		$insert_status = $queryObj -> query($sqlInsert ,"");
		
		$core->redirect($config->cf_domain."index.php?section=administrator&view=slides");
		
	}
	
?>