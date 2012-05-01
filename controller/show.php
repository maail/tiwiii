<?php

/**
 * Show
 *
 * Controller for Show
 * 
 * @author Maail
 */
class Show extends Controller
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function index()
	{
		
		$this->view->render('show');
		
	}
	
	function view($id)
	{
		$this->view->showinfo = $this->model->view($id);
		$this->view->pagetitle = $this->view->showinfo['show_name'];
		$this->view->pagedesc = $this->view->showinfo['show_desc'];
		$this->view->render('show');
	}
	
	
	
	function episode($epid)
	{
		$this->model->episode($epid);
	}
	
	function update($showid)
	{
		$this->model->update($showid);
	}
	
	function updateshows()
	{
		$this->model->updateshows();
	}
	
	function search()
	{
		$this->view->showinfo = $this->model->search();
		$this->view->pagetitle = "Add Show";
		$this->view->render('show');
	}
	
	function query()
	{
		$this->model->query();
	}
	
	function livesearch()
	{
		$this->model->livesearch();
	}
	
	function add($showid)
	{
		$this->view->showinfo = $this->model->add($showid);
		$this->view->pagetitle = "Add Show";
		$this->view->render('show');
	}
	
	function fave($showid)
	{
		$this->model->fave($showid);
	}
	
	function unfave($showid)
	{
		$this->model->unfave($showid);
	}
	
	function watch($showid)
	{
		$this->model->watch($showid);
	}
	
	function unwatch($showid)
	{
		$this->model->unwatch($showid);
	}
	
	function vote($showid)
	{
		$this->model->vote($showid);
	}
	
	function unvote($showid)
	{
		$this->model->unvote($showid);
	}
	
	function count_vote($showid)
	{
		$this->model->count_vote($showid);
	}
	
	function user_options($showid)
	{
		$this->model->user_options($showid);
	}
	
	function checkin($epid)
	{
		$this->model->checkin($epid);
	}
	
	function update_by($type)
	{
		$this->model->update_by($type);
	}
	
	function show_options($showid, $option)
	{
		$this->model->show_options($showid, $option);
	}
	
	function update_misc()
	{
		$this->model->update_misc();
	}
	
	function activity($show, $page)
	{
		$this->model->activity($show, $page);
	}
	
	function save()
	{
		$this->model->save();
	}
}