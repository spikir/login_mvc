<?php
	include_once("dbconnect.php");
	
	class Model {
		
		function __construct() {
			$this->connection = new dbconnect();
			session_start();
		}
		
		public function getLogin() {
			$conn = $this->connection->connect();
			
			if(!isset($_SESSION['user_id'])) {
				if(isset($_POST['username']) && isset($_POST['password'])) {
					$username = trim(mysqli_escape_string($conn,$_POST['username']));
					$password = trim(mysqli_escape_string($conn,$_POST['password']));
				
					$stmt = mysqli_prepare($conn, "SELECT user_id, user_password FROM users WHERE user_username = ?");
					
					if($stmt) {
						mysqli_stmt_bind_param($stmt, 's', $username);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_bind_result($stmt, $userid, $userpassword);
						mysqli_stmt_fetch($stmt);
						mysqli_stmt_close($stmt);
						if (!password_verify($password, $userpassword)) {
							return 'Invalid user';
						} else {
							$_SESSION['user_id'] = $userid;
							return 'login';
						}		
					}
				}
			} else {
				return 'login';
			}
		}
		
		private function getCheckUser($username, $email) {
			$conn = $this->connection->connect();
			
			$stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE user_username = ? or user_email = ?");
					
			if($stmt) {
				mysqli_stmt_bind_param($stmt, 'ss', $username, $email);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $userid);
				mysqli_stmt_fetch($stmt);
				mysqli_stmt_close($stmt);
			}
			return $userid;
		}
		
		public function getUserEmail() {
			$conn = $this->connection->connect();
			
			$user_id = $_SESSION['user_id'];
			$stmt = mysqli_prepare($conn, "SELECT user_email FROM users WHERE user_id = ?");
			
			if($stmt) {
				mysqli_stmt_bind_param($stmt, 'i', $user_id);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $user_email);
				mysqli_stmt_fetch($stmt);
				mysqli_stmt_close($stmt);
			}
			return $user_email;
		}
		
		private function getSendActivEmail($email, $hash) {
			$to = $email;
			$subject = 'Signup Verification';
			$message = '
			Thanks for signing up!
			Your account has been created, you can login with your credentials after you have activated your account by pressing the url below.

			Please click this link to activate your account:
			http://localhost/cart_mvc/model/verify.php?email='.$email.'&hash='.$hash.'
			
			';
			
			$headers = 'From: noreply@localhost';
			mail($to, $subject, $message, $header);
			
			return true;
		}
		
		public function getRegister() {
			$conn = $this->connection->connect();
			
			if(!isset($_SESSION['user_id'])) {
				if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
					if ($_POST['username']!= '' && $_POST['password']!='' && $_POST['email']!='') {
						$username = trim(mysqli_escape_string($conn,$_POST['username']));
						$password = trim(mysqli_escape_string($conn,$_POST['password']));
						$email = trim(mysqli_escape_string($conn,$_POST['email']));
						
						if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
							return 'Invalid email';
						} else {
							$password_hash = password_hash($password, PASSWORD_DEFAULT);
							$hash = md5(rand(0,1000));
							
							$userid = $this->getCheckUser($username, $email);
							
							if (!$userid) {
								$stmt = mysqli_prepare($conn, "INSERT INTO users (user_username, user_password, user_email, user_hash) VALUES (?, ?, ?, ?)");
								mysqli_stmt_bind_param($stmt, 'ssss', $username, $password_hash, $email, $hash);
								mysqli_stmt_execute($stmt);
								$email_sent = $this->getSendActivEmail($email, $hash);
								mysqli_stmt_close($stmt);
								return 'register';
							} else {
								return 'User is registered';
							}
						}
					} else {
						return 'Fill out the form';
					}
				}
			} else {
				return 'register';
			}
		}
		
		public function getLogout() {
			unset($_SESSION['user_id']);
			return 'logout';
		}
		
		public function getUpdateProfile() {
			$conn = $this->connection->connect();
			
			$user_email = trim(mysqli_escape_string($conn,$_POST['email']));
			$user_id = $_SESSION['user_id'];
			
			$stmt = mysqli_prepare($conn, "UPDATE users SET user_email = ? WHERE user_id = ? ");
			
			if($stmt) {
				mysqli_stmt_bind_param($stmt, 'si', $user_email, $user_id);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
				return 'updated';
			}
		}
		
		public function getVerifyUser() {
			$conn = $this->connection->connect();
			if(isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
				$email = mysql_escape_string($_GET['email']);
				$hash = mysql_escape_string($_GET['hash']);
				
				$stmt = mysqli_prepare($conn, "SELECT user_email, user_hash, user_active FROM users WHERE user_email = ? AND user_hash = ?");
					
				if($stmt) {
					mysqli_stmt_bind_param($stmt, 'ss', $email, $hash);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt, $user_email, $user_hash, $user_active);
					mysqli_stmt_fetch($stmt);
					mysqli_stmt_close($stmt);
					if($user_hash) {
						echo $user_hash;
						$stmt = mysqli_prepare($conn, "UPDATE users SET user_active = 1 WHERE user_email = ? AND user_hash = ? ");
						if($stmt) {
							mysqli_stmt_bind_param($stmt, 'ss', $email, $hash);
							mysqli_stmt_execute($stmt);
							return 'activated';
						}
					} else {
						return 'invalid';
					}
				}
			} else {
				return 'invalid';
			}
		}
		
		public function getArticles() {
			$conn = $this->connection->connect();
			
			$stmt = mysqli_prepare($conn, "SELECT product_id, product_image, product_title, product_desc, product_price FROM products ORDER BY product_id ASC");
	
			if($stmt) {
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
				mysqli_stmt_close($stmt);
				return $products;
			} else {
				return array();
			}
		}
	}
?>