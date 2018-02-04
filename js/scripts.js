function toggle(element, show) {
	document.getElementById(element).style.display = show;
}

function togglePortal(to) {
	switch(to) {
		case 'login':
			var portal = document.getElementById("portal").style.display;
			if(portal == "block") {
				// Portal is open, unclear if it's currently login or register
				var submit = document.getElementById("submit").getAttribute("name");
				if(submit == "login"){
					// Login is currently open, close login
					document.getElementById("errors").innerHTML = "";
					document.getElementById("navbarLogin").setAttribute("class", "nav-link");
					document.getElementById("navbarRegister").setAttribute("class", "nav-link");
					toggle("portal", "none");
				} else {
					// Register is currently open, switch to login
					document.getElementById("submit").setAttribute("name", "login");
					document.getElementById("submit").setAttribute("value", "Login");
					document.getElementById("navbarLogin").setAttribute("class", "nav-link active");
					document.getElementById("navbarRegister").setAttribute("class", "nav-link");
					document.getElementById("usernameField").removeAttribute("required");
					document.getElementById("errors").innerHTML = "";
					toggle("username", "none");
					toggle("reset_password", "block");
				}
			} else {
				// Portal is closed, open it
				toggle("portal", "block");
				document.getElementById("submit").setAttribute("name", "login");
				document.getElementById("submit").setAttribute("value", "Login");
				document.getElementById("navbarLogin").setAttribute("class", "nav-link active");
				document.getElementById("navbarRegister").setAttribute("class", "nav-link");
				document.getElementById("usernameField").removeAttribute("required");
				document.getElementById("errors").innerHTML = "";
				toggle("username", "none");
				toggle("reset_password", "block");
			}

			break;
		case 'register':
			var portal = document.getElementById("portal").style.display;
			if(portal == "block") {
				// Portal is open, unclear if it's currently Login or Register
				var submit = document.getElementById("submit").getAttribute("name");
				if(submit == "register"){
					// Register is currently open, close Register
					document.getElementById("errors").innerHTML = "";
					document.getElementById("navbarLogin").setAttribute("class", "nav-link");
					document.getElementById("navbarRegister").setAttribute("class", "nav-link");
					toggle("portal", "none");
				} else {
					// Login is currently open, switch to Register
					document.getElementById("submit").setAttribute("name", "register");
					document.getElementById("submit").setAttribute("value", "Register");
					document.getElementById("navbarLogin").setAttribute("class", "nav-link");
					document.getElementById("navbarRegister").setAttribute("class", "nav-link active");
					document.getElementById("usernameField").setAttribute("required", true);
					document.getElementById("errors").innerHTML = "";
					toggle("username", "block");
					toggle("reset_password", "none");
				}
			} else {
				// Portal is closed, open it
				toggle("portal", "block");
				document.getElementById("submit").setAttribute("name", "register");
				document.getElementById("submit").setAttribute("value", "Register");
				document.getElementById("navbarLogin").setAttribute("class", "nav-link");
				document.getElementById("navbarRegister").setAttribute("class", "nav-link active");
				document.getElementById("usernameField").setAttribute("required", true);
				document.getElementById("errors").innerHTML = "";
				toggle("username", "block");
				toggle("reset_password", "none");
			}
			break;
		case 'close':
			document.getElementById("errors").innerHTML = "";
			document.getElementById("navbarLogin").setAttribute("class", "nav-link");
			document.getElementById("navbarRegister").setAttribute("class", "nav-link");
			toggle("portal", "none");
			break;
		default:
			break;
	}
}
