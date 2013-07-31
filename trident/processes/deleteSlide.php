<?php

	if(!$core->getLoggedUser()){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	if(empty($_GET["slide"]) or !is_numeric($_GET["slide"])){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	define("__SLIDER_PICTURES_RURL__", "images/slider/");
	
	$sqlSlide= "SELECT Id as id
				FROM slider
				WHERE id = $_GET[slide]
				LIMIT 1";
							  
	$slideInfo = $queryObj -> query($sqlSlide, "select" );
				
	if(!empty($slideInfo["id"])){
					
		$sqlDelete = "DELETE 
					  FROM slider
					  WHERE id = $_GET[slide]
					  LIMIT 1";
								  
		$delete_status = $queryObj -> query($sqlDelete, "" );
		
		/***************************************
		* Delete Pictures
		***************************************/
		
		$sqlPictures = "SELECT id, file
						FROM slider_pictures
						WHERE slide = $_GET[slide]";
						
		$pictures = $queryObj -> query($sqlPictures, "list" );
		
		while($row = mysql_fetch_row($pictures)){
			
			$file_tmp_name = explode(".", $row[1]);
			
			if(file_exists(__SLIDER_PICTURES_RURL__.$row[1])){
				unlink(__SLIDER_PICTURES_RURL__.$row[1]);
			}
									
			if(file_exists(__SLIDER_PICTURES_RURL__.$file_tmp_name[0]."_thumb.".$file_tmp_name[1])){
				unlink(__SLIDER_PICTURES_RURL__.$file_tmp_name[0]."_thumb.".$file_tmp_name[1]);
			}
			
		}
		
		$sqlDelete = "DELETE 
					  FROM slider_pictures
					  WHERE slide = $_GET[slide]";
								  
		$delete_status = $queryObj -> query($sqlDelete, "" );
				
	}
				
					
	$core->redirect($config->cf_domain."index.php?section=administrator&view=slides")
	
	
	
?>