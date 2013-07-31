<?php
	
	 $event_videos = $event_obj->getEventVideos($eventID);
	 $event_pictures = $event_obj->getEventPictures($eventID,1,100);
	
	if(count($event_videos) > 0 or count($event_pictures) > 0){
	
		$totalitems = count($event_videos) + count($event_pictures);
		
		$tplSection->assign("__VIDEO_YOUTUBE__", $event_videos[0]["file"]);
		$tplSection->assign("__VIDEO_TOTAL__", $totalitems);
		
		if($totalitems > 4){
			$tplSection -> parse("main.video_player.thumbnails.arrowleft");
			$tplSection -> parse("main.video_player.thumbnails.arrowright");
		}
		
		foreach($event_videos as $video){
			
			$tplSection->assign("__VIDEO_THUMBNAIL__", "http://i.ytimg.com/vi/".$video["file"]."/2.jpg");
			$tplSection->assign("__VIDEO_ID__", $video["id"]);
			$tplSection -> parse("main.video_player.thumbnails.thumbVideo");
			
		}
		
		
		foreach($event_pictures as $picture){
		
			$picture_thumb = explode(".", $picture["file"]);
			
			$tplSection->assign("__PICTURE_ID__", $picture["id"]);
			$tplSection->assign("__PICTURE_THUMBNAIL__", "http://www.ocesa.com.mx/events/images/".$picture_thumb[0]."_small.".$picture_thumb[1]);
			$tplSection->assign("__PICTURE_FILE__", "http://www.ocesa.com.mx/events/images/".$picture["file"]);
			
			$tplSection -> parse("main.video_player.thumbnails.thumbPicture");
			
		}
		
		if(count($event_videos) == 0){
			$tplSection->assign("__PICTURE_LEAD__", "http://www.ocesa.com.mx/events/images/".$event_pictures[0]["file"]);
		}
		else{
			$tplSection->assign("__PICTURE_LEAD__", "");
			$tplSection -> parse("main.video_player.video");
		}
		
		
		$tplSection -> parse("main.video_player.thumbnails");
		$tplSection -> parse("main.video_player");
	}

	
?>