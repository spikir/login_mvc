<?php
	include_once("model/Model.php");
	
	class Controller {
		public $model;
			
		public function __construct() {
			$this->model = new Model();
		}
			
		public function invoke() {
			
			if (! isset($_GET['page'])) {
				Header ('Location: index.php?page=home');
			} else {	
				$page = $_GET['page'];
				switch ($page) {
					case 'home';
						include ('view/home.php');
						break;
					
					case 'login';
						$result = $this->model->getLogin();
						if ($result == 'login') {
							include ('view/Afterlogin.php');
						} else {
							include ('view/login.php');
						}
						break;
						
					case 'signup';
						$result = $this->model->getRegister();
						if($result == 'register') {
							Header('Location: index.php?page=login');
						} else {
							include ('view/signup.php');
						}
						break;
				}
			}
		}
	}
?>