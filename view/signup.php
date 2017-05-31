<html>
	<head>
	</head>
	
	<body>
		<?php echo $result; ?>
		<form action="" method="POST">
			<p>
				<label for="login">Username: </label>
				<input type="text" name="username" />
			</p>
			<p>
				<label for="password">Password: </label>
				<input type="password" name="password" />
			</p>
			<p>
				<label for="email">E-Mail: </label>
				<input type="text" name="email" />
			</p>
			<p>
				<input type="submit" name="Register" value="Register"/>
			</p>
		</form>
	</body>
</html>