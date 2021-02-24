use casawils_db;

drop table if exists Student;

show warnings;

#Students participating in the program
#A new record is added when a Student participates a second year
create table Student (
	StudentID  int          NOT NULL AUTO_INCREMENT,
	SchoolID   int          NOT NULL, #school attended by the Student
	ProjectID  int          NOT NULL, #project the Student worked on
	FirstName  varchar(50)  NOT NULL,
	MiddleName varchar(50),
	LastName   varchar(50)  NOT NULL,
	GradeID    int          NOT NULL, #what grade the Student is in
	Gender     varchar(50)  NOT NULL,
	Year       year         NOT NULL, #which year this Student is participating in

	PRIMARY KEY (StudentID),
	FOREIGN KEY (SchoolID)  REFERENCES School   (SchoolID),
	FOREIGN KEY (ProjectID) REFERENCES Project  (ProjectID),
	FOREIGN KEY (GradeID)   REFERENCES Grade    (GradeID)
);

show warnings;

#test
insert into
	Student (SchoolID, ProjectID, FirstName, MiddleName, LastName, GradeID,  Gender, Year)
	values  (       1,         1,   "Tfirst",  "Tmiddle",  "Tlast",     12, "Gtest", 2020)
;

select * from Student;
