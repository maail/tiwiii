<?php

/**
 * Bootstrap
 *
 * class description
 * 
 * @author Maail
 */
class bootstrap
{
	function __construct(){
	
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
		$url = explode('/',$url);
		
		//print_r($url);
		
		if(empty($url[0])){    					#if no url redirect it to index.php and return false
			require '../controller/index.php';
			$controller = new Index();
			$controller->index();
			$controller->loadModel('index');
			return false;
		}
		
		$file = "../controller/".$url[0].".php";	#redirect url to specified
				
		if (file_exists($file)){				#check whether the required file exists
			require $file; 
		}else{									#else show error [possible 404] and return false
			require '../controller/error.php';
			$controller = new error();
			return false;
		}
		
		$controller = new $url[0];				# make a new url specific object of class url			
		$controller->loadModel($url[0]);		# load the model of the class
		
		if(isset($url[2]))
		{
			if(isset($url[3]))
			{
				if(isset($url[4]))
				{
					$controller->{$url[1]}($url[2], $url[3], $url[4]);
				}
				else
				{
					$controller->{$url[1]}($url[2], $url[3]);
				}
				
			}
			else
			{
				$controller->{$url[1]}($url[2]);
			}
		}
		else
		{
			if(isset($url[1])){
				$controller->{$url[1]}();
				
			}else{
				$controller->index();
			}
		}
	}
}