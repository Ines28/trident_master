<?php
	
	if(!$core->getLoggedUser()){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	define("__SLIDE_WIDTH__", 950);
	define("__SLIDE_HEIGHT__", 220);
	define("__SLIDER_PICTURES_RURL__", "images/slider/");
	
	if(empty($_GET["picture"]) or !is_numeric($_GET["picture"])){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	$sqlSlide = "SELECT slide, file, id
				 FROM slider_pictures
				 WHERE id = $_GET[picture]
				 LIMIT 1";
				 
	$slide = $queryObj -> query( $sqlSlide, "select" );
	
	$file_tmp_name = explode(".", $slide["file"]);
			
	if(file_exists(__SLIDER_PICTURES_RURL__.$slide["file"])){
		unlink(__SLIDER_PICTURES_RURL__.$slide["file"]);
	}
							
	
	$sqlDelete = "DELETE 
				  FROM slider_pictures
				  WHERE id = $slide[id]
				  LIMIT 1";
								  
	$delete_status = $queryObj -> query($sqlDelete, "" );
	
	$core->redirect($config->cf_domain."index.php?section=administrator&view=editSlide&item=$slide[slide]");
	
?>