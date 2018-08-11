<?php
/* 
 * 菜鸟教程 RESTful 演示实例
 * RESTful 服务类
 */

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
        	'user' => $_SESSION['username']
        );
    }
}
?>