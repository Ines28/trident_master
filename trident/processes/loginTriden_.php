<?php

	ini_set('display_errors', 1);	


	session_start();
	$user =  addslashes($_GET["user"]);
	if (!empty($_GET["password"])) {
		$password = md5(addslashes($_GET["password"]));
	
	}

	if (!empty($_GET["new_password"])) {
		$new_pass = md5(addslashes($_GET["new_password"]));
		
	}
	
	if (!empty($_GET["verify_password"])) {
		$verify_pass = md5(addslashes($_GET["verify_password"]));	
	}
	
		
	$fieldValues["password"] = addslashes($_GET["password"]);	
	$fieldValues["user"] = addslashes($_GET["user"]);	
	$fieldValues["new_password"] = addslashes($_GETT["new_password"]);	
	$fieldValues["verify_password"] = addslashes($_GET["verify_password"]);	
			
			

	$core -> setSession("loginUser", $fieldValues);
	$option = $_GET["option"];
	$userTriden = $core->getLoggedUser();
	$userLogin = $userTriden["user"];
	switch ($option) {
		case 'login':

			if (empty($user) or empty($password)) {
				$errors[] = "El usuario o password son incorrectos";	
			}
			 if(count($errors)  > 0){
			 	$response["status"] = 0;			 
			/*	$core -> setMessage(join(",", $errors),"error");
				$core->redirect($config->cf_domain."index.php?section=login");*/
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
				/*	$core->setMessage("El usuario o password son incorrectos");
					$core->redirect($config->cf_domain."index.php?section=login");*/
				}

				else if($userTriden["active"] == "0"){
					$response["status"] = 1;
					/*$core->setMessage("El usuario está inactivo");
					$core->redirect($config->cf_domain."index.php?section=login");*/
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
					/*$core->redirect($config->cf_domain."index.php");	
					echo $config->cf_domain."index.php";	*/			
				}
			}
			break;
		case 'editPassword':

				
				echo "user: ".$userTriden["user"];

				$user =  addslashes($_GET["user"]);
					if (!empty($_GET["password"])) {
						$password = md5(addslashes($_GET["password"]));
					
					}

					if (!empty($_GET["new_password"])) {
						$new_pass = md5(addslashes($_GET["new_password"]));
						
					}
					
					if (!empty($_GET["verify_password"])) {
						$verify_pass = md5(addslashes($_GET["verify_password"]));	
					}
					
						
					$fieldValues["password"] = addslashes($_GET["password"]);	
					$fieldValues["user"] = addslashes($_GET["user"]);	
					$fieldValues["new_password"] = addslashes($_GETT["new_password"]);	
					$fieldValues["verify_password"] = addslashes($_GET["verify_password"]);	
							
							

					$core -> setSession("loginUser", $fieldValues);
					$option = $_GET["option"];
					$userTriden = $core->getLoggedUser();
					$userLogin = $userTriden["user"];
				if (empty($password)) {
					
					$errors[] = "El campo password esta vació";	
				}
				if (empty($new_pass) or empty($verify_pass)) {
			
					$errors[] = "Debe indicar su nueva contrañesa";	
				}
				
	
				 if(count($errors)  > 0){
				 	echo "errores: ". $password;
					$core -> setMessage(join(",", $errors),"error");
					$core->redirect($config->cf_domain."index.php?section=login&option={$option}");
				}
				else{

					if ($new_pass !=$verify_pass) {
						$core->setMessage("La nueva contraseña debe ser igual a la contraseña a verificar");
						$core->redirect($config->cf_domain."index.php?section=login&option={$option}");
					}
				
						$sqlUserTriden = "SELECT id, active
							FROM users
							WHERE email = '{$userLogin }'
							AND password = '{$password}'
							LIMIT 1";
			
						$userTriden = $queryObj->query($sqlUserTriden, "select"); 
						if(empty($userTriden["id"])){
							$core->setMessage("El password es incorrecto");
							$core->redirect($config->cf_domain."index.php?section=login&option={$option}");
						}
						
						$sqlUpdateUser = "UPDATE users
										SET password = '{$new_pass}'
										WHERE email = '{$userLogin}'
										LIMIT 1";
						echo "$sqlUpdateUser ";
						$updateUser= $queryObj->query($sqlUpdateUser, "select"); 

						$core->setMessage("Se ha cambiado la contraseña");

						$core->redirect($config->cf_domain);	

				}
			break;

			case 'logout':
				$core->logout();
				$core->redirect($config->cf_domain);
		
			break;

	}
		
	if (!empty($userLogin)) {
		
		$response["status"] = 0;
	}
	$core -> clearSession("loginUser");		
	echo json_encode($response);
?>