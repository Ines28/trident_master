// Event Script


function evslideLeft(){
	
	vsldSize = 141;
	vsldLimit = 2;
	rollname = "video-full-thumbs-roll";
	
	if(vsldLimit <= evideosCurr){
		$("#"+rollname).animate({"left": "+="+vsldSize+"px"}, "slow");
		evideosCurr = evideosCurr - 1;
	}
	
}



function evslideRight(){
	
	vsldSize = 141; 
	vsldLimit = evideosTotal - 4;
	rollname = "video-full-thumbs-roll";
	
	if(vsldLimit >= evideosCurr ){
		$("#"+rollname).animate({"left": "-="+vsldSize+"px"}, "slow");
		evideosCurr = evideosCurr + 1;
	}
	
	
}



function loadVideo(videoID){
	
	//$('html,body').animate({scrollTop: 350}, 1000);	
	
	$('#player').replaceWith("<div id=\"player\"><img src=\""+url_domain+"images/spinloader-1.gif\" alt=\"cargando\" id=\"cld-menu-spiner\" /></div>");
	
	$.post(url_domain+"index.php?action=getVideo", { videoID: videoID}, 
				function(data){ 
					$('#player').replaceWith(data);
				});
	
}