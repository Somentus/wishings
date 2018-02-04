<?php

function location($pdo) {
    foreach ($_GET as $key => $value) {
        if($key == "id") {
            $id = $value;
        }
    }

    $location = basename($_SERVER['PHP_SELF']);
    $currentFile = explode('.', $location)[0];

    switch($currentFile) {
        case 'forum':
            $forum_id = $id;
            $forum = query($pdo, "SELECT * FROM forums WHERE id = :id", ['id' => $forum_id])[0];
            $forum_name = $forum['name'];
            $category_id = $forum['category_id'];
            $category = query($pdo, "SELECT * FROM categories WHERE id = :id", ['id' => $category_id])[0];
            $category_name = $category['name'];

            $objectNames = [$category_name, $forum_name];
            $urls = ['index.php', 'forum.php?id='.$forum_id];           
            break;
        case 'topic':
            $topic_id = $id;
            $topic = query($pdo, "SELECT * FROM topics WHERE id = :id", ['id' => $topic_id])[0];
            $topic_title = $topic['title'];
            $forum_id = $topic['forum_id'];
            $forum = query($pdo, "SELECT * FROM forums WHERE id = :id", ['id' => $forum_id])[0];
            $forum_name = $forum['name'];
            $category_id = $forum['category_id'];
            $category = query($pdo, "SELECT * FROM categories WHERE id = :id", ['id' => $category_id])[0];
            $category_name = $category['name'];

            $objectNames = [$category_name, $forum_name, $topic_title];
            $urls = ['index.php', 'forum.php?id='.$forum_id, 'topic.php?id='.$topic_id];
            break;
        default:
            $currentFileCapitalised = ucfirst($currentFile);
            $objectNames = [$currentFileCapitalised];
            $urls = [$location];
            break;
    }

    $return = [$objectNames, $urls];
    return $return;
}

function navbar($pdo) {
    $location = location($pdo);
    $currentLocation = $location[0];
    $currentLocationFiles = $location[1];
    $locationSteps = count($currentLocation);

    echo '
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="/index.php">Forum</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

            ';
    if(isLoggedIn(true)) {
    	echo '
    		<span class="navbar-text">|</span>
    		<li class="nav-item';
			if($currentLocation[0] == "Categories") {
				echo ' active';
			}
    		echo '" >
    			<a class="nav-link" href="/admin/categories.php">Categories</a>
			</li>
			<span class="navbar-text">|</span>
			<li class="nav-item';

			if($currentLocation[0] == "Forums") {
				echo ' active';
			}

			echo '" >
				<a class="nav-link" href="/admin/forums.php">Forums</a>
			</li>
		';
    } else {
	    for($i = 0; $i < $locationSteps; $i++) {
	        echo '
	            <span class="navbar-text">/</span>
	            <li class="nav-item';
	        if($i == $locationSteps - 1) {
	            echo ' active';
	        }
	        echo '"><a class="nav-link" href="'.$currentLocationFiles[$i].'">'.$currentLocation[$i].'
	                </a>
	            </li>
	        ';
	    } 
    }


    echo '</ul>';

    echo '<ul class="navbar-nav ml-auto">';

    // Check if user is logged in
    if(isLoggedIn(true)) {
    	// Visitor is an admin
        echo '
            <li class="nav-item">
                <a class="nav-link" href="/settings.php">Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout.php?url='.$_SERVER['REQUEST_URI'].'">Log Out</a>
            </li>
        ';
    } else if(isLoggedIn()) {
    	// Visitor is normal user
       	echo '
            <li class="nav-item">
                <a class="nav-link" href="/settings.php">Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout.php?url='.$_SERVER['REQUEST_URI'].'">Log Out</a>
            </li>
        ';
    } else {
    	// Visitor is not logged in
        echo '
            <li class="nav-item">
                <a id="navbarLogin" name="login" onclick="togglePortal(\'login\');" class="nav-link" >Login</a>
            </li>
            <li class="nav-item">
                <a id="navbarRegister" name="register" onclick="togglePortal(\'register\');" class="nav-link" >Register</a>
            </li>
            <li class="nav-item">
                <a id="navbarClose" name="closePortal" onclick="togglePortal(\'close\');" class="nav-link" >X</a>
            </li>';
    }

    echo '
            </ul>
        </div>
    </nav>
    ';

}

