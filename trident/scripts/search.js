// Search Module Script

$(document).ready(function(){
						   
	$("#"+csrchSearchbox).click(function () {
			
		if($(this).val() == csrchSearchdefault){
			$(this).val("");
		}
		
			
	});
			
			
			
	$("#"+csrchSearchbox).focusout(function () {
			
		if($(this).val() == ""){
			$(this).val(csrchSearchdefault);
		}
		
				
	});
	
	
	
	$("#search-top").keypress(function (e) {
											 
		search_keywords = $(this).val();
		
		if(e.keyCode == 13){
			location.href = csrchSiteUrl + 'buscar/' + search_keywords + "/page/1/";
		}
			
		 									
	});
	
	
	
});




function submitSearchAdv(boxID){
											 
	search_keywords = $("#"+boxID).val();
	location.href = csrchSiteUrl + 'buscar/' + search_keywords + "/page/1/";
	
}