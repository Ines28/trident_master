<?php

	ini_set('display_errors', 1);	



	session_start();
	$user =  addslashes($_POST["user"]);
		
	

	$fieldValues["password_1"] = addslashes($_POST["password_1"]);
	$fieldValues["password_2"] = addslashes($_POST["password_2"]);	
	$fieldValues["password_3"] = addslashes($_POST["password_3"]);	
			

	$core -> setSession("loginUser", $fieldValues);
	$option = $_GET["option"];
	$userTriden = $core->getLoggedUser();
	$userLogin = $userTriden["user"];



				if (empty($fieldValues["password_1"])) {								
					$errors[] = "El campo password esta vació";	
				}
				if (empty($fieldValues["password_2"]) or empty($fieldValues["password_3"])) {	
					$errors[] = "Debe indicar su nueva contrañesa";	
				}
				
			
				 if(count($errors)  > 0){
				 	var_dump($errors);
					$core -> setMessage(join(",", $errors),"error");
					$core->redirect($config->cf_domain."index.php?section=password&option={$option}");
				}
				else{

					$password_1 = md5($fieldValues["password_1"]);
					$new_pass = md5($fieldValues["password_2"]);	
					$verify_pass = md5($fieldValues["password_3"]);	

					$sqlUserTridenVerify = "SELECT id
							FROM users
							WHERE email = '{$userLogin}'
							AND password = '{$password_1}'
							LIMIT 1";
			
					$userTridenVerify  = $queryObj->query($sqlUserTridenVerify , "select"); 
					echo "id: ".$userTridenVerify["id"];
					if(empty($userTridenVerify["id"])){
						$core->setMessage("El password es incorrectos");
						$core->redirect($config->cf_domain."index.php?section=password&option=editPassword");
					}
		
					elseif ($fieldValues["password_2"] != $fieldValues["password_3"]) {
						$core->setMessage("La nueva contraseña debe ser igual a la contraseña a verificar");
						$core->redirect($config->cf_domain."index.php?section=password&option=editPassword");
					}
					
					else{  
						
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

										
						$updateUser= $queryObj->query($sqlUpdateUser, "select"); 
						//$core -> send_mail_recup( "premier3@trident-mashup.mx", "TRIDEN", $userLogin, $fieldValues["password_2"]);
						$core->setMessage("Se ha cambiado la contraseña");

						$core->redirect($config->cf_domain."index.php?section=home");
					}
				}


	?>