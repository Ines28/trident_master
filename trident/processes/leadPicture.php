<?php
	
	if(!$user){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	if(is_numeric($_GET["picture"]) and !empty($_GET["picture"])){
		
		$sqlUpdate = "UPDATE slider_pictures SET cover = 0 WHERE slide = $_GET[slide]";
		$update_status = $queryObj -> query($sqlUpdate ,"");
		
		if($_GET["active"] == 1){
			
			$sqlUpdate = "UPDATE slider_pictures SET cover = 1 WHERE id = $_GET[picture] LIMIT 1";
			$update_status = $queryObj -> query($sqlUpdate ,"");
			
		}
		
	}
		
		
	$core->redirect($config->cf_domain."index.php?section=administrator&view=editSlide&item=$_GET[slide]");
		
	
?>