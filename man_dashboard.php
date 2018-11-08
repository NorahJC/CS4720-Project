<?php
	session_start();
	include("database/tempDbClass.php");	//include db stuff
	$dbMan = new dbHandler();
	$userinfo = array();
	if($_SESSION['usertype'] == "parent"){
		$userinfo = $dbMan->getParent($_SESSION['uname']);
	}
	else{
		$userinfo = $dbMan->getTeacher($_SESSION['uname']);
	}
	$classList = $dbMan->getUsersClasses($userinfo[1]);
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>M.A.N. High School Portal Dashboard</title>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="stylesheet_login.css">
  <link rel = "stylesheet" href = "stylesheet_homepage.css">
  <style>
  </style>  
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#"><img src="logo_black.jpg" alt="homepage logo" class="homepage_logo" width = "90%" height = "80px" ></a>
	  <a href="#" style = "color: white; font-size:20px; padding: 30px">Welcome, <?php echo $userinfo[0]?></a>
	</div>
</nav>	
<div class = "container-fluid-1">
		<form class="dashboard" action="man_homepage.html">
		<?php 
			$count = 0;	//after 3 move to next row
			foreach($classList as $class){
				$info = $dbMan->getClassInfo($class);
				echo '<div class="col-sm-4 sidenav"><div class="well"><img src="logo_off.png" alt="Avatar" class="avatar">';
				echo '<a href = "man_homepage.html"><p class = "login-page"><b>'.$info[1].'</b></p></a>	</div></div>	';
			}		
		?>


		</form>
	</div>
	<footer class="container-fluid-1">
		<hr class = "style-one">
		<p class = "login-page">Copyright © 2018
		</p>
	</footer>	  
</body>
</html>
