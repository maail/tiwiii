<?php

/**
 * Activity
 *
 * Controller for Activity
 * 
 * @author Maail
 */
class Activity extends Controller
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function index()
	{
		$this->view->pagetitle = "Activity";
		$this->view->render('activity');
	}
	
	function feed($filter, $page)
	{
		$this->model->feed($filter, $page);
	}

}