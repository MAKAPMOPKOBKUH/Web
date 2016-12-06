<?php

require_once( "views/View.php" );

class indexController extends Controller {

public $view;
	public function index(){
		$message = "APP";
		$this->setResponce($message);

		$this->view = new View();
		$this->view->generate('mainView.php');
	}
		
}