/*
This sql script will create all tables and constraints for the database to be used for the M.A.N. 
School project for CS4720
*/

CREATE TABLE IF NOT EXISTS teacher(
	TeacherID int AUTO_INCREMENT NOT NULL,
    TeacherName varchar(50) NOT NULL,
    Username varchar(50) NOT NULL,
    Pass varchar(50) NOT NULL,
    CONSTRAINT TeacherID PRIMARY KEY(TeacherID)
);


CREATE TABLE IF NOT EXISTS Parent(
	ParentID int AUTO_INCREMENT NOT NULL,
    ParentName varchar(50) NOT NULL,
    Username varchar(50) NOT NULL,
    Pass varchar(50) NOT NULL,
    CONSTRAINT parentPK PRIMARY KEY(ParentID)
);


CREATE TABLE IF NOT EXISTS class(
	ClassID int AUTO_INCREMENT NOT NULL,
    ClassName varchar(50) NOT NULL,
    ClassTime varchar(25) NOT NULL,
    TeacherID int,
    Classroom varchar(50) NOT NULL,
    Description varchar(200) NOT NULL,
    CONSTRAINT classPk PRIMARY KEY(ClassID),
    CONSTRAINT fkTeacherID FOREIGN KEY (TeacherID) REFERENCES Teacher(TeacherID)
);

CREATE TABLE IF NOT EXISTS parent_class(
	ClassID int,
    ParentID int,
	CONSTRAINT parentFK FOREIGN KEY (ParentID) REFERENCES Parent(ParentID),
    CONSTRAINT classFK FOREIGN KEY (ClassID) REFERENCES class(ClassID)
);



CREATE TABLE IF NOT EXISTS homework(
	HomeworkID int AUTO_INCREMENT NOT NULL,
	ClassID int,
	dueDate varchar(50) NOT NULL,
	Description varchar(200) NOT NULL,
	isHistorical varchar(1) NOT NULL,
	PRIMARY KEY(HomeworkID),
	CONSTRAINT hwkclassFK FOREIGN KEY (ClassID) REFERENCES class(ClassID)
);



CREATE TABLE IF NOT EXISTS activities(
	ActivityID int AUTO_INCREMENT NOT NULL,
    ClassID int,
    ActivityName varchar(50) NOT NULL,
    ActivityDate varchar(50) NOT NULL,
    Description varchar(200) NOT NULL,
    CONSTRAINT actPk PRIMARY KEY(ActivityID),
    CONSTRAINT activclassFK FOREIGN KEY (ClassID) REFERENCES class(ClassID)
);
