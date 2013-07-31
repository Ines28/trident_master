<?php

	//ini_set("display_errors","on");

	$videoID = $_POST["videoID"];

	if(is_numeric($videoID) and $videoID != 0){ 
		
		$tplPlayer = new XTemplate("sections/templates/video.html");
		
		$sqlVideo = "SELECT `file`
					 FROM om_events_videos
					 WHERE id = $videoID
					 AND published = 1
					 LIMIT 1";
				
					 
		$video = $queryObj -> query($sqlVideo, "select");
		
		if(!empty($video["file"])){
		
			$tplPlayer->assign("__YOUTUBE__", $video["file"]);
			
			$tplPlayer->assign("__WIDTH__", 656);
			$tplPlayer->assign("__HEIGHT__", 413);
			
			$tplPlayer -> parse("main");
			echo $tplPlayer -> render("main");
			
		}
	
	}

?>