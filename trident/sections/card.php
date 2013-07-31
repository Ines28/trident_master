<?php
	
	//ini_set("display_errors","on");
	
	$core->setTitle($config->cf_title_default);
	$core->setMetaDescription("");
	
	$core->setStyle("styles/card.css");
	
	$tplSection -> assign("__SITE_URL__", $config->cf_domain);
	
	echo "entro";
?>