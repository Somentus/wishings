<?php

session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	session_destroy();	
}

if(isset($_GET['url']) && !empty($_GET['url'])) {
	header('Location: '.$_GET['url']);
} else {
	header('Location: /');
}
exit;

?>
