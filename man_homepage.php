<?php
	session_start();
	include("database/tempDbClass.php");	//include db stuff
	$dbMan = new dbHandler();
	
	$classInfo = array();
	if(isset($_SESSION['currentClassID'])){				//set the current classID or maintain it
		$classInfo = $dbMan->getClassInfo($_SESSION['currentClassID']);
	}
	else{
		$_SESSION['currentClassID'] = $_POST["classID"];	
		$classInfo = $dbMan->getClassInfo($_POST["classID"]);
	}
	
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if($_POST["action"] == "addHomework"){
			$dbMan->addHomework($_SESSION['currentClassID'], $_POST['homeworkTitle'], $_POST['homeworkDueDate'], $_POST['homeworkDescription']);			
		}
		else if($_POST["action"] == "addActivity"){
			$dbMan->addActivity($_SESSION['currentClassID'], $_POST['ActivityTitle'], $_POST['ActivityDate'], $_POST['ActivityDescription']);			
		}
	}
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<title>M.A.N. High School Portal HomePage</title>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="stylesheet_login.css">
  <link rel = "stylesheet" href = "stylesheet_homepage.css">
   <style>
	  .btn-link{
	  border:none;
	  outline:none;
	  background:none;
	  cursor:pointer;
	  color:#0000EE;
	  padding:0;
	  font-family:inherit;
	  font-size:inherit;
	  white-space: nowrap;
	}
	.btn-link:active{
		color:#FF0000;
	}
  
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
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
	
				
      <ul class="nav navbar-nav">
	  	<li>
				<form class="dashboard" method="post">
				<input type="hidden" name = "tab" value="userInfo">
				<button style = "padding: 30px" type="submit" class="btn-link login-page">User Info</button></form>
		</li>
        <li>
				<form class="dashboard" method="post">
				<input type="hidden" name = "tab" value="homework">
				<button style = "padding: 30px" type="submit" class="btn-link login-page">Homework</button></form>
		</li>
        <li>
				<form class="dashboard" method="post">
				<input type="hidden" name = "tab" value="todo">
				<button style = "padding: 30px" type="submit" class="btn-link login-page">To-Do List</button></form>
        <li>
				<form class="dashboard" method="post">
				<input type="hidden" name = "tab" value="wishlist">
				<button style = "padding: 30px" type="submit" class="btn-link login-page">Wish List</button></form>
		</li>
		<li>
				<form class="dashboard" method="post">
				<input type="hidden" name = "tab" value="activities">
				<button style = "padding: 30px" type="submit" class="btn-link login-page">Activities</button></form>
		<li>
				<form class="dashboard" method="post">
				<input type="hidden" name = "tab" value="messageboard">
				<button style = "padding: 30px" type="submit" class="btn-link login-page">Message Board</button></form>
		</li>
		<li >
				<form class="dashboard" action="man_dashboard.php" method="post">
				<button style = "padding: 30px" type="submit" class="btn-link login-page">Dashboard</button></form>
		</li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.html" style = "padding: 30px"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="col-sm-8 text-left"> 
      <h1>Welcome to <?php echo $classInfo[1];?></h1>
      <p class = "homepage">
	  <?php echo $classInfo[4]; ?>
	<br>
	<br>
	<b> this is just an example of what the page can look like...</b>
	  </p>
	  <hr>
<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if($_POST['tab'] == "homework"){
			echo "<h3>Homework<h3><hr>";
			$homeworkIDs = $dbMan->getClassHomework($classInfo[0]);//function getClassHomework($ClassID){
			foreach($homeworkIDs as $homework){
				$homeworkInfo = $dbMan->getHomeworkInfo($homework);
				echo '<b>'.$homeworkInfo[1].'</b> Due - '.$homeworkInfo[3].'<br>';
				echo '<p>'.$homeworkInfo[2].'</p>';
				echo "<br><br>";
			}	
			
			
			if($_SESSION['usertype'] != 'parent'){
				echo "<h4>Add Homework</h4>";
				echo '<form class="dashboard" method="post">';
				echo '<input type="hidden" name = "tab" value="homework">';
				echo '<input type="hidden" name = "action" value="addHomework">';
				echo '<label><b>Homework Title</b></label><br>';
				echo '<input type="text" placeholder="Enter Homework Name" name="homeworkTitle" required><br>';
				echo '<label><b>Description</b></label><br>';
				echo '<input type="text" placeholder="Enter Homework Description" name="homeworkDescription" required><br>';
				echo '<label><b>Due Date</b></label><br>';
				echo '<input type="text" placeholder="Enter Due Date" name="homeworkDueDate" required><br>';
				echo '<button style = "padding: 30px" type="submit">Add Homework</button></form><br>';
			}
		}
		else if($_POST['tab'] == "todo"){
			echo "<h3>To-Do List<h3><hr>";
		}	
		else if($_POST['tab'] == "wishlist"){
			echo "<h3>Wish List<h3><hr>";
		}		
		else if($_POST['tab'] == "activities"){
			echo "<h3>Activities<h3><hr>";
			
			$ActivityIDs = $dbMan->getClassActivities($classInfo[0]);//function getClassHomework($ClassID){
			foreach($ActivityIDs as $Activity){
				$ActivityInfo = $dbMan->getActivityInfo($Activity);
				echo '<b>'.$ActivityInfo[2].'</b> - '.$ActivityInfo[3].'<br>';
				echo '<p>'.$ActivityInfo[4].'</p>';
				echo "<br><br>";
			}

			if($_SESSION['usertype'] != 'parent'){
				echo "<h4>Add New Activity</h4>";
				echo '<form class="dashboard" method="post">';
				echo '<input type="hidden" name = "tab" value="activities">';
				echo '<input type="hidden" name = "action" value="addActivity">';
				echo '<label><b>Activity Title</b></label><br>';
				echo '<input type="text" placeholder="Enter Activity Name" name="ActivityTitle" required><br>';
				echo '<label><b>Description</b></label><br>';
				echo '<input type="text" placeholder="Enter Activity Description" name="ActivityDescription" required><br>';
				echo '<label><b>Due Date</b></label><br>';
				echo '<input type="text" placeholder="Enter Activity Date" name="ActivityDate" required><br>';
				echo '<button style = "padding: 30px" type="submit">Add Activity</button></form><br>';
			}			
			
			
		}
		else if($_POST['tab'] == "messageboard"){
			echo "<h3>Message Board<h3><hr>";
		}
		else if($_POST['tab'] == "userInfo"){
			echo "<h3>User Information<h3><hr>";
			$userInfo = array();
			if($_SESSION['usertype'] != 'parent'){
				echo "<p>You are logged in as a Teacher</p>";
			}
			else{
				echo "<p>You are logged in as a Parent</p>";
			}
			echo "<p><b>Username:</b> ".$_SESSION['uname']."</p>";
			
		}
		else{
			echo "<h3>Please select a tab</h3><hr>";
		}
	}  
?>
</div>  

<footer class="container-fluid-1">
	<hr class = "style-one">
	<p class = "login-page">Copyright © 2018
	</p>
</footer>	


</body>
</html>
