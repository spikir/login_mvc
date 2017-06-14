<html>
	<head>
		<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
	</head>
	<body>
		<div class="wrapper">
			<div class="boxheader">
				Header
			</div>
			<div class="boxsidebar">
				<ul>
					<li><a href="index.php?page=home">Home</a></li>
					<?php 
					if (isset($_SESSION['user_id'])){
						echo '<li><a href="index.php?page=vieworders">View Orders</a></li>';
						echo '<li><a href="index.php?page=logout">Logout</a></li>';
					} else {
						echo '<li><a href="index.php?page=login">Login</a></li>';
						echo '<li><a href="index.php?page=signup">Sign up</a></li>';
					}					
					?>
				</ul>
			</div>