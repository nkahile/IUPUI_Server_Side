use casawils_db;

drop table if exists Grade;

show warnings;

-- Grade level
-- referenced by Student, Judge
create table Grade (
	GradeID  int NOT NULL AUTO_INCREMENT,
	GradeNum int NOT NULL,

	UNIQUE (GradeNum),

	PRIMARY KEY (GradeID)
);

show warnings;

-- test
insert into
	Grade (GradeNum)
	values (1), (2), (3), (4), (5), (6), (7), (8), (9), (10), (11), (12)
;

select * from Grade;

-- deletion
drop trigger if exists GradeDelete;

delimiter //

create trigger GradeDelete before delete on Grade
for each row
begin

	-- Student
	declare dependents int;
	declare grade_name varchar(8);
	declare new_lower  int;
	declare new_upper  int;

	select count(*) into  dependents
	from    Student where Student.GradeID = old.GradeID;

	if dependents > 0
	then
		set grade_name = CONCAT("Grade ", old.GradeNum);
		signal sqlstate '45000'
		set message_text = grade_name;
	end if;

	-- Judge

	-- set Judge.LowerGradePref to the next highest grade
	select min(GradeNum) into  new_lower
	from   Grade         where GradeNum > old.GradeNum;

	update Judge
	set LowerGradePref = new_lower
	where Judge.LowerGradePref = old.GradeNum;

	-- set Judge.UpperGradePref to the next lowest grade
	select max(GradeNum) into  new_upper
	from   Grade         where GradeNum < old.GradeNum;

	update Judge
	set UpperGradePref = new_upper
	where Judge.UpperGradePref = old.GradeNum;

end;
//
delimiter ;
