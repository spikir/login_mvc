<?php
	include_once("model/Model.php");
	
	class Controller {
		public $model;
			
		public function __construct() {
			$this->model = new Model();
		}
			
		public function invoke() {
			if (! isset($_GET['page'])) {
				if(isset($_GET['email']) && isset($_GET['hash'])) {
					$result = $this->model->getVerifyUser();
					include ('view/login.php');
				} else {
					Header ('Location: index.php?page=home');
				}
			} else {	
				$page = $_GET['page'];
				switch ($page) {
					case 'home';
						$products = $this->model->getArticles();
						include ('view/home.php');
						break;
					
					case 'login';
						$result = $this->model->getLogin();
						if ($result == 'login') {
							$user_email = $this->model->getUserEmail();
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
						
					case 'logout';
						$result = $this->model->getLogout();
						if($result == 'logout') {
							Header('Location: index.php?page=login');
						} 
						break;
		
					case 'updateprofile';
						$result = $this->model->getUpdateProfile();
						if($result == 'updated') {
							Header('Location: index.php?page=login');
						} 
						break;
						
					case 'vieworders';
						$orders = $this->model->getAllOrders();
						include('view/vieworders.php');
						break;
				}
			}
		}
	}
?>