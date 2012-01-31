<?php

class User{
	
	public $id;
	public $token;
	public $loginDate;
	public $unusedCitizen;
	public $points;
	
	function __construct($input) {
		$this->id = $input['ID'];
		$this->token = $input['Token'];
		$this->loginDate = $input['LoginDate'];
		$this->unusedCitizen = $input['UnusedCitizen'];
		$this->points = $input['Points'];
	}
	
}

?>