function portal($errors) {
	echo '
	<div id="errors">';
		foreach($errors as $error) {
			echo $error."<br />";
		}
  	echo '
  	</div>
	<div id="portal" class="portal" style="display:none;">
		<a id="reset_password" href="reset_password.php">Reset password</a>
		
		<form action="index.php" method="POST">
			
			<div id="username" style="display:none">
				<h3>Username:</h3>
				<input id="usernameField" type="text" name="username" />
			</div>

			<h3>Email:</h3>
			<input type="email" name="email" required/>
			<br />

			<h3>Password:</h3>
			<input type="password" name="password" required/>
			<br />
			<br />

			<input id="submit" type="submit" name="login" value="Login" class="btn btn-primary" />
			<br />
		</form>

	</div>
	<br />
	';
}

function login($pdo) {
	$errors = [];

	if(isset($_POST['login'])) {
	    $email = $_POST['email'];
	    $password = $_POST['password'];

	    $user = query($pdo, "SELECT * FROM users WHERE email = :email", ['email' => $email]);
	    if(count($user) == 1) {
	        // User found
	        $user = $user[0];

	        $passwordHash = $user['password'];

	        if(password_verify($password, $passwordHash)) {
	            $_SESSION['loggedin'] = true;
	            $_SESSION['id'] = $user['id'];
	        	if($user['is_admin'] == true) {
	        		$_SESSION['is_admin'] = true;
		            header('location:admin/categories.php');
	        	} else {        		
		            header('location:account.php');
	        	}
		        exit();

	        } else {
	            // TODO: After X tries, wait Y seconds before you can retry logging in to prevent spamming
	            $errors[] = "User not found or password incorrect.";
	        }
	    } else {
	    	$errors[] = "User not found or password incorrect.";
	    }
	}

	return $errors;
}

function register($pdo) {
	$errors = [];

	if(isset($_POST['register'])) {
		$unVerifiedUsername = $_POST['username'];
		$unVerifiedEmail = $_POST['email'];
		$unVerifiedPassword = $_POST['password'];

		// Check if username already exists
		$usernameAlreadyExists = query($pdo, "SELECT * FROM users WHERE username = :username", ['username' => $unVerifiedUsername]);
		if(count($usernameAlreadyExists) >= 1) {
			$errors[] = "Username already exists.";
		} else {
			$username = $unVerifiedUsername;
		}

		// Check if emailaddress is valid
		if(!filter_var($unVerifiedEmail, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Please enter a valid email address." ;
		} else {
			$emailAlreadyExists = query($pdo, "SELECT * FROM users WHERE email = :email", ['email' => $unVerifiedEmail]);
			if(count($emailAlreadyExists) >= 1) {
				$errors[] = "Email address already exists." ;
			} else {
				$email = $unVerifiedEmail;
			}
		}

		// Hash password
		if(strlen($unVerifiedPassword) > 72 ) {
			$errors[] = "Password is too long. Please enter a password of 72 characters or fewer.";
		} else {
			// TODO Check if password is strong enough
			$password = password_hash($unVerifiedPassword, PASSWORD_DEFAULT);
		}

		// TODO: Captcha

		// If no errors, register user
		if (empty($errors)) {
			$query = query($pdo, "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)", ['username' => $username, 'email' => $email, 'password' => $password]);

			$user_id = $pdo->lastInsertId();
			$uuid = generate_uuid();
	
			$query = query($pdo, "INSERT INTO activation_keys (user_id, uuid) VALUES (:user_id, :uuid)", ['user_id' => $user_id, 'uuid' => $uuid]);

			$to = $email;
			$subject = 'Registration complete!';
			$message = '
			<!DOCTYPE HTML>

			<html lang="en">
			<head>
			  <meta charset="utf-8">
			  <title>'.$subject.'</title>
			</head>
			<body>
			  <p>Welcome to the website!</p>
			  <p>Please click here to activate your account:</p>
			  <a href="http://localhost/activate.php?uuid='.$uuid.'&user_id='.$user_id.'">CLICKITY</a>
			</body>
			</html>
			';

			// To send HTML mail, the Content-type header must be set
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';

			// Additional headers
			$headers[] = "To: ".$username." <".$email.">";
			$headers[] = "From: Functional Forum <somentusforum@gmail.com>";

			// Mail it
			mail($to, $subject, $message, implode("\r\n", $headers));

			$errors[] = "User succesfully registered.";
		}
	}

	return $errors;
}

function isLoggedIn($admin = false) {
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['id'])) {
		if($admin == true) {
			// Verify if visitor is logged in as an admin
			if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
				// Visitor is logged in as an admin
				return 1;
			} else {
				return 0;
			}
		} else {
			// Visitor is logged in as a normal user
			return 1;
		}
	} else {
		return 0;
	}
}

function generate_uuid() {
	return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		mt_rand( 0, 0xffff ),
		mt_rand( 0, 0x0fff ) | 0x4000,
		mt_rand( 0, 0x3fff ) | 0x8000,
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	);
}
