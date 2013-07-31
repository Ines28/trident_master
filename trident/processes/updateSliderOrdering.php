<?php
	
	if(!$core->getLoggedUser()){
		$core->redirect($config->cf_domain."administrator/");
	}
	
	$sqlItems = "SELECT id
				 FROM slider
			     ORDER BY ordering ASC";
					 
	$items = $queryObj -> query( $sqlItems, "list" );
	
	while($row = mysql_fetch_row($items)){
	
		$order = !is_numeric($_POST["item_".$row[0]])?"0":$_POST["item_".$row[0]];
		
		$sqlUpdate = "UPDATE slider 
					  SET ordering = '$order'
					  WHERE id = $row[0]";
					   
		$update_status = $queryObj -> query($sqlUpdate ,"");
		
	}
		
	$core->redirect($config->cf_domain."index.php?section=administrator&view=slides");
		
	
?>