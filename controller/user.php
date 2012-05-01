<?php

/**
 * Index
 *
 * Controller for User
 * 
 * @author Maail
 */
class User extends Controller
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function profile($username)
	{
		$this->view->userinfo = $this->model->profile($username);
		$this->view->pagetitle = $username;
		$this->view->render('profile');
	}
	
	function all($page)
	{
		$this->model->all($page);
	}
	
	function logout()
	{
		$this->model->logout();
	}
}