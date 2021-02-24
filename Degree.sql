use casawils_db;

drop table if exists Degree;

show warnings;

-- degrees earned by judges
-- referenced by Judge
create table Degree (
	DegreeID int NOT NULL AUTO_INCREMENT,

	DegreeName  varchar(100) NOT NULL,
	DegreeLevel int          NOT NULL,

	PRIMARY KEY (DegreeID)
);

show warnings;

-- test
insert into
	Degree (DegreeName, DegreeLevel)
	values
		(              "Other", 0),
		("High School Diploma", 1),
		(       "Some college", 2),
		( "Associate's Degree", 3),
		(  "Bachelor's Degree", 5),
		(    "Master's Degree", 6),
		(              "Ph.D.", 7)
;

select * from Degree;
