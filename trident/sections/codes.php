<?php 

	ini_set('display_errors', 1);

	$core->setScript("scripts/search.js");
	$userTriden = $core->getLoggedUser();

	if (empty($userTriden["user"])) {
		$core->setMessage("Debes iniciar sesión");
		$core->redirect($config->cf_domain."index.php?section=login");
	}
	else{

		$tplSection -> assign("__SITE_URL__", $config->cf_domain);	
		$tplSection -> assign("__STATUS__", 1);	
		/*$sqlCodesUser = "SELECT count(*) as total
						FROM codes
						WHERE user = '{$userTriden[user]}'
						LIMIT 1";
		
		$codesUser = $queryObj->query($sqlCodesUser, "select"); 

		$codes = $core -> getCodesUser($codesUser["total"], $userTriden["user"]);
		
		foreach ($codes as $key => $value) {
			$codeYearUsed = $core  -> codesUsed($userTriden['user']);
			
			$tplSection -> assign("__ID_CODE__", $value["id"]);
			$tplSection -> assign("__CODES_USED__", $codeYearUsed);

			
			$year = date("Y");
			$tplSection -> assign("__YEAR__", $year);
			if (!empty($value["used"])) {
				$tplSection -> assign("__CODE__", $value["code"]);
				$tplSection -> assign("__DATE__", "");
				$tplSection -> assign("__CLASS__", "disable");
				$tplSection -> assign("__URL__", "#");
				$tplSection -> assign("__USED__", "used");	
				$tplSection  -> parse("main.code.used");
				
			}
			elseif ($year == $value["valid"]){

				

				if ($value["valid"] == '2013') {
					$totalCodes = 4;
				}
				if ($value["valid"] == '2014') {
					$totalCodes = 8;
				}
				
				$tplSection -> assign("__CODES_YEAR__", $totalCodes );
				$codesActives = $totalCodes - $codeYearUsed;
				$tplSection -> assign("__CODES_ACTIVE__", $codesActives);
				$tplSection -> assign("__CODE__", '$10,000');
				$tplSection -> assign("__DATE__",  "Vigencia 31 de diciembre de 2013");
				$tplSection -> assign("__CLASS__", "valid");
				$tplSection -> assign("__USED__", "");
				$tplSection -> assign("__URL__", $config->cf_domain."index.php?action=usesCode&code=".$value["id"]);
				$tplSection  -> parse("main.code.valid.date");
				$tplSection  -> parse("main.code.valid");	
			}			
			else { 
				$tplSection -> assign("__CODE__", '$10,000');
				$tplSection -> assign("__USED__", "");
				$tplSection -> assign("__DATE__",  "Vigencia 31 de diciembre de 2014 ");
				$tplSection -> assign("__CLASS__", "valid2");
				$tplSection -> assign("__URL__", "#");
				$tplSection  -> parse("main.code.valid2.date");	
				$tplSection  -> parse("main.code.valid2");
			}
			

				
			$tplSection  -> parse("main.code");			
			}*/
			
			$tplSection -> parse("main.trident");	
		
		
	}


	
?>	