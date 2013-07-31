<?php 

	ini_set('display_errors', 1);
	require_once("classes/events.class.php");
	require_once("labels/date.php");

	$event_obj = new Events();

	$config = new Configuration();
	$core = new Core(&$tplIndex);
	$queryObj = new QueryClass();
	$queryObj -> serverConnection();
	global $userTriden;


	$tplModule -> assign("__SITE_URL__", $config->cf_domain);
	if (empty($userTriden['user'])) { 
					
		$tplModule  -> assign("__MESSAGE_TEXT__", "No hay códigos disponibles");				
		$tplModule   -> parse("main.message");	
	}
	else{
		$type_promotion = $userTriden['type_promotion'];

		$sqlCodesValor = "SELECT valor
						FROM codes
						WHERE type = '$type_promotion'
						LIMIT 1";
		$codesValor = $queryObj->query($sqlCodesValor, "select");

	
		

		switch ($type_promotion){
			case 'card':
			
				$totaCodesUse = $core -> totaCodesUse($userTriden['id'],"",$type_promotion);

				if ($totaCodesUse < 1) {

					$listCodesUsedUser  = $core -> setCodesUser($userTriden['id'],$userTriden['type_promotion']);

				}
				else if ($totaCodesUse < 12){
				
					$listCodesUsedUser  = $core -> setCodesUser2($userTriden['id'],$userTriden['type_promotion']); 
				

				}
				else{
				
					$listCodesUsedUser  = $core -> getCodesUsedUser($userTriden['id']);
				}
				

		

				$year = date("Y");		

				$totalItems = 	count($listCodesUsedUser);

				$tplModule  -> assign("__ITEMS__", $totalItems);

			
				foreach ($listCodesUsedUser as $key => $value) {
					
					$data = explode(" ", $value["end_date"]);
					if ($data[4] == $year){				
						$tplModule  -> assign("__CODE__",  $value["code"]);
						$tplModule  -> assign("__PIN__", "Pin ".$value["pin"]);
						$tplModule  -> assign("__CLASS__", "card_user");
						
						$tplModule  -> assign("__ACTIVE__", "activo");
						$tplModule  -> assign("__FINISH__","Valido hasta ".$value["end_date"]);
					}
					else {
						$tplModule  -> assign("__CODE__", "");
						$tplModule  -> assign("__PIN__", "");
						$tplModule  -> assign("__CLASS__", "disable");
						$tplModule  -> assign("__ACTIVE__", "no activo");
						$tplModule  -> assign("__FINISH__","Valido del 01 de enero al ".$value["end_date"]);
						
					}

	

					
					$tplModule  -> assign("__VALOR__", $value["valor"]);					
					$tplModule  -> assign("__SALDO__",  $value["saldo"]);
				
					$tplModule   -> parse("main.card.codes");		
				
				}

				break;
			
			case 'gift':
			
				$tplModule  -> assign("__ITEMS__", 1);
				$sqlCodesCountUser = "SELECT count(*) total_code
										FROM codes
										WHERE user = '{$userTriden[id]}'
										AND type = '$type_promotion'";
				$codesCountUser = $queryObj -> query($sqlCodesCountUser, "select");
				
				$codeRand = $this->getRandCode($type_promotion,"2013");	



				if ($codesCountUser["total_code"] == 0) {			
						
						if (!empty($codeRand)) {
							if ($core-> getInfoCode($codeRand )) {	
									$data = date("Y-m-d H:i:s");  				
									$code = $core->getCode($codeRand);	
									$sqlCodeUpdate = "UPDATE  codes
												SET used = '1',
													user = '{$userTriden[id]}',
													used_date = '$data'
												WHERE id ={$codeRand}
												AND type = '$type_promotion'
												LIMIT 1";
								
								$codeUpdate= $queryObj->query($sqlCodeUpdate, ""); 		
									
							}
						}
							
						
				}
				


			
					
					$sqlCodesUsedUser = "SELECT id, code, used_date, end_date, valor, saldo, pin
										FROM codes
										WHERE user = '{$userTriden[id]}'
										AND type = '$type_promotion'
										LIMIT 1";
					$codesUsedUser = $queryObj->query($sqlCodesUsedUser, "select");
					if (!empty($codesUsedUser["code"])) {
						$date= explode("-", $codesUsedUser["end_date"]);
						$tplModule  -> assign("__CODE__",  $codesUsedUser["code"]);
						$tplModule  -> assign("__CLASS__", "card_user");
						$tplModule  -> assign("__ACTIVE__", "activo");
						$tplModule  -> assign("__PIN__", "Pin ".$codesUsedUser["pin"]);
						
						$tplModule  -> assign("__FINISH__", $event_obj->formatDate($codesUsedUser["end_date"])." de ".$date[0] );
						$tplModule  -> assign("__VALOR__", $codesUsedUser["valor"]);					
						$tplModule  -> assign("__SALDO__",  $codesUsedUser["saldo"]);
							$tplModule   -> parse("main.gift.code");	
					}
										
					else{
						$tplModule  -> assign("__CLASS__", "msg");
						$tplModule   -> parse("main.gift.msg");
					}

				break;
		}


				
		$tplModule   -> parse("main.{$type_promotion}");	
		
	
	}


	$core->setMessage("");
	$error = $this -> getMessage();
	unset($error["text"]);
	unset($error["type"]);
	
?>	