<?php

session_start();

Class Site {
	var $sites;
		
	public function getAllSite(){
		return $this->sites;
	}
	
	public function getSite($id){
		
		$site = array($id => ($this->sites[$id]) ? $this->sites[$id] : $this->sites[1]);
		return $site;
	}

	public function __construct(){
        $this->sites = array(
        	'user' => htmlspecialchars($_SESSION['username']),
        	'currentLab' => htmlspecialchars($_SESSION['currentLab'])
        );
    }
}
?>