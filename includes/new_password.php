<?php

function newPasswordForm($pdo, $uuid, $user_id) {
	$resetPassword = query($pdo, "SELECT * FROM reset_passwords WHERE user_id = :user_id AND uuid = :uuid", ['user_id' => $user_id, 'uuid' => $uuid]);
	if(count($resetPassword) == 1) {
		echo ' 
			<form action="new_password.php?uuid='.$uuid.'&user_id='.$user_id.'" method="POST">
				<div class="form-group row">
					<label for="inputPassword" class="col-md-2 col-form-label">Enter new password:</label>
					<div class="input-group col-md-4">
						<input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password">
						<div class="input-group-addon">$</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-md-12 text-center">
						<button name="newPassword" type="submit" class="btn btn-primary">Save</button>
					</div>
				</div>
			</form>
		';
	} else {
		header('Location: /');
	}
}

function newPassword($pdo) {
	$errors = [];

	if(isset($_POST['newPassword']) && isset($_GET['uuid']) && !empty($_GET['uuid']) && isset($_GET['user_id']) && !empty($_GET['user_id'])) {
		$user_id = $_GET['user_id'];
		$uuid = $_GET['uuid'];
		$verifyUUID = query($pdo, "SELECT * FROM reset_passwords WHERE user_id = :user_id AND uuid = :uuid", ['user_id' => $user_id, 'uuid' => $uuid]);
		if(count($verifyUUID) == 1) {
			$unVerifiedPassword = $_POST['password'];
			// Hash password
			if(strlen($unVerifiedPassword) > 72 ) {
				$errors[] = "Password is too long. Please enter a password of 72 characters or fewer.";
			} else {
				// TODO Check if password is strong enough
				$password = password_hash($unVerifiedPassword, PASSWORD_DEFAULT);
			}

			// TODO: Captcha

			// If no errors, update password
			if (empty($errors)) {
				query($pdo, "DELETE FROM reset_passwords WHERE user_id = :user_id limit 1", ['user_id' => $user_id]);
				query($pdo, "UPDATE users SET password = :password WHERE id = :id", ['password' => $password, 'id' => $user_id]);
				$errors[] = "Password succesfully changed. You can now log in with your new password.";

				// Logout user
				session_destroy();
			}
		} else {
			$errors[] = "Password reset has already been used. Please retry: <a href='http://localhost/reset_password.php'>here</a>.";
		}
	}

	return $errors;
}
