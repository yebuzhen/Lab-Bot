<?php 
require_once("SimpleRest.php");
require_once("Site.php");

class SiteRestHandler extends SimpleRest {

	function getAllItems() {	

		$site = new Site();
		$rawData = $site->getAllItem();

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'Please login first!');		
		} else {
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);

		//Only response with Json data
		$response = $this->encodeJson($rawData);
		echo $response;
				

		//We only need Json data here, if need html and xml, uncomment below code.
		// if(strpos($requestContentType,'application/json') !== false){
		// 	$response = $this->encodeJson($rawData);
		// 	echo $response;
		// } else if(strpos($requestContentType,'text/html') !== false){
		// 	$response = $this->encodeHtml($rawData);
		// 	echo $response;
		// } else if(strpos($requestContentType,'application/xml') !== false){
		// 	$response = $this->encodeXml($rawData);
		// 	echo $response;
		// }
	}

	public function getSite($id) {

		$site = new Site();
		$rawData = $site->getSite($id);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No sites found!');		
		} else {
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
			
		//Only response with Json data
		$response = $this->encodeJson($rawData);
		echo $response;

		//We only need Json data here, if need html and xml, uncomment below code.
		// if(strpos($requestContentType,'application/json') !== false){
		// 	$response = $this->encodeJson($rawData);
		// 	echo $response;
		// } else if(strpos($requestContentType,'text/html') !== false){
		// 	$response = $this->encodeHtml($rawData);
		// 	echo $response;
		// } else if(strpos($requestContentType,'application/xml') !== false){
		// 	$response = $this->encodeXml($rawData);
		// 	echo $response;
		// }
	}
	
	public function encodeHtml($responseData) {
	
		$htmlResponse = "<table border='1'>";
		foreach($responseData as $key=>$value) {
    			$htmlResponse .= "<tr><td>". $key. "</td><td>". $value. "</td></tr>";
		}
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function encodeXml($responseData) {
		// Create SimpleXMLElement object
		$xml = new SimpleXMLElement('<?xml version="1.0"?><site></site>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
	
}
?>