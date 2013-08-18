<?php
class controller_Controller{
	
	protected $view;
	protected $param;
	
	public function __construct(){
		$this->view = View::getInstance();
		$this->param = FrontController::getRequestParams();
	}
	
	public function defaultAction() {
		
	}
}
?>