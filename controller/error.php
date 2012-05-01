<?php

/**
 * Show
 *
 * Controller for Show
 * 
 * @author Maail
 */
class Error extends Controller
{
	function __construct()
	{
		parent::__construct();		
		$this->view->render('error');
	}
	
	function index()
	{
		//$this->view->render('error');
	}
}