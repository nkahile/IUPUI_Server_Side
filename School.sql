use casawils_db;

drop table if exists School;

show warnings;

-- Schools attended by Students
-- Referenced by Student
create table School (
	SchoolID    int        NOT NULL AUTO_INCREMENT,
	CountyID    int        NOT NULL, -- County in which the school is located
	SchoolName varchar(50) NOT NULL,

	UNIQUE (SchoolName, CountyID), #no County should have two Schools with the same name

	PRIMARY KEY (SchoolID),
	FOREIGN KEY (CountyID) REFERENCES County (CountyID)
);

show warnings;

-- test
insert into
	School (CountyID, SchoolName)
	values (       1, "Test School 1"),
	       (       1, "Test School 2")
;

select * from School;

-- deletion
drop trigger if exists SchoolDelete;

delimiter //

create trigger SchoolDelete before delete on School
for each row
begin

	declare dependents int;

	select count(*) into  dependents
	from    Student  where Student.SchoolID = old.SchoolID;

	if dependents > 0
	then
		signal sqlstate '45000'
		set message_text = old.SchoolName;
	end if;
end;
//
delimiter ;
