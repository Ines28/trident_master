<?php
	session_start();

	
	ini_set("display_errors","off");
	error_reporting(0);
	/*ini_set("session.bug_compat_warn","off");
	
	define("__PATH__", dirname(__FILE__) );*/
	
	require_once("configuration.php");
	require_once("classes/query.class.php");
	require_once("classes/core.class.php");
	require_once("classes/xtemplate.class.php");
	require_once("classes/class.phpmailer.php");
	
	date_default_timezone_set($config->cf_timezone);
	setlocale(LC_MONETARY, $config->cf_monetary);
	
	$target = $_GET["section"];
	$action = $_GET["action"];
	$section = ($target != "")?$target:"login";

	$tplIndex = new XTemplate("sections/templates/index.html");
	$config = new Configuration();
	$core = new Core(&$tplIndex);
	
	$queryObj = new QueryClass();
	$queryObj -> serverConnection();

	$userTriden = $core->getLoggedUser();

	if (empty($userTriden["user"])) {
		$style = "login";
		$status_site = "login_user";
	}
	else{
		$style = "template";
		$status_site = "session";			
	}
	
	if(empty($action)){
		
		if($core -> getMessage()){				
			$message = $core -> getMessage("loginUser");			
			$tplIndex -> assign("__MESSAGE_TEXT__", $message["text"]);
			$tplIndex -> assign("__MESSAGE_TYPE__", $message["type"]);				
			$tplIndex -> parse("main.{$status_site}.message");	
		}

		$tplIndex -> assign("__STYLE__",$style);
		$tplIndex -> assign("__SEARCH_JS__","search");
		$tplIndex -> assign("__SITE_URL__", $config->cf_domain);
		
		$tplIndex -> assign("__NAME__", $userTriden["name"] ." ".$userTriden["last_name"]);
		$tplIndex -> parse("main.{$status_site}.user");
		$tplIndex -> assign("__CODES__", $core->getModule("codes"));
		$tplIndex -> parse("main.{$status_site}.codes");

		
		$tplIndex -> assign("__SERVER_IP__",  $_SERVER['SERVER_ADDR']);
		
		$tplIndex -> assign("__INDEX_DEFAULT__", $config->cf_index_default);
		$tplIndex -> assign("__SEARCH__", $core->getModule("search"));
		$tplIndex -> assign("__NAVIGATION__", $core->getModule("mainmenu"));
		$tplIndex -> assign("__LABEL_FOOTER__", utf8_encode("© ".date("Y")." Aeroméxico. Todos los derechos reservados."));
		
		$url_section = "sections/".$section.".php";
		
		if(!file_exists($url_section)){
			$section = "error404";
		}
		
		$tplSection = new XTemplate("sections/templates/".$section.".html");
		require_once("sections/".$section.".php");
		$tplSection -> parse("main");
		
		$tplIndex -> assign("__CONTENT__", $tplSection -> render("main"));

	
		$tplIndex -> parse("main.{$status_site}");	
		$tplIndex -> parse("main");
		$tplIndex -> out("main");
		
	}
	else{
		
		require_once("processes/".$action.".php");
		
	}

?>