// Calendar Script

$(function() {
    var input = document.createElement("input");
    if(('placeholder' in input)==false) { 
                $('[placeholder]').focus(function() {
                        var i = $(this);
                        if(i.val() == i.attr('placeholder')) {
                                i.val('').removeClass('placeholder');
                                if(i.hasClass('user')) {
                                        i.removeClass('user');
                                        this.type='text';
                                } 

                                if(i.hasClass('password')) {
                                        i.removeClass('password');
                                        this.type='password';
                                } 

                                 if(i.hasClass('password_1')) {
                                        i.removeClass('password_1');
                                        this.type='text';
                                }                       
                        }
                }).blur(function() {
                        var i = $(this);        
                        if(i.val() == '' || i.val() == i.attr('placeholder')) {
                                if(this.type=='user') {
                                        i.addClass('user');
                                        this.type='text';
                                }

                                if(this.type=='password') {
                                        i.addClass('password');
                                        this.type='password';
                                }
                                i.addClass('placeholder').val(i.attr('placeholder'));
                        }
                }).blur().parents('form').submit(function() {
                        $(this).find('[placeholder]').each(function() {
                                var i = $(this);
                                if(i.val() == i.attr('placeholder'))
                                        i.val('');
                        })
                });
        }




});



$(document).ready(function(){
	/*ocultar cosigos*/ 
	$(".btn-ticket").click(function(){
			$("#cards").toggle();
			if ($('#cards').is(':visible')){
				$("#ticket_").attr('src', cf_site_domain+"images/btn-ticket_2.png");
			} 
			else{
				$("#ticket_").attr('src', cf_site_domain+"images/btn-ticket_1.png");
			}


		});

	/* Ancla footer*/
	var footer = $('footer').position(); 	
	if (footer.top < $(window).height()) {
		$('footer').css({ "color":"#FFF",
					  "position": "absolute",
						"bottom":"3px"});
		
	}
	


	if (status_code == 1) {
		$('#cards').css("display","none");
		$("#ticket_").attr('src', cf_site_domain+"images/btn-ticket_1.png");
	}

	
});

function session(e){
	e.preventDefault();
	var user = $("#user").val();
	var user = $("#user").val();
	var option = $("#option").val();
	
	$.ajax({
		type: 'GET',
		url: cf_site_domain + 'index.php?action=loginTriden',
		data: {response: 'json', user: user, user: user, option:option },
		context: document.body,
		dataType: 'json',
		cache: false,
	}).done(function(response) { 	

			if (response.status == 0) {
				alert('El usuario o user son incorrectos');				
			}	
			if (response.status == 1) {
				alert("El usuario está inactivo");
			}			
				
			if (response.status == 2) {
				user = $("#user").val("");
				user = $("#user").val("");
				option = $("#option").val("");
				location.href = cf_site_domain;

			}	 

	}).fail(function(XHR){alert("Se perdió la conexión, inténtalo nuevamente" +XHR.statusText );});//XHR.statusText
	
} 



function slideUpRacingCalendar(){
	
	slide_distance = 255; 
	slide_limit = rcalitems - 3;
	
	if(slide_limit >= rcalcurr_item ){
		$("#codes-list-containe").animate({"left": "-="+slide_distance+"px"}, "slow");
		rcalcurr_item = rcalcurr_item + 1;
	}
	
}


function slideDownRacingCalendar(){
	
	slide_distance = 255;
	slide_limit = 2;
	
	if(slide_limit <= rcalcurr_item ){
		$("#codes-list-containe").animate({"left": "+="+slide_distance+"px"}, "slow");
		rcalcurr_item = rcalcurr_item - 1;
	}
	
}
