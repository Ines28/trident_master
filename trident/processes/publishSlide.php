<?php
	
	if(!$core->getLoggedUser()){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	if(empty($_GET["item"]) or !is_numeric($_GET["item"])){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	if($_GET["set"] == "no"){
		$published = 0;
	}
	else{
		$published = 1;
	}
	
	$sqlUpdate = "UPDATE slider 
				  SET published = '$published'
				  WHERE id = $_GET[item]
				  LIMIT 1";
					   
	$update_status = $queryObj -> query($sqlUpdate ,"");
	
		
	$core->redirect($config->cf_domain."index.php?section=administrator&view=slides");
		
	
?>