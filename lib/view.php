<?php

/**
 * View
 *
 * class description
 * 
 * @author Maail
 */

class View
{
	function __construct(){
		//echo "This is the view</br>";
	}
	
	public function render($name, $noInclude = false){
		
		if($noInclude == true){
			require "../view/".$name.".php";
		}else{
			require '../view/header.php';
			require "../view/".$name.".php";
			require '../view/footer.php';
		}
	}
}