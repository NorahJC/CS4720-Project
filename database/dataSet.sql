/*Create Data*/
/*
This sql script will create data and insert it into respective tables so that anyone using 
the site through xampp will have the same data to compare results with
*/

/*Create Parents*/
INSERT INTO PARENT
(parentName, Username, Pass)
VALUES
("Shruthi Rajuri", "srajuri", "password123"),
("Alex Drennan", "adrennan", "password123"),
("Matt Hammond", "mhammond", "password123"),
("Norah Jean Charles", "njcharles", "password123"),
("Cersei Lannister", "clannister", "password123"),
("Tywin Lannister", "tlannister", "password123"),
("Donald Trump", "dtrump", "password123"),
("Bernie Sanders", "bsanders", "password123"),
("Justin Trudeau", "jtrudeau", "password123"),
("George R.R. Martin", "gmartin", "password123"),
("Jimmy Carter", "jcarter", "password123"),
("Justin Beiber", "jbieber", "12345"),
("Barack Obama", "bobama", "12345"),
("Barry Allen", "ballen", "12345"),
("Bruce Wayne", "bwayne", "thebat"),
("Clark Kent", "ckent", "12345"),
("Stephen Colbert", "scolbert", "12345"),
("Stephen King", "sking", "12345"),
("Brandon Sanderson", "bsanderson", "12345"),
("Anne Frank", "afrank", "12345"),
("Sarah North", "snorth", "12345");

/*Create Teachers*/
INSERT INTO TEACHER
(TeacherName, Username, Pass)
VALUES
("Jose Garrido", "jgarrido", "teacher123"),
("Dick Gayler", "dgayler", "teacher123"),
("Patrick Bobbie", "pbobbie", "teacher123"),
("Charlotte Stephenson", "cstephenson", "teacher123"),
("Dan Lo", "dlo", "teacher123"),
("Selena He", "she", "teacher123"),
("Kirk Inman", "kinman", "teacher123");

/*Create Classrooms*/
INSERT INTO CLASS
(ClassName, ClassTime, TeacherID, Classroom, Description)
VALUES
("Operating Systems", "MW 5:00-6:15PM", 1, "J-220", "This course introduces the fundamental concepts and principles of operating systems."),
("Simulation and Modeling", "MW 4:00-4:50PM", 1, "J-220", "This course introduces the fundamental concepts and principles of Simulation and Modeling."),
("Data Structures", "TR 11:00-11:50AM", 1, "J-110", "This course introduces the fundamental concepts and principles of Data Structures."),
("Internet Programming", "TR 11:00-11:50AM", 2, "J-110", "This course introduces Internet Programming with PHP, HTML, JS, CSS."),
("Compilers", "TR 2:00-3:15PM", 2, "J-205", "This course introduces compilers, parsing, interpreters and other related concepts."),
("English 1102", "MWF 8:00-8:50AM", 4, "J-100", "This course introduces World Literature and different cultures");

INSERT INTO PARENT_CLASS
(ClassID, ParentID)
VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(1, 3),
(3, 3),
(5, 3),
(6, 3),
(6, 4),
(6, 5),
(1, 5),
(2, 5),
(2, 6),
(3, 7),
(4, 8),
(2, 9),
(2, 10),
(4, 10),
(5, 10);