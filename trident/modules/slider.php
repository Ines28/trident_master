<?php
	
	require_once("modules/functions/slider.php");
	$slider_obj = new modSliderClass();
	
	$speed = "slow";
	$time = "5";
	
	$slides = $slider_obj->getSlides();
	
	if(count($slides) > 0){
		
		$tplModule -> assign("__SLD_SPEED__", $speed);
		$tplModule -> assign("__SLD_TIME__", $time);
		
		$countslides = 0;
		$countslides_unpublished = 0;
		$counter = 0;
		
		foreach($slides as $slide){
			
			$dateto1 = explode(" ", $slide["date_to"]);
			$dateto2 = explode("-", $dateto1[0]);
			
			$date_to = strtotime($dateto2[2]."-".$dateto2[1]."-".$dateto2[0]." ".$dateto1[1]);
			$today_time = strtotime(date("d-m-Y H:i:s",time()));
			
			$countslides = $countslides + 1;
			
			if($slide["date_to"] == "0000-00-00 00:00:00" or ($date_to >= $today_time)){
				
				$counter = $counter + 1;
				
				if(!empty($slide["title"])){
					$tplModule -> assign("__SLD_TITLE__", $slide["title"]);
					$tplModule -> parse("main.slide.title");
				}
				
				if(!empty($slide["subtitle"])){
					$tplModule -> assign("__SLD_SUBTITLE__", $slide["subtitle"]);
					$tplModule -> parse("main.slide.subtitle");
				}
				
				if(!empty($slide["url"])){
					$tplModule -> assign("__SLD_URL__", htmlentities($slide["url"]));
					$tplModule -> assign("__SLD_TARGET__", $slide["target"]);
					$tplModule -> parse("main.slide.url");
				}
				
				
				$picture = $slider_obj->getLeadPicture($slide["id"]);
				
				$tplModule -> assign("__SLD_PICTURE__", $picture["file"]);
				
				$tplModule -> parse("main.slide");
				
				if($countslides > 1){
					$tplModule -> assign("__THUMB_MARGIN__", "topmargin");
				}
				
				$tplModule -> assign("__SLD_COUNT__",$counter);
				$tplModule -> parse("main.thumb");
				
			}
			else{
				$countslides_unpublished =  $countslides_unpublished + 1;
			}
			
			
			
		}
		
		$total_slides = $countslides - $countslides_unpublished;
		$tplModule -> assign("__SLD_ITEMS__", $total_slides);
		
	}
	
?>