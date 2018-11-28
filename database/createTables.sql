CREATE TABLE IF NOT EXISTS messages(
	ClassID int,
	TeacherID int,
    	ParentID int,
	TimeSent TIMESTAMP,
	Content VARCHAR(50),
	CONSTRAINT msgparentFK FOREIGN KEY (ParentID) REFERENCES Parent(ParentID),
    CONSTRAINT msgclassFK FOREIGN KEY (ClassID) REFERENCES class(ClassID),
	CONSTRAINT msgteacherFK FOREIGN KEY (TeacherID) REFERENCES class(TeacherID)
);