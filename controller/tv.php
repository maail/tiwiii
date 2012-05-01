<?php

/**
 * TV
 *
 * Controller for TV
 * 
 * @author Maail
 */
class TV extends Controller
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function index()
	{
		$this->view->pagetitle = "All Shows";
		$this->view->render('tv');
	}
	
	function shows($genre, $page)
	{
		$this->model->shows($genre, $page);
	}
}