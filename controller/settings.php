<?php

/**
 * Settings
 *
 * Controller for User Settings
 * 
 * @author Maail
 */
class Settings extends Controller
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function index()
	{
		$this->view->pagetitle = "Settings";
		$this->view->render('settings');
	}
	
	function view($type)
	{
		$this->model->view($type);
	}
	
	function check($type, $typeval)
	{
		$this->model->check($type, $typeval);
	}
	
	function save($type)
	{
		$this->model->save($type);
	}
}