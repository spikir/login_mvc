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
			}
			return $user_email;
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
							$hash = password_hash($password, PASSWORD_DEFAULT);
							
							$userid = $this->getCheckUser($username, $email);
							
							if (!$userid) {
								$stmt = mysqli_prepare($conn, "INSERT INTO users (user_username, user_password, user_email) VALUES (?, ?, ?)");
								mysqli_stmt_bind_param($stmt, 'sss', $username, $hash, $email);
								mysqli_stmt_execute($stmt);
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
				return 'updated';
			}
		}
	}
?>