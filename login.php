<html>
	<head>
		<title>Test</title>
	</head>
	<body>
		<form action="checkLogin.php" method="post">
			<?php
				require 'auth.php';
				
				if ($_GET['error']==Auth::notConnected) 
					echo 'Please log in before accessing this page';
				if ($_GET['error']==Auth::authFailed)
					echo 'Authentication failed';
			?>

			Login: <input type="text" name="user" /><br />
			Password: <input type"password" name="pass" /><br /> 
			<input type="submit" value="Log me in!" />
		</form>
	</body>
</html>
