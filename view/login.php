<html>
	<head>
	</head>
	
	<body>
		<?php echo $result; ?>
		<form action="" method="POST">
			<p>
				<label>Username</label>
				<input type="text" id="username" value="" name="username" />
			</p>
			<p>
				<label>Password</label>
				<input type="password" id="password" name="password" />
			</p>
			<p>
				<button type="submit" name="submit">
					<span>Login</span>
				</button>
				<button type="reset">
					<span>Cancel</span>
				</button>
			</p>
		</form>
	</body>
</html>