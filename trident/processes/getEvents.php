<?php

ini_set("display_errors","on");
require_once("classes/events.class.php");
require_once("labels/date.php");

$month = $_POST["month"];


$tplModule = new XTemplate("sections/templates/events.html");

if (!empty($month)) {
	if(is_numeric($month) and $month != 0){
		
		$year = date("Y");
		$day = date("d");
		$site_url = $_POST["site_url"];
		$event_path = "cartelera/";
	
		$event_obj = new Events();
		
		$tplModule->assign("__EVENT_PATH__", $event_path);
		
		if($month == date("m")){
			$calendar_current = true;
		}
		else{
			$calendar_current = false;
		}
		
		
		// carga eventos del proximo año
		if($month < date("m")){
			$year++;
		}
		
		$sqlEvents = "SELECT c.event, e.name, e.alias
				  	  FROM om_events e, om_events_calendar c, om_events_has_sites es
				  	  WHERE e.id = c.event
					  AND e.id = es.events_event_id
				  	  AND e.published = 1
					  AND es.sites_site_id = 45
				  	  AND EXTRACT(month FROM c.date) = $month
				  	  AND EXTRACT(year FROM c.date) = $year";
				  
				  	  if($calendar_current){
					      $sqlEvents .= " AND EXTRACT(day FROM c.date) >= $day";
				  	  }
		  
		$sqlEvents .=" GROUP BY e.id
				  	  ORDER BY EXTRACT(day FROM c.date) ASC";

		$events = $queryObj -> query($sqlEvents, "list");
		
		$events_list = array();
		
		while($event = mysql_fetch_row($events)){
			$event_date = $event_obj->getEventCurrentDate($event[0],"previous",$month);
			
			$events_list[$event_date["date"]." ".$event[0]] = array(
														"id" => $event[0],
														"name" => $event[1],
														"alias" => $event[2]
														);
		}
		
		ksort($events_list);
		$event_counter = 1;
		
		foreach($events_list as $eventInfo){
			
			$event_date = $event_obj->getEventCurrentDate($eventInfo["id"],"previous",$month);
			
			$date = explode("-", $event_date["date"]);
			$mktime = mktime(0,0,0,$date[1],$date[2],$date[0]);
			$wday = date("D", $mktime);
			$monthLabel = "DATE_MONTH_".date("m", mktime(0,0,0,$date[1],1,date("Y")));
			
			$time = explode(":", $event_date["time"]);
			$place = $event_obj->getPlaceInfo($event_date["place"]);
			
			$tplModule->assign("__EVENT_ID__", $eventInfo["id"]);
			$tplModule->assign("__EVENT_ALIAS__", $eventInfo["alias"]);
			$tplModule->assign("__EVENT_NAME__", substr($eventInfo["name"],0,35));
			$tplModule->assign("__DATE_WDAY__", ucfirst(constant("DATE_DAY_".strtoupper($wday))));
			$tplModule->assign("__DATE_TIME__", $time[0].".".$time[1]);
			$tplModule->assign("__DATE_DAY__", $date[2]);
			$tplModule->assign("__DATE_MONTH__", constant($monthLabel));
			$tplModule->assign("__EVENT_PLACE__", $place["name"]);
			
			$event_cover = $event_obj->getEventCover($eventInfo["id"]);
			
			if($event_cover !== false){
				$cover = explode(".", $event_cover["cover"]);
			}
			
			
			if($event_counter%4 == 0){
				$tplModule->assign("__NOMARGIN_RIGHT__", "nomargin-right");
			}
			else{
				$tplModule->assign("__NOMARGIN_RIGHT__", "");
			}
			
			
			if($event_cover !== false){
					
				$tplModule->assign("__EVENT_COVER__", "http://www.ocesa.com.mx/events/images/".$cover[0]."_medium.".$cover[1]);
					
				if($event_cover["position"] != "middle"){
					$tplModule->assign("__COVER_POSITION__", $event_cover["position"]);
				}
				else{
					$tplModule->assign("__COVER_POSITION__", "");
				}
				
					
			}
			else{
				$tplModule->assign("__EVENT_COVER__", "images/cld-event-picture-default.png");
				$tplModule->assign("__COVER_POSITION__", "");
			}
			
			$dates_event =$event_obj -> getEventDates($eventInfo["id"],"current");
			
		
			$total_dates = count($dates_event);

			$totalcount = $total_dates - 1;

			if ($totalcount > 1) {			 
				$tplModule->assign("__DATES_COUNT__",  $totalcount);
				$tplModule -> parse("main.event.count");
			}

			$event_counter = $event_counter + 1;

			$tplModule -> parse("main.event");
		}
	}	


	
	
	
}

else{	
		$tplModule -> assign("__SITE_URL__", $config->cf_domain);
		$tplModule -> parse("main.theater");
	}


	$tplModule -> parse("main");
	echo $tplModule -> render("main");
?>