<?php
	
	require_once("classes/events.class.php");
	require_once("labels/date.php");
	
	$core->setTitle($config->cf_title_default);
	$core->setMetaDescription("");
	
	$core->setStyle("styles/home.css");
	$core->setStyle("styles/slider.css");
	$core->setScript("scripts/slider.js");
	$core->setScript("scripts/home.js");
	$core->setScript("scripts/search.js");
	
	$event_obj = new Events();
	$months = $event_obj->getMonths();
	
	$tplSection -> assign("__SITE_URL__", $config->cf_domain);
	$tplSection -> assign("__MENU_TOTAL_MONTHS__", count($months));
	$tplSection -> assign("__CALENDAR_CONTENT_ID__", "modcal_events");
	$tplSection -> assign("__SLIDER__", $core->getModule("slider"));
	
	if(empty($userTriden["user"])){
		$core->redirect($config->cf_domain);	
	}
	else{
	
		if(count($months) > 0){
			$tplSection -> assign("__STATUS__", 1);		
			$tplSection -> assign("__MENU_MONTH_DEFAULT__", $months[0]);
			$count_months = 1;
			
			foreach($months as $month){
				

				$monthLabel = "DATE_MONTH_".date("m", mktime(0,0,0,$month,1,date("Y")));
				
				
				$tplSection -> assign("__MONTH_LABEL__", constant($monthLabel));
				$tplSection -> assign("__MONTH_COUNTER__", $count_months);
				$tplSection -> assign("__MONTH_NUMBER__", $month);
				
				if($month_counter == 1){
					$tplSection -> assign("__MONTH_SELECTED__", "selected");
					$tplSection -> assign("__MONTH_LINE__", "show");
				}
				else{
					$tplSection -> assign("__MONTH_SELECTED__", "");
					$tplSection -> assign("__MONTH_LINE__", "");
				}
				
				$tplSection -> parse("main.calendar.month");
				
				$count_months = $count_months + 1;
				
			}
			
			$tplSection -> parse("main.calendar");
			
		}
	}
		
	
	
?>