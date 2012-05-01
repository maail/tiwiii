<?php

/**
 * Twitter
 *
 * Controller for Twitter
 * 
 * @author Maail
 */
class Twitter extends Controller
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function callback()
	{
		 $this->model->callback();
	}
	
}