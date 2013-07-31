<?php
	
	//ini_set("display_errors","on");
	
	require_once("classes/events.class.php");
	require_once("classes/search.class.php");
	require_once("labels/date.php");
	
	$core->setTitle($config->cf_title_default);
	$core->setMetaDescription("");
	
	$core->setStyle("styles/search.css");

	$core->setScript("scripts/search.js");

	$searchwords = mysql_real_escape_string($_GET["searchwords"]);
	$currentPage = empty($_GET["page"])?1:$_GET["page"];
	$results_per_page = 10;
	
	$event_obj = new Events();
	$search_obj = new Search();
	
	$tplSection -> assign("__SITE_URL__", $config->cf_domain);
	if (!empty($userTriden["user"])) {
		if(!empty($searchwords)){
		
			$splitwords = explode(" ", $searchwords);
			$total_results =  $search_obj->getTotalResults($searchwords);
			$pager = $core->pagerResults($total_results,$results_per_page,$currentPage,$config->cf_domain."buscar/".$searchwords."/");
			
			/*****************************************
			* Search more than 1 word
			*****************************************/
			if(count($splitwords) > 1){
			
			
				
				$sqlSearch = "SELECT e.alias, e.name, e.id, MATCH (e.name,e.keywords) AGAINST ('$searchwords') as rank 
							  FROM om_events e, om_events_has_sites es
							  WHERE e.published = 1
							  AND e.id = es.events_event_id
							  AND es.sites_site_id = 0
							  AND MATCH (e.name,e.keywords) AGAINST ('$searchwords')
							  ORDER BY rank DESC
							  LIMIT $pager[begin], ".$results_per_page;
				
				
				
			}
			/*****************************************
			* Search 1 word
			*****************************************/
			else{
			
							  
				$sqlSearch = "SELECT e.alias, e.name, e.id
							  FROM om_events e, om_events_has_sites es
							  WHERE e.published = 1
							  AND e.id = es.events_event_id
							  AND es.sites_site_id = 0
							  AND (e.name LIKE '%$searchwords%' OR e.keywords LIKE '%$searchwords%')
							  LIMIT $pager[begin], ".$results_per_page;
							  
			}
			
			if($total_results > 0){
			
				$search_result = $queryObj->query($sqlSearch, "list");
				$search_counter = 1;
				
				while($result = mysql_fetch_row($search_result)){
				
					$text = $event_obj->getEventText($result[2]);
					
					$tplSection -> assign("__SEARCH_COUNTER__", $search_counter + (($currentPage - 1) * $results_per_page));
					$tplSection -> assign("__SEARCH_TITLE__", $result[1]);
					$tplSection -> assign("__SEARCH_URL__", $config->cf_domain."cartelera/".$result[0]."/");
					
					$event_cover = $event_obj->getEventCover($result[2]);
			
					if($event_cover !== false){
						$cover = explode(".", $event_cover["cover"]);
					}
					
					
					if($event_counter%5 == 0){
						$tplSection->assign("__NOMARGIN_RIGHT__", "nomargin-right");
					}
					else{
						$tplSection->assign("__NOMARGIN_RIGHT__", "");
					}
					
					
					if($event_cover !== false){
							
						$tplSection->assign("__EVENT_COVER__", "http://www.ocesa.com.mx/events/images/".$cover[0]."_medium.".$cover[1]);
							
						if($event_cover["position"] != "middle"){
							$tplSection->assign("__COVER_POSITION__", $event_cover["position"]);
						}
						else{
							$tplSection->assign("__COVER_POSITION__", "");
						}
						
							
					}
					else{
						$tplSection->assign("__EVENT_COVER__", "images/cld-event-picture-default.png");
						$tplSection->assign("__COVER_POSITION__", "");
					}
					
					$search_counter = $search_counter + 1;
					$tplSection -> parse("main.results.result");
					
				}
				
				/*************************************************
				* Pager
				*************************************************/
				
				
				if($pager["pages"] > 1){
				
					if($pager["range"]["prev"] !== false){
						$tplSection -> assign("__PAGER_PREV__", $pager["range"]["prev"]);
						$tplSection -> parse("main.results.pager.prev");
					}
				
					foreach($pager["links"] as $key => $value){
						
						$tplSection -> assign("__PAGER_URL__", $value);
						$tplSection -> assign("__PAGER_NUMBER__", $key);
						
						if($key == $currentPage){
							$tplSection -> assign("__PAGER_NUMBER_STYLE__", "lnk_pager_number_active");
						}
						else{
							$tplSection -> assign("__PAGER_NUMBER_STYLE__", "lnk_pager_number");
						}
						
						if($key < $pager["pages"]){
							$tplSection -> parse("main.results.pager.number.separator");
						}
						
						$tplSection -> parse("main.results.pager.number");
						
					}
					
					if($pager["range"]["next"] !== false){
						$tplSection -> assign("__PAGER_NEXT__", $pager["range"]["next"]);
						$tplSection -> parse("main.results.pager.next");
					}
					
					$tplSection -> parse("main.results.pager");
				}
				
				/************************************************/
				
				$tplSection -> parse("main.results");
				
			}
			
			
			$tplSection -> assign("__STATUS__", 1);	
			
			$tplSection -> assign("__RESULTS_TOTAL__", $total_results);
			$tplSection -> assign("__SEARCHWORDS__", utf8_decode($searchwords));
			$tplSection -> parse("main.status");
			
		}
		
	}
	else{
		$core->redirect($config->cf_domain);
	}
?>