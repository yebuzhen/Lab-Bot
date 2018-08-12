<?php

session_start();

Class Site {
	var $sites;
		
	public function getAllSite(){
		return $this->sites;
	}
	
	public function getSite($id){

		if (array_key_exists($id, $this->sites)) {
			$site = array($id => $this->sites[$id]);
			return $site;
		}

		$site = array($id => 'not valid');
		return $site;
		
	}

	public function __construct(){

		if (isset($_SESSION['userType'])) {
			
			if ($_SESSION['userType'] == 'student') {

				$this->sites = array(
		        	'userType' => $_SESSION['userType'],
		        	'email' => $_SESSION['username'],
		        	'currentLab' => $_SESSION['currentLab'],
		        	'ifHasRequest' => $_SESSION['ifHasRequest'],
		        	'request' => $_SESSION['request'],
		        	'ifSuspened' => $_SESSION['ifSuspened'],
		        	'requestPosition' => $_SESSION['requestPosition']
		        );

			} else if ($_SESSION['userType'] == 'admin') {

				$this->sites = array(
		        	'userType' => $_SESSION['userType'],
		        	'email' => $_SESSION['username'],
		        	'currentLab' => $_SESSION['currentLab'],
		        	'ifHandlingRequest' => $_SESSION['ifHandlingRequest'],
		        	'requestStudent' => $_SESSION['requestStudent']
		        );

			}

		}

    }
}
?>