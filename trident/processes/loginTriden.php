<?php

	ini_set('display_errors', 1);	

	session_start();

	$user =  addslashes($_POST["user"]);

	if (!empty($_POST["password"])) {
		$password = md5(addslashes($_POST["password"]));	
	}


		
	$fieldValues["password"] = addslashes($_POST["password"]);	
	$fieldValues["user"] = addslashes($_POST["user"]);	

	$fieldValues["password_1"] = addslashes($_POST["password_1"]);
	$fieldValues["password_2"] = addslashes($_POST["password_2"]);	
	$fieldValues["password_3"] = addslashes($_POST["password_3"]);	
			
			

	$core -> setSession("loginUser", $fieldValues);
	$option = $_GET["option"];
	$userTriden = $core->getLoggedUser();
	$userLogin = $userTriden["user"];

	$option = empty($_GET["option"])?'login':$_GET["option"];
	switch ($option) {
		case 'login':	
			if (empty($user) or empty($password)) {
				$errors[] = "El usuario o password son incorrectos";	
			}
			 if(count($errors)  > 0){
			 	$response["status"] = 0;			 
				$core -> setMessage(join(",", $errors),"error");
				$core->redirect($config->cf_domain."index.php?section=login");
			}
			else{
				
				$sqlUserTriden = "SELECT id, active,name, last_name, promotion
							FROM users
							WHERE email = '{$user}'
							AND password = '{$password}'
							LIMIT 1";
			
				$userTriden = $queryObj->query($sqlUserTriden, "select"); 
				if(empty($userTriden["id"])){
					$response["status"] = 0;
					$core->setMessage("El usuario o password son incorrectos");
					$core->redirect($config->cf_domain."index.php?section=login");
				}

				else if($userTriden["active"] == "0"){
					$response["status"] = 1;
					$core->setMessage("El usuario está inactivo");
					$core->redirect($config->cf_domain."index.php?section=login");
				}

				else{			
					session_start();									
					$_SESSION["user"] = array(
												"id" => $userTriden["id"],		
												"user" => $user,
												"name" => $userTriden["name"],
												"last_name" => $userTriden["last_name"],
												"type_promotion" => $userTriden["promotion"]

											);
					$response["status"] = 2;					
					$core->redirect($config->cf_domain."index.php?section=home");	
					
				}
			}
			break;
		case 'recover':
			echo "user: ".$user;

				if (empty($user)) {
					$core->setMessage("El campo usuario esta vació");
					$core->redirect($config->cf_domain."index.php?section=login&option=recover");	 
				}

				else{
					$sqlUserTridenVerify = "SELECT id, active
								FROM users
								WHERE email = '{$user}'
								LIMIT 1";
					$userTridenVerify  = $queryObj->query($sqlUserTridenVerify , "select"); 


					if($userTridenVerify["active"] == "0"){
						$response["status"] = 1;
						$core->setMessage("El usuario está inactivo");
						$core->redirect($config->cf_domain."index.php?section=login&option=recover");
					}
					else{

						$newPass = $core ->cbMakeRandomString();
						$encryptedPass = md5($newPass);

						$sqlUpdatePass = "UPDATE users
										SET password = '{$encryptedPass}'
										WHERE email = '{$user}'";
						$updatePass = $queryObj->query($sqlUpdatePass , "select"); 

						$core -> send_mail_recup( "dbarrera@cie.com.mx", "Trident® Mashup", $user, $newPass);
						
						$core -> setMessage("Tu nueva contraseña ha sido enviada a tu correo");

						$core->redirect($config->cf_domain."index.php?section=login");				


					}
					
				}
				

				/*if (empty($fieldValues["password_1"])) {								
					$errors[] = "El campo password esta vació";	
				}
				if (empty($fieldValues["password_2"]) or empty($fieldValues["password_3"])) {	
					$errors[] = "Debe indicar su nueva contrañesa";	
				}
				
			
				 if(count($errors)  > 0){
				
					$core -> setMessage(join(",", $errors),"error");
					//$core->redirect($config->cf_domain."index.php?section=password&option=editPassword"); 
					echo $config->cf_domain."index.php?section=password&option=editPassword";
				}
				else{

					/*$password_1 = md5($fieldValues["password_1"]);
					$new_pass = md5($fieldValues["password_2"]);	
					$verify_pass = md5($fieldValues["password_3"]);	

					
							AND password = '{$password_1}'
							LIMIT 1";
			
					$userTridenVerify  = $queryObj->query($sqlUserTridenVerify , "select"); 

					if(empty($userTridenVerify["id"])){
						$response["status"] = 0;
						$core->setMessage("El password es incorrectos");
						$core->redirect($config->cf_domain."index.php?section=password&option=editPassword");
					}
		
					if ($fieldValues["password_2"] != $fieldValues["password_3"]) {
						$core->setMessage("La nueva contraseña debe ser igual a la contraseña a verificar");
						$core->redirect($config->cf_domain."index.php?section=password&option=editPassword");
					}
					
						else{  
						echo "entro";
						$sqlUserTriden = "SELECT id, active
							FROM users
							WHERE email = '{$userLogin}'
							AND password = '{$password_1}'
							LIMIT 1";
			
						$userTriden = $queryObj->query($sqlUserTriden, "select"); 
						if(empty($userTriden["id"])){
							$core->setMessage("El password es incorrecto", "error");
							$core->redirect($config->cf_domain."index.php?section=password&option=editPassword");
						}
						
						$sqlUpdateUser = "UPDATE users
										SET password = '{$new_pass}'
										WHERE email = '{$userLogin}'
										LIMIT 1";
						echo "$sqlUpdateUser ";
						$updateUser= $queryObj->query($sqlUpdateUser, "select"); 

						$core->setMessage("Se ha cambiado la contraseña");

						$core->redirect($config->cf_domain."index.php?section=home");
					}
				}*/

			break;

			case 'logout':
				$core->logout();
				$core->redirect($config->cf_domain);
		
			break;

	}
		
	if (!empty($userLogin)) {
		$core->redirect($config->cf_domain);
		$response["status"] = 0;
	}
	$core -> clearSession("loginUser");		
	//echo json_encode($response);
?>