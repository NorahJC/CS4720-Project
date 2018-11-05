<?php
class dbHandler{
	//Temp PHP dbClass so we can just call this to do all the work for us
	/*
		Currently implemented implemented methods:
			addParent		
			addTeacher		
			addClass
			addParentToClass
			getParent			*needed for runtime
			getTeacher			*needed for runtime
			getUsersClasses		*needed for runtime
			
		Need to be implemented methods: *all will be needed for runtime
			addHomework
			addEvent
			getHomework
			getEvents
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
}
?>
