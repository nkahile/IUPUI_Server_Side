use casawils_db;

drop table if exists Session;

show warnings;

-- each record represents a timeslot during which multiple projects are judged simultaneously
-- referenced by Schedule
create table Session (
	SessionID int NOT NULL AUTO_INCREMENT,

	SessionNum int      NOT NULL, -- user-friendly Session number
	StartTime  datetime NOT NULL,
	EndTime    datetime,
	Active     bool     NOT NULL, -- whether the timeslot includes the current time

	UNIQUE (SessionNum),

	PRIMARY KEY (SessionID)
);

show warnings;

-- test
insert into Session (SessionNum, StartTime, EndTime, Active)
	values (1, now(), now() + interval 1 day, 1);


select * from Session;

-- deletion
/*
drop trigger if exists SessionDelete;

delimiter //

create trigger SessionDelete before delete on Session
for each row
begin

	delete from Schedule
	where SesssionID = old.SessionID;

end;
//
delimiter ;
*/
