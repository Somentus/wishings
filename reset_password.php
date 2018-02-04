<?php

session_start();

require_once($_SERVER["DOCUMENT_ROOT"].'/includes/DB.php');
$pdo = DB();
require_once($_SERVER["DOCUMENT_ROOT"].'/includes/codes.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/includes/reset_password.php');

$errors = [];
if(isset($_POST['resetPassword'])) {
	$errors = resetPassword($pdo);
}

?>

<!DOCTYPE HTML>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Reset Password</title>

	<link rel="stylesheet" href="/css/main.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<script src="/js/scripts.js" type="text/javascript" ></script>
</head>

<body>

	<div>
		<?php navbar($pdo); ?>
	</div>

	<br />
	<div class="container">
		<div id="errors">
			<?php
				foreach($errors as $error) {
					echo $error."<br />";
				}
			?>
		</div>

		<form action="reset_password.php" method="POST">
			<div class="form-group row">
				<label class="col-md-12 col-form-label">Forgot your password? Don't worry! Just enter your email address or username here:</label>
			</div>

			<div class="form-group row">
				<label for="inputEmail" class="col-md-2 col-form-label">Email address:</label>
				<div class="input-group col-md-4">
					<input type="email" class="form-control" id="inputEmail" placeholder="Email address" name="email">
					<div class="input-group-addon">@</div>
				</div>

				<label for="inputUsername" class="col-md-2 col-form-label">Username:</label>
				<div class="input-group col-md-4">
					<input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username">
					<div class="input-group-addon">$</div>
				</div>
			</div>

			<hr>

			<div class="form-group row">
				<div class="col-md-12 text-center">
					<button name="resetPassword" type="submit" class="btn btn-primary">Save</button>
				</div>
			</div>
		</form>
	</div>

</body>
</html>
