<?php 
	
	if (!empty($userTriden["user"])) {

		$type_promotion = $userTriden['type_promotion'];

		if (!empty($type_promotion)) {

			$randCode = $core -> getRandCode($type_promotion);	

			switch ($type_promotion) {
				case 'card':
					$year = date("Y");
					$sqlCodesUsedUser = "SELECT count(*) total_codes
										FROM codes
										WHERE user = '{$userTriden[id]}'
										AND used_date like '%$year%'
										AND type = '$type_promotion'";
					$codesUsedUser = $queryObj->query($sqlCodesUsedUser, "select"); 

			
					if ($year == '2013') {
						$limit = 4;
					}

					if ($year == '2014') {
						$limit = 8;
					}

					if ($codesUsedUser["total_codes"] < $limit ){
						$totalCodes = $core -> getCountCode($type_promotion);
						if ($totalCodes) {					
							if ($core-> getInfoCode($randCode)) {	
								$data = date("Y-m-d H:i:s");  				
								$code = $core->getCode($randCode);					
											$sqlCodeUpdate = "UPDATE  codes
											SET used = '1',
												user = '{$userTriden[id]}',
												used_date = '$data'
											WHERE id = $randCode
											AND code = '{$code}'
											AND type = '$type_promotion'
											LIMIT 1";
							
								$codeUpdate= $queryObj->query($sqlCodeUpdate, "select"); 

								$response["status"] = 1;
								$response["code"] = $code;
								$response["total_codes"] = $codesUsedUser["total_codes"] + 1;
							}
							else{
								$response["status"] = 0;
							}		
						
						}

						else{
							$response["status"] = 0;
						}
						
					}
					else{
						$response["status"] = 0;
					}
					break;
				
				case 'gift':
					$sqlCodesUsedUser = "SELECT count(*) total_codes
										FROM codes
										WHERE user = '{$userTriden[id]}'
										AND type = '$type_promotion'";
					$codesUsedUser = $queryObj->query($sqlCodesUsedUser, "select"); 

				
					if ($codesUsedUser["total_codes"] < 1) {
						if ($core-> getInfoCode($randCode)) {	
								$data = date("Y-m-d H:i:s");  				
								$code = $core->getCode($randCode);					
											$sqlCodeUpdate = "UPDATE  codes
											SET used = '1',
												user = '{$userTriden[id]}',
												used_date = '$data'
											WHERE id = $randCode
											AND code = '{$code}'
											AND type = '$type_promotion'
											LIMIT 1";
							
								$codeUpdate= $queryObj->query($sqlCodeUpdate, "select"); 
								
								$response["status"] = 1;
								$response["code"] = $code;
								$response["total_codes"] = $codesUsedUser["total_codes"] + 1;
						}
					}
					else{
						$response["status"] = 0;
					}
				break;
			}
					
		}
			
		else{			
			$response["status"] = 0;
		}

	}
	
	else{			
		$response["status"] = 0;

	}

	echo json_encode($response);
?>