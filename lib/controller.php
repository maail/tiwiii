<?php

/**
 * Controller
 *
 * class description
 * 
 * @author Maail
 */
class Controller
{
	function __construct(){
		//echo "Main Controller</br>";
		$this->view = new View();
		
	}
	
	public function loadModel($name){
		
		$path = '../model/'.$name.'_model.php';
		
		if(file_exists($path)){
			require '../model/'.$name.'_model.php';
			$modelName = $name.'_Model';
			$this->model = new $modelName();
		} 
	}
	
}