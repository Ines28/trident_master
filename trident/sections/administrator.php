<?php
	
	//ini_set("display_errors","on");
	
	session_start();
	
	$core->setTitle($config->cf_title_default);
	$core->setMetaDescription("");
	$core->setStyle("styles/administrator.css");
	$core->setStyle("styles/template.css");

	
	
	$user = $core->getLoggedUser();
	$tplSection -> assign("__SITE_URL__", $config->cf_domain);
	$view = empty($_GET["view"])?"slides":$_GET["view"];
	
	define("__SLIDE_WIDTH__", 555);
	define("__SLIDE_HEIGHT__", 393);
	define("__SLIDER_PICTURES_RURL__", "images/slider/");


	//$user_admin = explode("@",$user["user"]);


	$user_admin = $core ->usernameIsEmail($user["user"]);
	
	if($user ){	
		if (!$user_admin) {
			# code...
			
			$tplSection->assign("__USER_NAME__", $user["username"]);	

		
			switch($view){
			
				
				case "slides":
				
								$sqlItems = "SELECT id, title, ordering, published
											 FROM slider
											 ORDER BY ordering ASC";
												 
								$items = $queryObj -> query( $sqlItems, "list" );
								
								$sqlItemsCount = "SELECT COUNT(*) as total
												  FROM slider";
												  
								
								$items_total = $queryObj -> query( $sqlItemsCount,"select");
									
							
								if($items_total["total"] > 0){
									
									$rowclass = 0;
									
									while($row = mysql_fetch_row($items)){
										
										$title = empty($row[1])?"Sin título":$row[1];
										$tplSection -> assign("__ITEM__", $row[0]);
										$tplSection -> assign("__TITLE__", $title);
										$tplSection -> assign("__ORDERING__", $row[2]);
										$tplSection -> assign("__PUBLISHED__", $row[3]);
										
										$tplSection -> parse("main.view.slides.items.item.edit");
										$tplSection -> parse("main.view.slides.items.item.delete");
										
										
										if($row[3] == "0"){
											$tplSection -> parse("main.view.slides.items.item.unpublished");
										}
										else{
											$tplSection -> parse("main.view.slides.items.item.published");
										}
										
										if($rowclass == 0){
											$tplSection -> assign("__ROW_CLASS__", "class=\"row-grey\"");
											$rowclass = 1;
										}
										else{
											$tplSection -> assign("__ROW_CLASS__", "");
											$rowclass = 0;
										}
										
										$tplSection -> parse("main.view.slides.items.item");
									}
									
									
									$tplSection -> parse("main.view.slides.items.save");
									$tplSection -> parse("main.view.slides.items");
									
								}
								else{
									
									$tplSection -> assign("__MSG__", __MSG_NO_SLIDER_ITEMS__);
									//$tplSection -> parse("main.msg");
									
								}
								
								$tplSection -> parse("main.view.slides");
				
								break;
								
								
								
				
				
				
				
				
				case "newSlide":
				
								
								$tplSection -> assign("__ACTION__", "addSlide");
		
								session_start();
								$fieldValues = $_SESSION["fieldValues"];
								if($core -> getMessage()){				
										$message = $core -> getMessage("loginUser");			
										$tplSection  -> assign("__MESSAGE_TEXT__", $message["text"]);
										$tplSection  -> assign("__MESSAGE_TYPE__", $message["type"]);				
										$tplSection  -> parse("main.message");	
								}
								
								$tplSection -> assign("__TITLE__", $fieldValues["title"]);
								$tplSection -> assign("__SUBTITLE__", $fieldValues["subtitle"]);
								$tplSection -> assign("__CONTENT__", $fieldValues["content"]);
								$tplSection -> assign("__URL__", $fieldValues["url"]);
								$tplSection -> assign("__SLIDE_WIDTH__", __SLIDE_WIDTH__);
								$tplSection -> assign("__SLIDE_HEIGHT__", __SLIDE_HEIGHT__);
								
								if(empty($fieldValues["date_from"])){
									$tplSection -> assign("__DATE_FROM__", date("Y-m-d H:i:s"));
								}
								else{
									$tplSection -> assign("__DATE_FROM__", $fieldValues["date_from"]);
								}
								
								if(empty($fieldValues["date_to"])){
									$tplSection -> assign("__DATE_TO__", "nunca");
								}
								else{
									$tplSection -> assign("__DATE_TO__", $fieldValues["date_to"]);
								}
								
								
								if($fieldValues["target"] == '_blank'){
									$tplSection -> assign("__TARGET_SELECTED_BLANK__", "selected=\"selected\"");
								}
								else{
									$tplSection -> assign("__TARGET_SELECTED_SELF__", "selected=\"selected\"");
								}
									
									
								if($fieldValues["published"] == '0'){
									$tplSection -> assign("__PUBLISHED_SELECTED_0__", "checked=\"checked\"");
								}
								else{
									$tplSection -> assign("__PUBLISHED_SELECTED_1__", "checked=\"checked\"");
								}
								
								$tplSection -> assign("__MSG__", "no hay imágens, guarde este slide para poder cargar imágenes.");
								$tplSection -> parse("main.view.newSlide.modMsg");
									
								$_SESSION["fieldValues"] =  NULL;
								
								$tplSection -> parse("main.view.newSlide");
								
								break;	
								
								
								
								
				case "editSlide":
				
								
								session_start();
		
								$item = $_GET["item"];
								
								$sqlItem = "SELECT id, title, subtitle, date_from, date_to, url, target, published
											FROM slider
											WHERE id = $item
											LIMIT 1";
											
								$itemInfo = $queryObj -> query( $sqlItem, "select" );
								
								if(!empty($itemInfo["id"])){
								
								
									if(empty($_SESSION["fieldValues"])){
								
										$fieldValues["title"] = $itemInfo["title"];
										$fieldValues["subtitle"] = $itemInfo["subtitle"];
										$fieldValues["url"] = $itemInfo["url"];
										$fieldValues["date_from"] = $itemInfo["date_from"];
										$fieldValues["date_to"] = $itemInfo["date_to"];
										$fieldValues["target"] = $itemInfo["target"];
										$fieldValues["published"] = $itemInfo["published"];
									
									}
									else{
										$fieldValues = $_SESSION["fieldValues"];
									}
									
									$tplSection -> assign("__ACTION__", "updateSlide");
									$tplSection -> assign("__SLIDE_WIDTH__", __SLIDE_WIDTH__);
									$tplSection -> assign("__SLIDE_HEIGHT__", __SLIDE_HEIGHT__);
								
									
									$tplSection -> assign("__SLIDE__", $item);
									$tplSection -> assign("__TITLE__", $fieldValues["title"]);
									$tplSection -> assign("__SUBTITLE__", $fieldValues["subtitle"]);
									$tplSection -> assign("__URL__", $fieldValues["url"]);
									
									
									if(empty($fieldValues["date_from"])){
										$tplSection -> assign("__DATE_FROM__", date("Y-m-d H:i:s"));
									}
									else{
										$tplSection -> assign("__DATE_FROM__", $fieldValues["date_from"]);
									}
									
									if(empty($fieldValues["date_to"]) or $fieldValues["date_to"] == "0000-00-00 00:00:00"){
										$tplSection -> assign("__DATE_TO__", "nunca");
									}
									else{
										$tplSection -> assign("__DATE_TO__", $fieldValues["date_to"]);
									}
									
							
									
									if($fieldValues["target"] == '_blank'){
										$tplSection -> assign("__TARGET_SELECTED_BLANK__", "selected=\"selected\"");
									}
									else{
										$tplSection -> assign("__TARGET_SELECTED_SELF__", "selected=\"selected\"");
									}
										
										
									if($fieldValues["published"] == '0'){
										$tplSection -> assign("__PUBLISHED_SELECTED_0__", "checked=\"checked\"");
									}
									else{
										$tplSection -> assign("__PUBLISHED_SELECTED_1__", "checked=\"checked\"");
									}
										
									$_SESSION["fieldValues"] =  NULL;
									
									/***********************************************
									* Get Pictures
									************************************************/
									
									$pictures = $core -> getSlidePictures($item);
									
									if($pictures){
										
										foreach($pictures as $picture){
											
											
											$tplSection -> assign("__PICTURE_ID__", $picture["id"]);
											$tplSection -> assign("__PICTURE_URL__", __SLIDER_PICTURES_RURL__);
											$tplSection -> assign("__PICTURE_THUMBNAIL__", $picture["file"]);
											
											if($picture["cover"] == 1){
												$tplSection -> assign("__LEAD_ACTIVE__", "-active");
												$tplSection -> assign("__LEAD_OPTION__", "0");
												$tplSection -> assign("__LEAD_TITLE__", "quitar como imagen principal");
											}
											else{
												$tplSection -> assign("__LEAD_ACTIVE__", "");
												$tplSection -> assign("__LEAD_OPTION__", "1");
												$tplSection -> assign("__LEAD_TITLE__", "seleccionar como imagen principal");
											}
												
								
											if($rowSet == 0){
												$tplSection -> assign("__ROW_CLASS__", "");
												$rowSet = 1;
											}
											else{
												$tplSection -> assign("__ROW_CLASS__", "class=\"rowSet\"");
							
												$rowSet = 0;
											}
												
											$tplSection -> parse("main.view.newSlide.pictures.picture");
											
										}
										
										$tplSection -> parse("main.view.newSlide.pictures");
										
									}
									else{
										$tplSection -> assign("__MSG__", "no hay imágenes");
										$tplSection -> parse("main.view.newSlide.modMsg");
									}
									
									$tplSection -> parse("main.view.newSlide.addPicture");
								
								
									$tplSection -> parse("main.view.newSlide");
								
								}
				
								break;					
				
				
				
			}
		
			$tplSection->parse("main.view");
		}
		else{
	$core->redirect($config->cf_domain."index.php?section=home");
		}
		
		
	}
	else{
		$tplSection->parse("main.login");
	}
	
?>