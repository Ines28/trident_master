$(document).ready(function(){
	
	$(".code_valid").click(usedCode);



	$(".code_valid").click(function(){
		$(this).html("Cargando c骴igo...."); 
		$(this).css({ "color":"#FFF",
					  "position": "relative",
						"top":"40px"});
	});

	
});

function usedCode(e){
	e.preventDefault();
	var code = $(this).attr("href");
	var array = code .split('_');
	var usedCode = array[1];

	
	
	$.ajax({
		type: 'GET',
		url: cf_site_domain + 'index.php?action=usesCode',
		data: {response: 'json'},
		context: document.body,
		dataType: 'json',
		cache: false,
	}).done(function(response) { 	
			
			if (response.status == 0) {
				alert('No hay codigos disponibles');				
			}	
			if (response.status == 1) {

				$("#code_"+usedCode).parent(".valid ").addClass("card_user");
				$("#code_"+usedCode).parent(".card_user").removeClass("valid");

				
				$("#code_"+usedCode).replaceWith( '<div class="card_user code"> 
														<div class="user_code">
															<strong>' + response.code + '</strong>
															<span>activo</span>
														</div>
														<div class="detail_code">
															<p>VALOR: <strong>'+response.code +'</strong></p>
															<p>SALDO: <strong>$10,000.鞍  </strong></p>
															<p class="finish_date">valido hasta 31 de diciembre de 2013 </p>
														</div>								
													</div> ');		
				$("#total_codes").html(response.total_codes);		
				

				/*<div class="card_user code"> 
								<div class="user_code">
									<strong>85236974</strong>
									<span>activo</span>
								</div>
								<div class="detail_code">
									<p>VALOR: <strong> $10,000.鞍 </strong></p>
									<p>SALDO: <strong>$10,000.鞍  </strong></p>
									<p class="finish_date">valido hasta 31 de diciembre de 2013 </p>
								</div>
								
							</div> */
			}
				
	}).fail(function(XHR){alert("Se perdi贸 la conexi贸n, int茅ntalo nuevamente" +XHR.statusText );});//XHR.statusText
	
}  


/*function usedCode(e){
	e.preventDefault();
	var code = $(this).attr("href");
	var array = code .split('_');
	var usedCode = array[1];

	
	$.ajax({
		type: 'GET',
		url: cf_site_domain + 'index.php?action=usesCode',
		data: {response: 'json', code: usedCode},
		context: document.body,
		dataType: 'json',
		cache: false,
	}).done(function(response) { 	
		
	
			if (response.status == 0) {
				alert('No se encontr贸 el c贸digo');				
			}	
			if (response.status == 1) {
				$("#code_"+usedCode).replaceWith( "<span>" + response.code + "</span>" );				
			}

				
	}).fail(function(XHR){alert("Se perdi贸 la conexi贸n, int茅ntalo nuevamente" +XHR.statusText );});//XHR.statusText
	
} */