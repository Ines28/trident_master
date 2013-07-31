<?php
	
	//ini_set("display_errors","on");
	
	require_once("classes/events.class.php");
	require_once("labels/date.php");
	
	$core->setTitle($config->cf_title_default);
	$core->setMetaDescription("");
	
	$core->setStyle("styles/event.css");
	$core->setStyle("scripts/libraries/fancybox/jquery.fancybox-1.3.4.css");
	$core->setScript("scripts/event.js");
	$core->setScript("scripts/libraries/jplayer/jquery.jplayer.min.js");
	$core->setScript("scripts/libraries/fancybox/jquery.mousewheel-3.0.4.pack.js");
	$core->setScript("scripts/libraries/fancybox/jquery.fancybox-1.3.4.js");

	$core->setScript("scripts/search.js");
	
	
	$event_alias = mysql_real_escape_string($_GET["event"]);
	
	$event_obj = new Events();
	
	$tplSection -> assign("__SITE_URL__", $config->cf_domain);

	$tplSection -> assign("__STATUS__", 1);

	
	
	$sqlEvent = "SELECT id, name, tickets, faq, keywords, hashtag, presale, classification, category
				 FROM om_events
				 WHERE alias = '$event_alias'
				 AND published = 1
				 LIMIT 1";
					 
	$event = $queryObj -> query($sqlEvent, "select");
	
	if(empty($userTriden["user"])){
		$core->redirect($config->cf_domain);	
	}
	else{
		if(!empty($event["id"])){
			
			$eventID = $event["id"];
			
			/***************************************************
			* Basic Information
			****************************************************/
			
			$event_text = $event_obj->getEventText($eventID);
			$event_classification = $event_obj->getClassificationInfo($event["classification"]);
			
			$tplSection->assign("__EVENT_ALIAS__", $event_alias);
			$tplSection->assign("__EVENT_ID__", $eventID);
			$tplSection->assign("__EVENT_NAME__",$event["name"]);
			$tplSection->assign("__EVENT_NAME_R__", utf8_encode($event["name"]));
			$tplSection->assign("__EVENT_DESCRIPTION__", $event_text["description"]);
			$tplSection->assign("__EVENT_CLASSIFICATION__", $event_classification["name"]);
			$tplSection->assign("__EVENT_TICKETMASTER__", $event["tickets"]);

			$tplSection->assign("__EVENT_URL__","http://www.ocesa.com.mx/cartelera/".$event_alias."/");
			

		
			/****************************************************
			* Get Podcast
			*****************************************************/
									
			/*$event_podcast = $event_obj->getAudioEvent($eventID,2);
									
			if(!empty($event_podcast)){
										
				$tplSection->assign("__PODCAST_MP3__", $event_podcast["file"]);
				$tplSection -> parse("main.podcast");
										
			}	*/							
									
			
			/*******************************************************
			* Get Video Player
			********************************************************/
			require_once("processes/player.php");
			
			/***************************************************
			* Socials
			****************************************************/
				
			$tplSection->assign("__NETWORKS_URL_ENCODE__", urlencode($config->cf_domain."cartelera/".$event_alias."/"));
			$tplSection->assign("__NETWORKS_URL__", $config->cf_domain."cartelera/".$event_alias."/");
				
			$event_artists = $event_obj->getEventArtists($eventID);
				
			if(count($event_artists) > 0){
					
				$artistID = $event_artists[0]["id"];
				$artist_socials = $event_obj->getArtistSocials($artistID);
					
				foreach($artist_socials as $social){
					
					if($social["id"] == 4){
						$tplSection->assign("__ARTIST_WEBSITE__", $social["url"]);
						$tplSection -> parse("main.website");
						break;
					}
						
				}
					
			}
			
			/***************************************************
			* Dates
			****************************************************/
				
			$event_dates = $event_obj->getEventDates($eventID);
				
			foreach($event_dates as $date){
			
				$place = $event_obj->getPlaceInfo($date["place"]);
				
				$tplSection->assign("__EVENT_DATE__", $event_obj->formatDate($date["date"]));
				$tplSection->assign("__EVENT_TIME__", $event_obj->formatTime($date["time"]));
				$tplSection->assign("__EVENT_PLACE__", $place["name"]);
				
				$tplSection->parse("main.date");
					
			}
			
			/***************************************************
			* Link
			****************************************************/
			
			//$event_link = $event_obj->getEventLink($eventID);
			
			if(!empty($event["tickets"])){
				
				$tplSection->assign("__EVENT_LINK__", $event["tickets"]);
				$tplSection->parse("main.link");
				
			}
			
			/***************************************************
			* Related
			****************************************************/
				
			$eventInfoArray = array("id" => $eventID,"keywords"=>$event["keywords"], "category"=>$event["category"]);
				
				$event_related_list = $event_obj->getRelatedEvents($eventInfoArray);
				
				if(count($event_related_list) > 0){
					
					foreach($event_related_list as $related){
						
						$related_place = $event_obj->getPlaceInfo($related["place"]);
						$related_cover = $event_obj->getEventCover($related["id"]);
						
						if($related_cover !== false){
							$cover = explode(".", $related_cover["cover"]);
						}
						
						if($related_cover !== false){
					
							$tplSection->assign("__RELATED_COVER__", "http://www.ocesa.com.mx/events/images/".$cover[0]."_small.".$cover[1]);
								
							if($related_cover->position != "middle"){
								$tplSection->assign("__RELATED_COVER_POSITION__", $related_cover["position"]);
							}
							else{
								$tplSection->assign("__RELATED_COVER_POSITION__", "");
							}
							
								
						}
						else{
							$tplSection->assign("__RELATED_COVER__", "images/cld-event-picture-default.png");
							$tplSection->assign("__RELATED_COVER_POSITION__", "");
						}
						
						$tplSection->assign("__RELATED_URL__", $config->cf_domain."cartelera/".$related["alias"]."/");
						$tplSection->assign("__RELATED_NAME__", $related["name"]);
						$tplSection->assign("__RELATED_PLACE__", $related_place["name"]);
						$tplSection->assign("__RELATED_DATE__", $event_obj->formatDate($related["date"]));
						
						$tplSection -> parse("main.events_related.event");
					}
					
					$tplSection -> parse("main.events_related");
					
				}
			
				
			
		}
		else{
		
			$core->redirect($config->cf_domain."index.php");
			
		}
	}
		
	
	
?>