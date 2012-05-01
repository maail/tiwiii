<?php

/**
 * Twitter
 *
 * Controller for Facebook
 * 
 * @author Maail
 */
class FB extends Controller
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function callback()
	{
		 $this->model->callback();
	}
	
	function update($status)
	{
		 $this->model->update($status);
	}
	
}