<?php

	define("__SLIDE_WIDTH__", 555);
	define("__SLIDE_HEIGHT__", 393);
	define("__SLIDER_PICTURES_RURL__", "images/slider/");
	
	$errors = array();
	$fieldValues = array();
	
	$fieldValues["slide"] =  $_POST["slide"];
	
	$picture = isset($_FILES['picture'])?$_FILES['picture']:NULL;
	list($pwidth, $pheight, $ptype, $pattr) = getimagesize($picture['tmp_name']);
	
	if($picture['type'] == ""){
		$core->setMessage("Debe seleccionar una imagen");
		$core->redirect($config->cf_domain."index.php?section=administrator&view=editSlide&item=$_POST[slide]");
		
	}
		
	else if( !(eregi("(image/gif)",$picture['type']) or eregi("(image/jpeg)",$picture['type']) or eregi("(image/pjpeg)",$picture['type']) or eregi("(image/png)",$picture['type']) or eregi("(image/x-png)",$picture['type'])) ){
	
		$core->setMessage("La imagen debe estar en formato jpg, png o gif");
		$core->redirect($config->cf_domain."index.php?section=administrator&view=editSlide&item=$_POST[slide]");

	}
		
	else if($pwidth != __SLIDE_WIDTH__ or $pheight != __SLIDE_HEIGHT__){

		$core->setMessage("La imagen debe tener las dimensiones"." ".__SLIDE_WIDTH__." x ".__SLIDE_HEIGHT__. "pixeles");
		$core->redirect($config->cf_domain."index.php?section=administrator&view=editSlide&item=$_POST[slide]");
	}
	

	else{
		
		session_start();
		
		$datetime = date("Y-m-d H:i:s");
		$author = $_SESSION["user"]["user"];
		$file_name = date("dmYHis");

		if ($_FILES['picture']['size']){

			$picture_type = $core -> getPictureType($picture);
		
			$picture_name = $file_name.".".$picture_type;
			
			$picture_file = __SLIDER_PICTURES_RURL__.$picture_name;
			
			move_uploaded_file($picture['tmp_name'], $picture_file);
			echo "ruta: ".$picture_file;					
				
					
		}

		
		
		
		$sqlInsert = "INSERT INTO slider_pictures (id,slide,file)
					   VALUES (0,'$_POST[slide]','$picture_name')";
					   
		$insert_status = $queryObj -> query($sqlInsert ,"");
		
		$core->redirect($config->cf_domain."index.php?section=administrator&view=editSlide&item=$_POST[slide]");
		
	}
	
	
	
?>