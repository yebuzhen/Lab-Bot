<?php
require_once("SiteRestHandler.php");
		
$view = "";
if(isset($_GET["view"]))
	$view = $_GET["view"];


switch($view){

	case "all":
		// Handle REST Url /site/list/
		$siteRestHandler = new SiteRestHandler();
		$siteRestHandler->getAllItems();
		break;
		
	case "single":
		// Handle REST Url /site/show/<id>/
		$siteRestHandler = new SiteRestHandler();
		$siteRestHandler->getItem($_GET["id"]);
		break;

	case "" :
		//404 - not found;
		break;
}
?>