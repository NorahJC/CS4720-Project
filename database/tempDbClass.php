<?php
error_reporting(0);
class dbHandler{
	//Temp PHP dbClass so we can just call this to do all the work for us
	/*
		Currently implemented implemented methods:
			addParent		
			addTeacher		
			addClass
			addParentToClass
			addHomework
			addEvent
			getEvents
			getHomework
			getParent			*needed for runtime
			getTeacher			*needed for runtime
			getUsersClasses		*needed for runtime
			login				*needed for runtime
			
		Need to be implemented methods: *all will be needed for runtime
			addWish
			getMessages
	*/
	
	private $servername = "localhost";
	private $username = "root";
	private $password = "";
	private $dbname = "phpmyadmin";
	private $conn; 
	
	//Class constructor
	function __construct(){
		//create connection to DB
		$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);		
		// Check connection, end if there is an error because all future will have issue
		if ($this->conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}	
	}
	
	//Class Destructor Close the connection to DB after finished
	function __destruct(){
		$this->conn->close();
	}
	
	
	//Administrator function to add Parent, accounts are made prior to runtime
	function addParent($ParentName, $Username, $Pass){		
		try{
			$stmt = $this->conn->prepare("INSERT INTO PARENT (`ParentName`, `Username`, `Pass`) VALUES (?, ?, ?);");
			//should update to enforce uniqueness

			$stmt->bind_param("sss", $ParentName, $Username, $Pass);	
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	//Administrator function to add Teacher, accounts are made prior to runtime
	function addTeacher($TeacherName, $Username, $Pass){
		try{
			$stmt = $this->conn->prepare("INSERT INTO TEACHER (`TeacherName`, `Username`, `Pass`) VALUES (?, ?, ?);");
			

			$stmt->bind_param("sss", $TeacherName, $Username, $Pass);	
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	//Administrator function to add Class, classes are added prior to runtime. Requires a teacherUsername for $username
	function addClass($ClassName, $ClassTime, $Classroom, $UserName, $ClassDescription){
		try{
			//Convert $username into a teacherID
			$TeacherID = $this->getTeacher($UserName); //returns array with 3 = ID
			$stmt = $this->conn->prepare("INSERT INTO CLASS (`ClassName`, `ClassTime`, `TeacherID`, `Classroom`, `Description` ) VALUES (?, ?, ?, ?, ?);");
			

			$stmt->bind_param("ssiss", $ClassName, $ClassTime, $TeacherID[3], $Classroom, $ClassDescription);	
			$stmt->execute();
			echo "Class added successfully";
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	//Administrator function to add Parent to a class, completed prior to runtime
	function addParentToClass($ParentUsername, $ClassID){
		try{
			//Convert $username into a teacherID
			$ParentID = $this->getParent($ParentUsername); //returns array with 3 = ID
			if($ParentID[3] != NULL){				
				$stmt = $this->conn->prepare("INSERT INTO PARENT_CLASS (`ClassID`, `ParentID`) VALUES (?, ?);");
				

				$stmt->bind_param("ii", $ClassID, $ParentID[3]);	
				$stmt->execute();
				echo "Parent added to class successfully";	
			}
			
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}	
	}
	
	function addHomework($ClassID, $HomeworkName, $DueDate, $Description){
		try{			
			$stmt = $this->conn->prepare("INSERT INTO HOMEWORK (`ClassID`,`description`, `isHistorical`, `dueDate`, `HomeworkName`) VALUES (?, ?, ?, ?, ?);");
			$isHistorical = "False";
			
			$stmt->bind_param("issss", $ClassID, $Description, $isHistorical, $DueDate, $HomeworkName);	
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}	
	}
	
	function addActivity($ClassID, $ActivityName, $ActivityDate, $Description){
		try{			
			$stmt = $this->conn->prepare("INSERT INTO ACTIVITIES (`ClassID`,`ActivityName`, `Description`, `ActivityDate`) VALUES (?, ?, ?, ?);");
			$stmt->bind_param("isss", $ClassID, $ActivityName, $Description, $ActivityDate);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}	
	}

	//Looks up parent based on username, returns array with :
	//0 = ParentName //1 = Username	//2 = Password	//3 = ID
	function getParent($Username){
		try{
			if(is_int($Username)){	//if given a teacherID, use that for SQL
				$stmt = $this->conn->prepare("SELECT * FROM PARENT WHERE ParentID=?;");	
				$stmt->bind_param("i", $Username);	
			}
			else{
				$stmt = $this->conn->prepare("SELECT * FROM PARENT WHERE Username=?;");	
				$stmt->bind_param("s", $Username);	
			}
			$stmt->execute();
			
			$stmt->bind_result($ParentID, $ParentName, $Username, $Pass);
			$result = array();
			$stmt->fetch();
			
			$result[0] = $ParentName;
			$result[1] = $Username;
			$result[2] = $Pass;
			$result[3] = $ParentID;
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

	//Looks up teacher based on username, returns array with :
	//0 = TeacherName //1 = Username	//2 = Password	//3 = ID
	function getTeacher($Username){
	
		try{
			$stmt;
			if(is_int($Username)){	//if given a teacherID, use that for SQL
				$stmt = $this->conn->prepare("SELECT * FROM TEACHER WHERE TeacherID=?;");	
				$stmt->bind_param("i", $Username);	
			}
			else{
				$stmt = $this->conn->prepare("SELECT * FROM TEACHER WHERE Username=?;");	
				$stmt->bind_param("s", $Username);	
			}
			

			
			$stmt->execute();
			
			$stmt->bind_result($TeacherID, $TeacherName, $Username, $Pass);
			$result = array();
			$stmt->fetch();
			
			$result[0] = $TeacherName;
			$result[1] = $Username;
			$result[2] = $Pass;
			$result[3] = $TeacherID;
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	//Looks up ClassInfo based on ClassID, returns array with :
	//0 = ClassID // 1 = ClassName // 2 = TeacherID // 3 = ClassRoom
	//4 = Description // 5 = ClassTime
	function getClassInfo($classID){
		try{
			$stmt = $this->conn->prepare("SELECT * FROM CLASS WHERE ClassID=?;");
			

			$stmt->bind_param("i", $classID);	
			$stmt->execute();
			
			$stmt->bind_result($ClassID, $ClassName, $TeacherID, $ClassRoom, $Description, $ClassTime);
			$result = array();
			$stmt->fetch();
			
			$result[0] = $ClassID;
			$result[1] = $ClassName;
			$result[2] = $TeacherID;
			$result[3] = $ClassRoom;
			$result[4] = $Description;
			$result[5] = $ClassTime;
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

		//Looks up ClassInfo based on ClassID, returns array with :
	//0 = ClassID // 1 = ClassName // 2 = TeacherID // 3 = ClassRoom
	//4 = Description // 5 = ClassTime
	function getHomeworkInfo($HomeworkID){
		try{
			$stmt = $this->conn->prepare("SELECT * FROM Homework WHERE HomeworkID=?;");
			

			$stmt->bind_param("i", $HomeworkID);	
			$stmt->execute();
			
			$stmt->bind_result($HomeworkID, $ClassID, $Description, $isHistorical, $DueDate, $HomeworkName);
			$result = array();
			$stmt->fetch();
			
			$result[0] = $HomeworkID;
			$result[1] = $HomeworkName;
			$result[2] = $Description;
			$result[3] = $DueDate;
			$result[4] = $ClassID;
			$result[5] = $isHistorical;
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

		function getActivityInfo($ActivityID){
		try{
			$stmt = $this->conn->prepare("SELECT * FROM activities WHERE ActivityID=?;");
			

			$stmt->bind_param("i", $ActivityID);	
			$stmt->execute();
			
			$stmt->bind_result($ActivityID, $ClassID, $ActivityName, $Description, $ActivityDate);
			$result = array();
			$stmt->fetch();
			
			$result[0] = $ActivityID;
			$result[1] = $ClassID;
			$result[2] = $ActivityName;
			$result[3] = $ActivityDate;
			$result[4] = $Description;
			return $result;
			
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	
	function getClassActivities($ClassID){
		$result = array();
		$listCount = 0;
		
		try{
			$stmt = $this->conn->prepare("select ActivityID	from class, activities where class.classID = activities.classID and class.ClassID = ? order by ActivityDate asc;");
			

			$stmt->bind_param("i", $class);	
			$class = $ClassID;
			
			$stmt->execute();
						
			$stmt->bind_result($ActivityID);
			
			while ($stmt->fetch()) {
				$result[$listCount] = $ActivityID;
				$listCount++;
			}
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	function getClassHomework($ClassID){
		$result = array();
		$listCount = 0;
		
		try{
			$stmt = $this->conn->prepare("select HomeworkID	from class, homework where class.classID = homework.classID and class.ClassID = ? order by dueDate asc;");
			

			$stmt->bind_param("i", $class);	
			$class = $ClassID;
			
			$stmt->execute();
						
			$stmt->bind_result($HomeworkID);
			
			while ($stmt->fetch()) {
				$result[$listCount] = $HomeworkID;
				$listCount++;
			}
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	//Looks up classes based on the username and returns an array of class ID's 
	//if given a username in the DB
	//Returns an array of all classes that the username is associated with
	function getUsersClasses($Username){
		$userInfo = $this->getParent($Username);
		$classList = array();
		$listCount = 0;
		
		if($userInfo[3] != NULL){		//is a parent 
			try{
				$stmt = $this->conn->prepare("SELECT ClassID FROM PARENT_CLASS WHERE ParentID=?;");
				
				$stmt->bind_param("i", $Class);	
				$Class = $userInfo[3];
				
				$stmt->execute();
				
				
				$stmt->bind_result($Class);
				while ($stmt->fetch()) {
					$classList[$listCount] = $Class;
					$listCount++;
				}
			}
			catch(PDOException $e)
			{
				echo "Error: " . $e->getMessage();
			}	
		}
		else{	//If a parent, cant be a teacher (according to our definitions)
			$userInfo = $this->getTeacher($Username);	//is a teacher
			if($userInfo[3] != NULL){					//Check if actually a teacher
				try{
					$stmt = $this->conn->prepare("select ClassID from class, teacher where class.TeacherID = teacher.TeacherID and class.TeacherID = ?;
");

					$stmt->bind_param("i", $Class);	
					$Class = $userInfo[3];

					$stmt->execute();


					$stmt->bind_result($Class);
					while ($stmt->fetch()) {
						$classList[$listCount] = $Class;
						$listCount++;
					}
				}
				catch(PDOException $e)
				{
					echo "Error: " . $e->getMessage();
				}	
			
			
			
			
			
			}
		}
		
		//return array of classList if has info or -1 if invalid user
		if(count($classList) > 0){
			return $classList;
		}
		else
			return -1;
	}
	
	function login ($username, $password){
		$valid = 0;
		
		try{
			$stmt = $this->conn->prepare("SELECT ParentID FROM PARENT WHERE Username=? and Pass=?;");	
			$stmt->bind_param("ss", $username, $password);	
			$stmt->execute();
			
			$stmt->bind_result($ParentID);
			$stmt->fetch();
			
			if(count($ParentID) == 1){
				$valid = 1;
				return $valid;
			}
			//else we check the teacher
			$stmt = $this->conn->prepare("SELECT TeacherID FROM TEACHER WHERE Username=? and Pass=?;");	
			$stmt->bind_param("ss", $username, $password);	
			$stmt->execute();
			
			$stmt->bind_result($TeacherID);
			$stmt->fetch();
			
			if($TeacherID!=null){
				$valid = 1;
			}
			return $valid;
			
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	function addMessage($ClassID, $TeacherID, $ParentID, $Content){
	    if(strlen(trim($Content)) == 0)
	    {
	        return;
	    }
	    $time = null; //stores default current time

	    try{
			$stmt = $this->conn->prepare("INSERT INTO messages (`ClassID`, `TeacherID`, `ParentID`, `TimeSent`, `Content`) VALUES (?, ?, ?, ?, ?);");
			$stmt->bind_param("iiiss", $ClassID, $TeacherID, $ParentID, $time, $Content);	
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	function getAllMessages($ClassID, $TeacherID, $ParentID){
		try{
			$stmt = $this->conn->prepare("SELECT * FROM messages WHERE ClassID=? and TeacherID=? and ParentID=?;");
			

			$stmt->bind_param("iii", $ClassID, $TeacherID, $ParentID);	
			$stmt->execute();
			
			$stmt->bind_result($ClassID, $TeacherID, $ParentID, $time, $Content);
			$result = array();
			$count = 0;
			while($stmt->fetch())
			{
			    $result[$count++] = $Content;
			}
			
			/*$result[0] = $ClassID;
			$result[1] = $TeacherID;
			$result[2] = $ParentID;
			$result[3] = $time;
			$result[4] = $Content;*/
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	function getClassWishes($ClassID){
		$result = array();
		$listCount = 0;
		
		try{
			$stmt = $this->conn->prepare("select WishID	from class, wish where class.classID = wish.classID and class.ClassID = ?;");
			

			$stmt->bind_param("i", $class);	
			$class = $ClassID;
			
			$stmt->execute();
						
			$stmt->bind_result($WishID);
			
			while ($stmt->fetch()) {
				$result[$listCount] = $WishID;
				$listCount++;
			}
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

	function addWish($ClassID, $WishName, $Description){
		try{			
			$stmt = $this->conn->prepare("INSERT INTO WISH (`ClassID`,`WishName`, `Description`) VALUES (?, ?, ?);");
			$stmt->bind_param("iss", $ClassID, $WishName, $Description);	
			$stmt->execute();
			
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}	
	}




	function addParentToWish($ParentUsername, $WishID){
		try{
			$ParentID = $this->getParent($ParentUsername); //returns array with 3 = ID
			if($ParentID[3] != NULL){				
				$stmt = $this->conn->prepare("INSERT INTO PARENT_WISH (`ParentID`, `WishID`) VALUES (?, ?);");
				
				$stmt->bind_param("ii",$ParentID[3], $WishID);	
				$stmt->execute();
			}
			
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}	
	}

	function getWishInfo($WishID){
		try{
			$stmt = $this->conn->prepare("SELECT * FROM wish WHERE WishID=?;");
			

			$stmt->bind_param("i", $WishID);	
			$stmt->execute();
			
			$stmt->bind_result($WishID, $ClassID, $WishName, $Description);
			$result = array();
			$stmt->fetch();
			
			$result[0] = $WishID;
			$result[1] = $ClassID;
			$result[2] = $WishName;
			$result[3] = $Description;;
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	function getWishSubscribers($WishID){
		$result = array();
		$listCount = 0;
		
		try{
			$stmt = $this->conn->prepare("SELECT ParentID FROM parent_wish WHERE WishID=?;");

			$stmt->bind_param("i", $WishID);	
			$stmt->execute();
			
			$stmt->bind_result($ParentID);
			
			while ($stmt->fetch()) {
				$result[$listCount] = $ParentID;
				$listCount++;
			}
			return $result;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
}
?>