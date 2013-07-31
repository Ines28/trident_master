<?php
	
	class Events extends QueryClass{
	
		
		function getMonths($month_limit = 12){
		
			$this -> serverConnection();
			$year = date("Y");
			$month = date("m");
			
			
			/* original
			$sqlMonths = "SELECT DISTINCT EXTRACT(month FROM c.date) as month
						  FROM om_events_calendar c, om_events e, om_events_has_sites es
						  WHERE EXTRACT(year FROM c.date) = '$year'
						  AND EXTRACT(month FROM c.date) >= '$month'
						  AND c.event = e.id
						  AND e.id = es.events_event_id
						  AND e.published = 1
						  AND es.sites_site_id = 0
						  ORDER BY EXTRACT(year FROM c.date) ASC, EXTRACT(month FROM c.date) ASC
						  LIMIT $month_limit";
			*/
			
			
			// permite traer eventos de los años proximos
			$sqlMonths = "SELECT DISTINCT EXTRACT(month FROM c.date) as month
						  FROM om_events_calendar c, om_events e, om_events_has_sites es 
						  WHERE DATE_FORMAT(c.date, '%Y-%m') >= '{$year}-{$month}'
						  AND c.event = e.id 
						  AND e.id = es.events_event_id 
						  AND e.published = 1 
					  	  AND es.sites_site_id = 45
						  ORDER BY EXTRACT(year FROM c.date) ASC, 
						  EXTRACT(month FROM c.date) ASC 
						  LIMIT {$month_limit};";
			
			
			
			$months = $this->query($sqlMonths, "list");
			$amonths = array();
			
			while($monthx = mysql_fetch_row($months)){
				
				$amonths[] = $monthx[0];
				
			}
			
			return $amonths;
			
		}
		
		
		
		
		
		
		
		function getEventDates($eventID,$opt="all"){
	
			$today = date("Y-m-d");
			$this -> serverConnection();
				
			$sqlDates = "SELECT id,date,time,place,open
						 FROM om_events_calendar
						 WHERE event = $eventID";
						 
						 if($opt == "current"){
							$sqlDates .= " AND date > '$today' ";
						 }
						 
			$sqlDates.= " ORDER BY date ASC";
						 
			$dates = $this -> query($sqlDates, "list");
						 
			while($date = mysql_fetch_row($dates)){
			
				$list[] = array(
								"id" => $date[0],
								"date" => $date[1],
								"time" => $date[2],
								"place" => $date[3],
								"open" => $date[4]
							  );
			}
			
			return $list;
		
		}
		
		
		
		
		
		
		
		

		function getEventCurrentDate($eventID,$event_status,$inMonth=NULL){
	
			$dates = $this->getEventDates($eventID);
			
			foreach($dates as $date){
			
				
				$date_parts = explode("-", $date["date"]);
				
				if($inMonth != NULL){
				
					$date_status = $this->getDateStatus($date["date"],$date["time"],false);
				
					if($event_status == "previous" and $date_status == "previous" and $date_parts[1] == $inMonth){
						$current_date = $date;
						
						break;
					}
					else if($event_status == "past" and $date_status == "past" and $date_parts[1] == $inMonth){
						$current_date = $date;
						
						break;
					}
					else if($event_status == "live" and $date_status == "live" and $date_parts[1] == $inMonth ){
						$current_date = $date;
						
						break;
					}
					
					else if($event_status == "previous" and $date_status == "live" and $date_parts[1] == $inMonth ){
						$current_date = $date;
						
						break;
					}
					
				}
				else{
					
					$date_status = $this->getDateStatus($date["date"],$date["time"],true);
					
					if($event_status == "previous" and $date_status == "previous"){
						$current_date = $date;
						break;
					}
					else if($event_status == "past" and $date_status == "past"){
						$current_date = $date;
						break;
					}
					else if($event_status == "live" and $date_status == "live"){
						$current_date = $date;
						break;
					}
					
					
					
				}	
				
				
			}
			
			return $current_date;
		
		
		}
		
		
		
		
		
		
		
		
		function getDateStatus($date,$time,$checkIsLive=false,$limit=240){
		
			if($date > date("Y-m-d")){
				return "previous";
		
			}
			else if($date < date("Y-m-d")){
				return "past";
			}
			else if($date == date("Y-m-d")){
				
				$date_tmp = explode("-",$date);
				$time_tmp = explode(":",$time);
				
				$time_limit = date("H:i:s", mktime($time_tmp[0],$time_tmp[1] + $limit,$time_tmp[2],$date_tmp[1],$date_tmp[2],$date_tmp[0]));
				
				if($time <= date("H:i:s") and date("H:i:s") <= $time_limit){
					
					return $checkIsLive?"live":"previous";
				}
				else if($time <= date("H:i:s") and date("H:i:s") > $time_limit){
					
					return $checkIsLive?"past":"previous";
				}
				else{
					
					return "previous";
				}
			}
			
		}
		
		
		
		
		
		
		
		function getEventCover($eventID){
			
			$this -> serverConnection();
			
			$sqlCover = "SELECT file, position
						 FROM om_events_pictures p, om_events_pictures_has_sites ps
						 WHERE p.event = $eventID
						 AND p.id = ps.events_pictures_event_picture_id
						 AND ps.cover = 1
						 AND (ps.sites_site_id = 45 OR ps.sites_site_id = 0)
						 AND p.published = 1
						 LIMIT 1";
								
			$cover = $this -> query($sqlCover, "select");
			
			if(!empty($cover["file"])){
				return array("cover" => $cover["file"], "position" => $cover["position"]);
			}
			else{
				return false;
			}
			
		}
		
		
		
		
		
		
		
		function getPlaceInfo($placeID){
		
			$this -> serverConnection();
		
			$sqlPlace = "SELECT *
						 FROM om_events_places
						 WHERE id = $placeID
						 LIMIT 1";
				 
			$place = $this -> query($sqlPlace, "select");
			
			return $place;
		
		}
		
		
		
		
		
		
		
		
		function getEventText($eventID){
	
			$this -> serverConnection();
		
			$query = "SELECT t.intro, t.description
					  FROM om_events_texts t, om_events_texts_has_sites s 
					  WHERE t.events_text_id = s.events_texts_event_text_id
					  AND t.events_event_id = $eventID
					  AND s.sites_site_id = 0
					  ORDER BY t.created_date DESC
					  LIMIT 1";

			$texts = $this -> query($query, "select");
			return $texts;
		
		}
		
		
		
		
		
		
		
		function getEventVideos($eventID){
		
			$this -> serverConnection();
			
			$sqlVideos = "SELECT v.id, v.name, v.file
						  FROM om_events_videos v, om_events_videos_has_sites vs
						  WHERE v.id = vs.events_videos_event_video_id
						  AND v.event = $eventID
						  AND v.type = 1
						  AND vs.sites_site_id = 0
						  ORDER BY id DESC";
						  
			$videos = $this -> query($sqlVideos, "list");
			$avideos = array();
						 
			while($video = mysql_fetch_row($videos)){
			
				$avideos[] = array(
								"id" => $video[0],
								"name" => $video[1],
								"file" => $video[2]
							  );
			}
			
			return $avideos;
			
		}
	
	
	
	
	
	
		function getEventPictures($eventID,$type,$limit=100){
		
			$this -> serverConnection();
			
			$sqlPictures = "SELECT p.file, p.position
							FROM om_events_pictures p, om_events_pictures_has_sites ps
							WHERE p.event = $eventID
							AND p.id = ps.events_pictures_event_picture_id
							AND ps.sites_site_id = 0 
							AND p.type = $type
							AND p.published = 1
							ORDER BY p.id DESC
							LIMIT $limit";
						  
			$pictures = $this -> query($sqlPictures, "list");
			$apictures = array();
						 
			while($picture = mysql_fetch_row($pictures)){
			
				$apictures[] = array(
								"file" => $picture[0],
								"position" => $picture[1]
							  );
			}
			
			return $apictures;
		
		}
		
		
		
		
		
		
		
		function getEventArtists($eventID){
		
			$this -> serverConnection();
			
			$sqlArtists = "SELECT a.id, a.name, a.biography
						   FROM om_artists a, om_events_has_artists ea
						   WHERE a.id = ea.artists_artist_id
						   AND ea.events_event_id = $eventID";
						   
			$artists = $this -> query($sqlArtists, "list");
			$aartists = array();
						 
			while($artist = mysql_fetch_row($artists)){
			
				$aartists[] = array(
								"id" => $artist[0],
								"name" => $artist[1],
								"biography" => $artist[2]
							  );
			}
			
			return $aartists;
			
		}
		
		
		
		
		
		
		
		function getArtistSocials($artistID){
		
			$this -> serverConnection();
			
			$sqlSocials =  "SELECT socials_social_id, url
							FROM om_artists_has_socials
							WHERE artists_artist_id = $artistID";
							
			$socials = $this -> query($sqlSocials, "list");
			$asocials = array();
						 
			while($social = mysql_fetch_row($socials)){
			
				$asocials[] = array(
								"id" => $social[0],
								"url" => $social[1]
							  );
			}
			
			return $asocials;
			
		} 
		
		
		
		
		
		
		
		function getMonthLabel($date){
		
			$date_tmp = explode("-", $date);
			$monthLabel = "DATE_MONTH_".strtoupper(date("M", mktime(0,0,0,$date_tmp[1],$date_tmp[2],$date_tmp[0])));
			echo "DATE_MONTH_".strtoupper(date("m", mktime(0,0,0,$date_tmp[1],$date_tmp[2],$date_tmp[0])));
			
			return constant($monthLabel);
			
		}
		
		
		
		
		
		
		
		function formatDate($date){
		
			$date_tmp = explode("-", $date);
			
			$dayLabel = "DATE_DAY_".strtoupper(date("D", mktime(0,0,0,$date_tmp[1],$date_tmp[2],$date_tmp[0])));
			$monthLabel = "DATE_MONTH_".strtoupper(date("m", mktime(0,0,0,$date_tmp[1],$date_tmp[2],$date_tmp[0])));
			
			$date_string = ucfirst(date("d", mktime(0,0,0,$date_tmp[1],$date_tmp[2],$date_tmp[0]))." de ".constant($monthLabel));
			
			
			return $date_string;
			
		}
		
		
		
		
		
		
		function formatTime($time){
		
			$time_tmp = explode(":", $time);
			return $time_tmp[0].".".$time_tmp[1]." hrs";
			
		}
		
		
		
		
		
		
		function getRelatedEvents($eventInfo){
		
			$this -> serverConnection();
	
			$today = date("Y-m-d");
			
			$sqlRelated = "SELECT e.id, e.name, c.place, c.date, e.alias
						   FROM om_events e, om_events_calendar c, om_events_has_sites s
						   WHERE e.published = 1
						   AND e.id <> ".$eventInfo["id"]."
						   AND e.id = c.event
						   AND c.date >= '".$today."'
						   AND s.events_event_id = e.id
						   AND s.sites_site_id = 45
						   AND category = ".$eventInfo["category"]."
						   GROUP BY e.id
						   ORDER BY RAND()
						   LIMIT 5";
							
			$related = $this -> query($sqlRelated, "list");
			$aevents = array();
						 
			while($event = mysql_fetch_row($related)){
			
				$aevents[] = array(
								"id" => $event[0],
								"name" => $event[1],
								"place" => $event[2],
								"date" => $event[3],
								"alias" => $event[4]
							  );
			}
			
			return $aevents;
			
		}
		
		
		
		
		
		
		
		function getClassificationInfo($classID){
		
			$this -> serverConnection();
		
			$sqlClass = "SELECT name, alias
						 FROM om_events_classifications
						 WHERE id = $classID
						 LIMIT 1";
				 
			$class = $this -> query($sqlClass, "select");
			
			return $class;
			
		}
		
		
	
		
		
		
		
		
		function getAudioEvent($eventID,$type){
		
			$this -> serverConnection();
		
			$sqlAudio = "SELECT a.*
						 FROM om_events_audios a, om_events_audios_has_sites aus
						 WHERE a.event = $eventID
						 AND a.id = aus.events_audios_event_audio_id
						 AND aus.sites_site_id = 0 
						 AND a.type = $type
						 LIMIT 1";
				 
			$audio = $this -> query($sqlAudio, "select");
			
			return $audio;
			
		}
		
		
		
		
		
		
		
		function getEventLink($eventID){
			
			$this -> serverConnection();
		
			$sqlCustomer = "SELECT url
						 	FROM om_events_links
						 	WHERE event = $eventID
						 	AND customer = 1
						 	LIMIT 1";
				 
			$customer = $this -> query($sqlCustomer, "select");
			
			return $customer["url"];
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
	
?>