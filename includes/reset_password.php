<?php

function resetPassword($pdo) {
	$errors = [];

	if(isset($_POST['resetPassword'])) {
		$email = $_POST['email'];
		$username = $_POST['username'];

		if(!empty($email)) {
			$emailUser = query($pdo, "SELECT * FROM users WHERE email = :email", ['email' => $email]);
			if(count($emailUser) == 1) {
				$user = $emailUser[0];
			} else {
				$errors[] = "No user found with that email address.";
			}
		}


		if(!empty($username)) {
			$usernameUser = query($pdo, "SELECT * FROM users WHERE username = :username", ['username' => $username]);
			if(count($usernameUser) == 1) {
				$user = $usernameUser[0];
			} else {
				$errors[] = "No user found with that username.";
			}
		}

		if(!empty($email) && !empty($username)) {
			if($emailUser['id'] != $usernameUser['id']) {
				$errors[] = "That username does not belong to that email address.";
			}
		}

		if(empty($errors)) {
			$uuid = generate_uuid();
			$user_id = $user['id'];

			// Delete possible previous reset_passwords
			$reset_password_already_exists = query($pdo, "SELECT * FROM reset_passwords WHERE user_id = :user_id", ['user_id' => $user_id]);
			if(count($reset_password_already_exists) == 1) {
				query($pdo, "UPDATE reset_passwords SET uuid = :uuid WHERE user_id = :user_id", ['user_id' => $user_id, 'uuid' => $uuid]);
			} else {
				query($pdo, "INSERT INTO reset_passwords (user_id, uuid) VALUES (:user_id, :uuid)", ['user_id' => $user_id, 'uuid' => $uuid]);
			}

			$to = $user['email'];
			$subject = 'Reset Password';
			$message = '
			<!DOCTYPE HTML>

			<html lang="en">
			<head>
			  <meta charset="utf-8">
			  <title>'.$subject.'</title>
			</head>
			<body>
			  <p>You have request your password to be reset.</p>
			  <p>Please click here to enter a new password:</p>
			  <a href="http://localhost/new_password.php?uuid='.$uuid.'&user_id='.$user_id.'">CLICKITY</a>
			</body>
			</html>
			';

			// To send HTML mail, the Content-type header must be set
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';

			// Additional headers
			$headers[] = "To: ".$user['username']." <".$to.">";
			$headers[] = "From: Functional Forum <somentusforum@gmail.com>";

			// Mail it
			mail($to, $subject, $message, implode("\r\n", $headers));

			$errors[] = "Please check your mail for further instructions on how to enter a new password.";
		}
	}

	return $errors;
}

?>
