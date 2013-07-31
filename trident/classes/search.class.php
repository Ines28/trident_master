<?php
	
	class Search extends QueryClass{
	
		
		
		function getTotalResults($searchwords){
		
			$this -> serverConnection();
			
			$splitwords = explode(" ", $searchwords);
			
			if(count($splitwords) > 1){
				
				$sqlSearch = "SELECT e.id, MATCH (e.name,e.keywords) AGAINST ('$searchwords') as rank 
							  FROM om_events e, om_events_has_sites es
							  WHERE e.published = 1
							  AND e.id = es.events_event_id
							  AND es.sites_site_id = 0
							  AND MATCH (e.name,e.keywords) AGAINST ('$searchwords')";
				
			}
			else{
				
				$sqlSearch = "SELECT e.id
							  FROM om_events e, om_events_has_sites es
							  WHERE e.published = 1
							  AND e.id = es.events_event_id
							  AND es.sites_site_id = 0
							  AND (e.name LIKE '%$searchwords%' OR e.keywords LIKE '%$searchwords%')";
				
			}
			
			$search = $this->query($sqlSearch, "list");
			$count_items = 0;
			
			while($items = mysql_fetch_row($search)){
				$count_items++;
			}
			
			return $count_items;
		
		
		}
		
		
		
		
		
		
		
		
	}
	
?>