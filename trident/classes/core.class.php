<?php
	
	class Core extends QueryClass{
		
		public $__session_id;
		

		var $template;
		

		

		function Core($template){
			$this->template = $template;
			global $config;

			$this->__session_id = $this ->__prefix.md5($config->cf_domain);

		}
		
		
		
		function setTitle($title){
			$this->template->assign("__LABEL_TITLE__", $title);
		}
		
		
		
		function setMetaDescription($description){
			$this->template->assign("__META_DESCRIPTION__", $description);
		}
		
		
		
		function setStyle($style, $type = "screen" ){
			
			$this->template->assign("__STYLE_URL__", $style);
			$this->template->assign("__STYLE_MEDIA__", $type);
			$this->template->parse("main.style");
				
		}
		
		
		
		function setScript($script){
			
			$this->template->assign("__SCRIPT_URL__", $script);
			$this->template->parse("main.script");
				
		}
		
		
		
		function getLoggedUser(){
		
			session_start();
			
			$user = $_SESSION["user"];
			
			if($user == NULL){
				return false;
			}
			else{
				return $user;
			}
			
		}
		
		
		
		
		function logout(){
			$_SESSION["user"] = NULL;
		}
		
		
		
		function getModule($module){
			
			$tplModule = new XTemplate("modules/templates/".$module.".html");
			require_once("modules/".$module.".php");
			
			$tplModule -> parse("main");
			return $tplModule -> render("main");
			
		}
		
		
		
		function getCurrentUrl(){
			
			$url_domain = $_SERVER['SERVER_NAME'];
			$url_params = $_SERVER['QUERY_STRING'];
			
			if(empty($url_params)){
				$this_url = "http://".$url_domain."/".$this->cf_index_default;
			}
			else{
				$this_url = "http://".$url_domain."/".$this->cf_index_default."?".$url_params;
			}
			
			return $this_url;
			
		}
		
		
		
		function setUrl($url){
			session_start();
			$_SESSION["url"] = $url;
		}
		
		
		function getUrl(){
			session_start();
			return $_SESSION["url"];
		}
		
		
		
		function redirect($url){
			
			header("Location: $url");
			
		}
		
		public function setSession($name,$value){
			
			$_SESSION[$this->__session_id][$name] = $value;
		
		}

		
		public function setMessage($message,$type="advice"){
			
			$amessage["text"] = $message;
			$amessage["type"] = $type;
			
			$this -> setSession("message",$amessage);
			
		}
		
		
		public function getSession($name){
			
			return $_SESSION[$this->__session_id][$name];
		
		}
		
		public function getMessage(){
		
			$message = $this -> getSession("message");
			
			if(empty($message["text"])){
				return false;
			}
			else{
				return $message; 
			}
		
		}
		
			
		public function clearSession($name){
		
			unset($_SESSION[$this->__session_id][$name]);
			
		}
		
		function getMenuItems($menuID){
			
			$this->serverConnection();
			
			$sqlItems = "SELECT id, alias, name, url, target, parent, ordering 
						 FROM site_menus_items
						 WHERE menu = $menuID 
						 ORDER BY ordering ASC";
							
			$items_list = $this->query($sqlItems, "list");
			$items = array();
			
			while($row = mysql_fetch_row($items_list)){
				
				$items[] = array(
									"id" => $row[0],
									"alias" => $row[1],
									"name" => $row[2],
									"url" => $row[3],
									"target" => $row[4],
									"parent" => $row[5],
									"ordering" => $row[6]
								);
				
			}
			
			return $items;
			
		}
		
		
		
		
		
		
		
		function pagerResults($totalRecords,$resPerPage,$crntPage,$urlparams){
		
				$url = empty($urlparams)?"page/":$urlparams."page/";
				define("__PAGER_RANGE__", 3);
			
				$currentPage = (empty($crntPage) or $crntPage == 0) ? 1 : $crntPage;
				$totalpages =(integer)($totalRecords/$resPerPage);
				$totalmodule = $totalRecords%$resPerPage; 
				
				$totalpages = ($totalRecords <= $resPerPage || $totalmodule == 0) && $totalpages !== 0? $totalpages : $totalpages + 1;
				$recordBegin = ($currentPage * $resPerPage) - $resPerPage;
				
				/*******************************
				* Pager Get Numbers Range
				*******************************/
				
				if($totalpages > 1){
				
					if($currentPage == 1){
						if($totalpages <=(__PAGER_RANGE__ * 2) ){
						
							$range["begin"] = 1;
							$range["end"] = $totalpages;
							$range["next"] = $url."2/";
							$range["prev"] = false;
										 
						}
						else{
						
							$range["begin"] = 1;
							$range["end"] = ((__PAGER_RANGE__ * 2) + 1);
							$range["next"] = $url."2/";
							$range["prev"] = false;
						
						}
					}
					else if($currentPage == $totalpages){
					
						if($totalpages <=(__PAGER_RANGE__ * 2) ){
						
							$range["begin"] = 1;
							$range["end"] = $totalpages;
							$range["next"] = false;
							$range["prev"] = $url.($totalpages - 1)."/";
										 
						}
						else{
						
							$range["begin"] = $totalpages - (__PAGER_RANGE__ * 2);
							$range["end"] = $totalpages;
							$range["next"] = false;
							$range["prev"] = $url.($totalpages - 1)."/";
						
						}
					
					}
					else{
						
						if(($currentPage - __PAGER_RANGE__) < 1){
							
							$range["begin"] = 1;
							$range["prev"] = $url.($currentPage - 1)."/";
							
						}
						else{
						
							$range["begin"] = $currentPage - __PAGER_RANGE__;
							$range["prev"] = $url.($currentPage - 1)."/";
							
						}
						
						if(($currentPage + __PAGER_RANGE__) > $totalpages){
							
							$range["end"] = $totalpages;
							$range["next"] = $url.($currentPage + 1)."/";
							
						}
						else{
							
							$range["end"] = $currentPage + __PAGER_RANGE__;
							$range["next"] = $url.($currentPage + 1)."/";
							
						}
						
						if(abs($range["begin"] - $range["end"]) < (__PAGER_RANGE__ * 2)){
						
							$ro = abs($range["begin"] - $range["end"]);
							$ra = __PAGER_RANGE__ * 2;
							$rd = abs($ro - $ra);
							
							if($range["begin"] == 1){
								$range["end"] = (($range["end"] + $rd) > $totalpages)?$totalpages:$range["end"] + $rd;
							}
							else if($range["end"] == $totalpages){
								$range["begin"] = (($range["begin"] - $rd) < 1)?1:$range["begin"] - $rd;
							}
						}
						
						
						
						
					
					}
					
					/*******************************
					* Get Numbers Link
					*******************************/
					
					$links = array();
					
					for($i = $range["begin"]; $i <= $range["end"]; $i++){
						$links[$i] = $url.$i."/";
					}
					
					
					
				}
				else{
					$range["begin"] = 0;
					$range["end"] = 0;
					$range["next"] = false;
					$range["prev"] = false;
				}
				
				/*******************************
				* Pager Array
				*******************************/
					
					$pager = array(
								"pages" => $totalpages,
								"begin" => $recordBegin,
								"range" => $range,
								"links" => $links
							   );
				
				/******************************/
				
				
				return $pager;
			
		}
		
		
		
		
		
		
		
		function getPictureType($picture){
		
			if(eregi("(image/gif)",$picture['type'])){
				$pictureType = "gif";
			}
			else if(eregi("(image/jpeg)",$picture['type']) or eregi("(image/pjpeg)",$picture['type'])){
				$pictureType = "jpg";
			}
			else if(eregi("(image/png)",$picture['type']) or eregi("(image/x-png)",$picture['type'])){
				$pictureType = "png";
			}
			
			return $pictureType;
			
		}
		
		
		
		
		
		
		
		function getSlidePictures($slideID){
		
			$this->serverConnection();
			
			$sqlPictures = "SELECT id, file, cover
							FROM slider_pictures
							WHERE slide = $slideID
							ORDER BY id ASC";
				
			$pictures = $this -> query( $sqlPictures, "list" );
			
			$apictures = array();
			
			while($row = mysql_fetch_row($pictures)){
				
				$apictures[] = array(
										"id" => $row[0],
										"file" => $row[1],
										"cover" => $row[2]
									);
				
			}
			
			if(count($apictures) > 0){
				return $apictures;
			}
			else{
				return false;
			}
			
			
		
		}
		
		function getListCode($type_promotion, $year){

			$this->serverConnection();

			$sqlListCodes ="SELECT id, type 
						FROM codes 
						WHERE used = 0 
						AND user = ''";
						

						if ($year == date('Y')) {
						$sqlListCodes.= " AND start_date <= CURDATE()
								AND end_date >= CURDATE()";
						}


						$sqlListCodes .= " AND used_date = '0000-00-00 00:00:00' 
						AND type='$type_promotion'
						AND end_date like '%$year%'
						ORDER BY id ASC";
				
			$listCodes = $this -> query($sqlListCodes, "list"); 

			$codes = array();

			while($row = mysql_fetch_row($listCodes)){				
				$codes[] = array(
									"id" => $row[0],
									"type" => $row[1]
								);			
			}	

			
			return $codes;

		}

		function getCountCode($type_promotion, $year){
			$this->serverConnection();
			$sqlCodes ="SELECT count(*) as total
						FROM codes 
						WHERE used = 0 
						AND user = ''";
						if ($year == date('Y')) {
						$sqlCodes .= " AND start_date <= CURDATE()
										AND end_date >= CURDATE() ";
						}
						
						$sqlCodes .=  "AND used_date = '0000-00-00 00:00:00' 
						AND type ='$type_promotion'
						AND end_date like '%$year%'
						ORDER BY id ASC";
				
			$countCodes = $this -> query($sqlCodes, "select"); 
						
			return $countCodes["total"];
		}

		function getRandCode($type_promotion, $year){
		
			$random_codes = $this->getListCode($type_promotion, $year);
			shuffle($random_codes); 
			$random = array_rand($random_codes,1);				
			return $random_codes[$random]["id"];
		}
		
		function getInfoCode($code){
			$this->serverConnection();

			$sqlCode = "SELECT id, used, user
						FROM codes 
						WHERE id = $code  
						AND used = 0
						AND user = ''
						AND start_date <= CURDATE()
						AND end_date >= CURDATE()";
			$code = $this -> query($sqlCode, "select"); 
			
			if (!empty($code["id"])) {
				return true;
			}
			else{
				return false;
			}
		

		}
		function getCodesUsedUser($user, $type){

			require_once("classes/events.class.php");
			require_once("labels/date.php");
			$event_obj = new Events();

			$this->serverConnection();
			if (!empty($type)) {
				$type_promotion = $type;
			}
			else{
				$type_promotion = "card";
			}
			$sqlListCodesUser = "SELECT id, code, used_date, end_date, valor, saldo, pin
								FROM codes
								WHERE user = '{$user}'
								AND type = '$type_promotion'
								ORDER BY end_date ASC";
			$listCodesUser = $this -> query($sqlListCodesUser, "list"); 

			while($row = mysql_fetch_row($listCodesUser)){	

				$date = explode("-",$row[3]);		
				$codesUser[$row[0]] = array(
									"id" => $row[0],
									"code" =>$row[1],
									"end_date" => $event_obj->formatDate($row[3])." de ".$date[0],
									"valor" => $row[4],
									"saldo" => $row[5],
									"pin" => $row[6],
									);		

			}

			return $codesUser;
		}
		
		function getCode($id_code){

			$sqlCode ="SELECT id, code, valor,saldo,end_date, pin
						FROM codes 
						WHERE id = $id_code
						LIMIT 1";

						
			$code = $this -> query($sqlCode, "select"); 
			
			if (!empty($code["id"])) {
				return $code;
			}
			else{
				return false;
			}
		}

		
		
		function codesUsed($user){
			$date = date("Y");
			$this->serverConnection();
			$sqlListCodesUser = "SELECT count(*) as total
								FROM codes
								WHERE user = '{$user}'
								AND used_date like '%$date%'";

			$codeUser = $this -> query($sqlListCodesUser, "select"); 

		

			return $codeUser["total"];
		}
	
		
		function cbMakeRandomString( $stringLength = 8, $noCaps = false ) {
			if ( $noCaps ) {
				$chars		=	'abchefghjkmnpqrstuvwxyz0123456789';
			} else {
				$chars		=	'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			}
			$len			=	strlen( $chars );
			$rndString		=	'';
			mt_srand( 10000000 * (double) microtime() );
			for ( $i = 0; $i < $stringLength; $i++ ) {
				$rndString	.=	$chars[mt_rand( 0, $len - 1 )];
			}
			return $rndString;
		}	


		function send_mail_recup( $mailSender, $nombreSender, $mailAmigo, $newPasswod ){
	
			$html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<p>Tident<span class="trident_r">®</span>  te envía tu clave</p>
			<p>Este correo está asociado a tu cuenta de usuario en <a href="http://trident.ocesa.com.mx" target="_blank"> trident.ocesa.com.mx</a>. Este mensaje te llegó porque has solicitado la recuperación de tu contraseña.
		Te recordamos que para entrar como usuario registrado a <a href="http://trident.ocesa.com.mx" target="_blank"> trident.ocesa.com.mx</a> es necesario introducir estos datos:
		</p>
			
			<ul>
			<li>Correo: <strong>'.$mailAmigo.'</strong></li>
			<li>Nueva contraseña: <strong>'.$newPasswod .'</strong></li>
			</ul>
			
			<p>Si has recibido este correo por error, no te preocupes. Ingresa al sitio web con esta nueva contraseña y cámbiala por una de tu elección desde el menú de usuario.
		Este correo fue generado automáticamente desde <a href="http://trident.ocesa.com.mx" target="_blank">http://trident.ocesa.com.mx</a>, por favor no lo respondas.</p>
			<a href="http://www.elplaza.mx/">
			
			</a>
			</body></html>';

		            $subject = "Nueva contraseña";
		            //$mail_sender = "contactoocesa@cie.com.mx";

					//mosMail($mailSender, $nombreSender, $mailAmigo, $subject, $html);
		            if($this ->mosMail($mailSender, $nombreSender, $mailAmigo, $subject, $html)){
						//echo "<script language='javascript' type='text/javascript'>alert('Env?o satisfactorio')</script>"; 
					}else{
						//echo "<script language='JavaScript'>alert('Fall? el env?o, verifica tu informaci?n')</script>"; 
					}
		    //<img alt="mail_bienvenida" src="http://67.192.170.212/ep2013/images/ftr/logo-ftr.png" style="border: 0 none; background: #000000" />
		}

		function mosMail( $myMail, $myName, $to, $subject, $message ){

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			$headers .= 'To: <' . $to . ">\r\n";
			$headers .= 'From: ' . $myName . '<' . $myMail . '>' . "\r\n";

			if( mail( $to, $subject, $message, $headers ) )
				return true;
			else
				return false;
		}
		


		/*Nueva versión códigos */

		function totaCodesUse($user, $year,$type){

			$this->serverConnection();
			$sqlCodesUser = "SELECT count(*) as total
						FROM codes
						WHERE user = {$user}
						AND type = '{$type}'";
						if (!empty($year)) {
							$sqlCodesUser .=" AND end_date like '%$year%'";
						}
			$sqlCodesUser .=" LIMIT 1";		

			$codesUser =$this -> query($sqlCodesUser, "select");   		

			return $codesUser["total"];
		}


		function setCodesUser($user,$type_promotion){	

			require_once("classes/events.class.php");
			require_once("labels/date.php");
			$event_obj = new Events();

			$codesUser = array();
			$totalCodes_2013 =  $this -> getCountCode($type_promotion, "2013");			
			$totalCodes_2014 =  $this -> getCountCode($type_promotion, "2014");

			$codesUser2013 = $this -> totaCodesUse($user, "2013");
			$codesUser2014 = $this -> totaCodesUse($user, "2014");
				
 			if ($type_promotion == 'card') {
				if ($totalCodes_2013 < 4) {
					$codes_2013 = $totalCodes_2013;
				}
				else{
					$codes_2013 = 4;
				}

				if ($totalCodes_2014 < 8) {
					$codes_2014 = $totalCodes_2014;
				}
				else{
					$codes_2014 = 8;
				}

				$listCodes = $codes_2013 + $codes_2014;
			}

			if ($type_promotion == 'gift') {
				$totalCodes =  $this -> getCountCode($type_promotion, "2013");	
		

				if ($totalCodes == 0 ) {
					$listCodes = 1; 
				}
			}
			

			if (count($codesUser) < $listCodes) {
				
				while (count($codesUser) < $listCodes) {

					if (count($codesUser) < 4 and $totalCodes_2013 > 0  ){

						$codeRand = $this->getRandCode($type_promotion,"2013");
					}
					else{
						$codeRand = $this->getRandCode($type_promotion,"2014");
					}
					
					if ($codeRand) {			
						$code = $this->getCode($codeRand);
						if ($code) {
							if (!array_key_exists($codeRand, $codesUser)) {	

								$date = explode("-",$code["code"]);	
							    $codesUser[$codeRand] = array(
										"id" => $code["id"],
										"code" => $code["code"],
										"valor" => $code["valor"],
										"saldo" => $code["saldo"],
										"pin" => $code["pin"],
										"end_date" => $event_obj->formatDate($code["date"])." de ".$date[0]
								);	

							    $data_1 = date("Y-m-d H:i:s");  

								$sqlCodeUpdate = "UPDATE  codes
											SET used = '1',
												user = '{$user}',
												used_date = '$data_1'
											WHERE id = $code[id]
											AND code = '$code[code]'
											AND type = '$type_promotion'
											LIMIT 1";							
								$codeUpdate= $this->query($sqlCodeUpdate, "select"); 
							}
						}						
					}
					else if (!$codeRand){
						break;
					}	
				}	
			}
			return $this -> getCodesUsedUser($user);		
		
		}


		function setCodesUser2($user,$type_promotion){	
			
			require_once("classes/events.class.php");
			require_once("labels/date.php");
			$event_obj = new Events();


			$totalCodes_2013 =  $this -> getCountCode($type_promotion, "2013");	
			$codesUser2013 = $this -> totaCodesUse($user, "2013",$type_promotion);
			$codes_2013  = 4 - $codesUser2013;

			

			if ($codes_2013 > 0) {
				
				if ($codes_2013 < $totalCodes_2013) {
					
						$listCodes2013 = $codes_2013;
					}
				else{
					
						$listCodes2013 = $totalCodes_2013;
				}
				
				if (count($codesUser_1) < $listCodes2013) {
					while (count($codesUser_1) < $listCodes2013) {
						$codeRand = $this->getRandCode($type_promotion,"2013");
											
						if ($codeRand) {			
							$code = $this->getCode($codeRand);
							if ($code) {
								if (!array_key_exists($codeRand, $codesUser)) {
									$date = explode("-",$code["code"]);	
								    $codesUser_1[$codeRand] = array(
											"id" => $code["id"],
											"code" => $code["code"],
											"valor" => $code["valor"],
											"saldo" => $code["saldo"],
											"pin" => $code["pin"],
											"end_date" => $event_obj->formatDate($code["date"])." de ".$date[0]
									);	

								    $data_1 = date("Y-m-d H:i:s");  
									$sqlCodeUpdate = "UPDATE  codes
												SET used = '1',
													user = '{$user}',
													used_date = '$data_1'
												WHERE id = $code[id]
												AND code = '$code[code]'
												AND type = '$type_promotion'
												LIMIT 1";							
									$codeUpdate= $this->query($sqlCodeUpdate, "select"); 
								}
							}						
						}
						else if (!$codeRand){
							break;
						}	
					}	
				}
			}
			

			$totalCodes_2014 =  $this -> getCountCode($type_promotion, "2014");	
			$codesUser2014 = $this -> totaCodesUse($user, "2014",$type_promotion);
			$codes_2014  = 8 - $codesUser2014;

			

			if ($codes_2014 > 0) {

				if ($codes_2014 < $totalCodes_2014) {
						$listCodes2014 = $codes_2014;
					}
				else{
						$listCodes2014 = $totalCodes_2014;
				}
		

				if (count($codesUser_2) < $listCodes2014) {
					while (count($codesUser_2) < $listCodes2014) {
						$codeRand = $this->getRandCode($type_promotion,"2014");
						
						if ($codeRand) {			
							$code = $this->getCode($codeRand);
							if ($code) {
								if (!array_key_exists($codeRand, $codesUser)) {	

									$date = explode("-",$code["code"]);	
								   $codesUser_2[$codeRand] = array(
											"id" => $code["id"],
											"code" => $code["code"],
											"valor" => $code["valor"],
											"saldo" => $code["saldo"],
											"pin" => $code["pin"],
											"end_date" => $event_obj->formatDate($code["date"])." de ".$date[0]
									);	
								    $data_1 = date("Y-m-d H:i:s");  

									$sqlCodeUpdate = "UPDATE  codes
												SET used = '1',
													user = '{$user}',
													used_date = '$data_1'
												WHERE id = $code[id]
												AND code = '$code[code]'
												AND type = '$type_promotion'
												LIMIT 1";							
									$codeUpdate= $this->query($sqlCodeUpdate, "select"); 
								}
							}						
						}
						else if (!$codeRand){
							break;
						}	
					}
				}	
			}

	
			return $this -> getCodesUsedUser($user);			
		}


		function usernameIsEmail($username){
			
			if(strpos($username,"@") !== false){
				return true;
			}
			else{
				return false;	
			}
			
			
	}


	}
	
?>