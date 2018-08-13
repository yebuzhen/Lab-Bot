<?php

session_start();

Class Site {
	var $items;
		
	public function getAllItem(){
		return $this->items;
	}
	
	public function getItem($id){

		if (array_key_exists($id, $this->items)) {
			$item = array($id => $this->items[$id]);
			return $item;
		}

		$item = array($id => 'not valid');
		return $item;
		
	}

	public function __construct(){

		if (isset($_SESSION['userType'])) {
			
			if ($_SESSION['userType'] == 'student') {

				$this->items = array(
		        	'userType' => $_SESSION['userType'],
		        	'email' => $_SESSION['username'],
		        	'currentLab' => $_SESSION['currentLab'],
		        	'ifHasRequest' => $_SESSION['ifHasRequest'],
		        	'request' => $_SESSION['request'],
		        	'ifSuspened' => $_SESSION['ifSuspened'],
		        	'requestPosition' => $_SESSION['requestPosition']
		        );

			} else if ($_SESSION['userType'] == 'admin') {

				$this->items = array(
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