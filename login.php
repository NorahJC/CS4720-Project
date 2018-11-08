<?php
	include("database/tempDbClass.php");	//include db stuff
	session_start();						//initialize session
	
	$dbMan = new dbHandler();
	$display = " ";
	$userInfo = array();
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$found = $dbMan->login($_POST['uname'], $_POST['psw']);
		
		if($found == 1){	
			//Determine if teacher/parent
			$_SESSION['uname'] = $_POST['uname'];
			
			$userInfo = $dbMan->getTeacher($_POST['uname']);
			if($userInfo[0] != null){
				$_SESSION['usertype'] = "teacher";
			}
			else{
				$_SESSION['usertype'] = "parent";
			}
			
			header("location: man_dashboard.php");
			
			//MOVE TO THE MAIN PAGE
		}
		else{
			$display = "Incorrect username or password"; 
		}
		
	}
	


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>M.A.N. High School Portal</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script-->
    <!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script-->
    <link rel="stylesheet" href="stylesheet_login.css">
<style>
<!--stylesheet_login.css-->
</style>
</head>
<body>
	<div class="container-fluid-1">
		<img class = "login-page" src="logo_blue.jpg" alt = "man-hs-logo" border = "0">
		<hr class = "style-one">
	</div><div class="container-fluid-2">
		<h1 style = "text-align:center">Welcome to the M.A.N. High School Learning Portal</h1>
		<p class = "login-page">
			This portal provides access to the M.A.N. HS Learning Portal System. Use your M.A.N. ID to login.
			<br>
			<br>
		<b>	System Check:</b>
			<br>
			Please make sure your device meets the system requirements you log in. 
			<br>
			<br>
		<b>	Technical Assistance:</b>
			<br>
			Faculty/Staff: email service@man.marietta.edu or call 470-008-6099
			<br>
			Students: email studenthelpdesk@man.marietta.edu or call 470-078-0555
			<br>
			<br>
		<b>	Portal Training:</b>
			<br>
			M.A.N. Portal Training Resources 
			<br>
			M.A.N. FAQs
	    </p>
	</div>		
	<div class = "form_modal">
		<form class="modal-content animate" action="" method="post">
			<div class="imgcontainer">
			  <img src="logo_off.png" alt="Avatar" class="avatar">
			</div>
			
			<div class="container text-left">
			<p style="color:red"><b><?php echo $display."<br><br>"?></b></p>	
				<label><b>Username</b></label>
				<br>
				<input type="text" placeholder="Enter Username" name="uname" required>
				<br>
				<label><b>Password</b></label>
				<br>
				<input type="password" placeholder="Enter Password" name="psw" required>
				<br>	
				<button type="submit" value = "Go to MAN Dashboard">Login</button>
				<br>
				<br>
			</div>	
				<label> Forgot <span class="psw"> <a href="#">password?</a></span>					  
			</label>				
		</form>
	</div>
	<footer class="container-fluid" >
		<hr class = "style-one">
		<p class = "login-page">Copyright © 2018
		</p>
	</footer>	  
</body>
</html>
