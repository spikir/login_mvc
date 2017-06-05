<?php include ('header.php')?>
<div class="boxcontent">
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
			<input type="submit" name="Register" value="Sign Up"/>
		</p>
	</form>
</div>
<?php include ('footer.php')?>