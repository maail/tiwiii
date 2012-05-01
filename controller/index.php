<?php

/**
 * Index
 *
 * Controller for Index
 * 
 * @author Maail
 */
class Index extends Controller
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function index()
	{
		$this->view->pagetitle = "Home";
		$this->view->render('index');
	}
	
	function home($type, $page, $filter)
	{
		$this->view->home = $this->model->home($type, $page, $filter);
	}
	
	function genre($type, $page, $filter)
	{
		$this->view->home = $this->model->genre($type, $page, $filter);
	}
	
	
}