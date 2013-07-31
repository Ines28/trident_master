// Slider Script

$(document).ready(function(){
	
	moveSlide();
	
	$("#sld-thumbnails > a").click(function () {
		  
		  slide_num = $(this).attr("data-rel");
		  sldLeadCurr = parseInt(slide_num);
		  moveSlide();
													
	});
	
	
});


function moveSlide(){
	
	clearInterval(sldInterval);
	
	slide_size = $(".sld-slide").width();											 
	slide_pos = slide_size * (sldLeadCurr - 1);
		  
	if(sldLeadCurr == 1){
		 $("#sld-roll").animate({"left": "0"}, sldSpeed);
	}
	else{
		$("#sld-roll").animate({"left": "-"+slide_pos+"px"}, sldSpeed); 
	}
	
	
	
	/* Set Class */
	
	for(i = 1; i <= sldItems ; i++){
		
		if(i == sldLeadCurr){
			$("#sld-thumbnails-thumb"+i).addClass("selected");
		}
		else{
			$("#sld-thumbnails-thumb"+i).removeClass("selected");
		}
		
	}
	
	
	
	/* Next Slide */
	
	if((sldLeadCurr + 1) > sldItems){
		sldLeadCurr = 1;
	}
	else{
		sldLeadCurr = sldLeadCurr + 1;
	}
	
	/* Call Itself */
	sldInterval = setInterval(function() {
    							$("#sld-roll").stop(true,true);
    							moveSlide();
							  }, sldTime);

	
}