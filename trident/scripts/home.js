// Calendar Script

$(document).ready(function(){
	
	loadCldEvents(cldMItemDefault,1);

	
});


function loadCldEvents(month,menuItem){
	
	selectCldMenuItem(menuItem);
	$('#'+cldContentID).replaceWith("<div id=\""+cldContentID+"\"><img src=\""+cldSiteUrl+"images/spinloader-1.gif\" alt=\"cargando\" id=\"cld-menu-spiner\" /></div>");
	
	$.post(cldSiteUrl+"index.php?action=getEvents", { month: month, site_url: cldSiteUrl }, 
				function(data){ 

					$('#'+cldContentID).replaceWith("<div id=\""+cldContentID+"\">"+data+"</div>");
					$('#'+cldContentID).hide();
					$('#'+cldContentID).fadeIn("slow");
	});
	
	
}

function loadCldTtheater(menuItem){

	selectCldMenuItem(menuItem);
	
	$('#'+cldContentID).replaceWith("<div id=\""+cldContentID+"\"><img src=\""+cldSiteUrl+"images/spinloader-1.gif\" alt=\"cargando\" id=\"cld-menu-spiner\" /></div>");

	$.post(cldSiteUrl+"index.php?action=getEvents",{}, 
				function(data){ 
					
					$('#'+cldContentID).replaceWith("<div id=\""+cldContentID+"\">"+data+"</div>");
					$('#'+cldContentID).hide();
					$('#'+cldContentID).fadeIn("slow");
	});
	
	
}


function selectCldMenuItem(menuItem){
	
	for(i=0; i<=cldMTotalMonths; i++){
		if(i == menuItem){
			$("#month-menu-"+i).addClass("selected");
			$("#month-menu-line-"+i).show();
		}
		else{
			$("#month-menu-"+i).removeClass("selected");
			$("#month-menu-line-"+i).hide();
		}
	}
	
}