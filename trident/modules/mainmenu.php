<?php

	$counter_items = 1;
	$menu_items = $this->getMenuItems(1);
	$config = new Configuration();
	$core = new Core(&$tplIndex);
	$userTriden = $core->getLoggedUser();

	if ($userTriden) {
		$tplModule -> assign("__DOMIN_URL__", $config->cf_domain);
		$tplModule -> parse("main.item.login");
	}
	if (!empty($userTriden["user"])) {
		
		
	}

	foreach($menu_items as $item){
	
		if($_GET["section"] == $item["alias"]){
			$tplModule -> assign("__ITEM_SELECTED__", "selected");
		}
		else if(empty($_GET["section"]) and $item["alias"] == "home"){
			$tplModule -> assign("__ITEM_SELECTED__", "selected");
		}
		else{
			$tplModule -> assign("__ITEM_SELECTED__", "");
		}
		
		if(count($menu_items) == $counter_items){
			$tplModule -> assign("__ITEM_SEPARATOR__", "noseparator-right");
		}
		else{
			$tplModule -> assign("__ITEM_SEPARATOR__", "");
		}
		
		$tplModule -> assign("__ITEM_NAME__",$item["name"]);
		$tplModule -> assign("__ITEM_URL__", $config->cf_domain.str_replace("[INDEX]",$config->cf_index_default,$item["url"]));
		$tplModule -> assign("__ITEM_TARGET__", $item["target"]);		
	
	
		/*if ($item["id"] == 2 ) {			
			if (!empty($userTriden["user"])) {
				$tplModule -> assign("__ITEM_NAME__",'');
				$tplModule -> assign("__ITEM_URL__", '');
				$tplModule -> assign("__ITEM_TARGET__",'');	
				$tplModule -> assign("__ITEM_SEPARATOR__", "");
			}
		}*/

		$tplModule -> parse("main.item");
		$counter_items++;
		
	}
	
?>