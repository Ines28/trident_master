<?php
	
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
	}
	
	if(count($errors) > 0){
		
		session_start();
		$_SESSION["fieldValues"] = $fieldValues;
		$core->redirect($config->cf_domain."index.php?section=administrator&view=editSlide&item=$_POST[slide]");
		
	}
	else{
	
		
		if($_POST["date_to"] == "nunca" or empty($_POST["date_to"])){
			$date_to = "0000-00-00 00:00:00";
		}
		else{
			$date_to = $_POST["date_to"];
		}
		
		
		$sqlUpdate = "UPDATE slider 
					  SET title = '$_POST[title]',
					  	  subtitle = '$_POST[subtitle]',
						  url = '$_POST[url]',
						  target = '$_POST[target]',
						  date_from = '$_POST[date_from]',
						  date_to = '$date_to',
						  published = $_POST[published]
					  WHERE Id = $_POST[slide]
					  LIMIT 1";
					   
		$update_status = $queryObj -> query($sqlUpdate ,"");
		
		$core->redirect($config->cf_domain."index.php?section=administrator&view=slides");
		
	}
	
?>