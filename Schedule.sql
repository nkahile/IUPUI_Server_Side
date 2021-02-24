use casawils_db;

drop table if exists Schedule;

show warnings;

-- each record in this table represents a specific judging of a specific project
create table Schedule (
	ScheduleID int NOT NULL AUTO_INCREMENT,
	SessionID  int NOT NULL, -- the time slot during which the project is to be judged
	ProjectID  int NOT NULL, -- the project to be judged
	JudgeID    int NOT NULL, -- the judge to judge the project

	-- Score out of 100
	Score decimal (5, 2),
		CHECK (Score >= 0),--  AND Score <= 100.00),
	
	Rank int,

	UNIQUE (SessionID, JudgeID),   -- a judge cannot judge multiple projects at the same time
	UNIQUE (ProjectID, JudgeID),   -- a judge cannot judge the same project more than once

	PRIMARY KEY (ScheduleID),

	FOREIGN KEY (SessionID) REFERENCES Session (SessionID) on delete cascade,
	FOREIGN KEY (ProjectID) REFERENCES Project (ProjectID),
	FOREIGN KEY (  JudgeID) REFERENCES Judge   (  JudgeID)
);

show warnings;

-- test
insert into
	Schedule (SessionID, ProjectID, JudgeID)
	values   (        1,         1,       1);

select * from Schedule;

-- ranking
drop procedure if exists ScoreProject;

delimiter //

create procedure ScoreProject(in newProjectID int, in newJudgeID int, in newScore int)
begin

	declare better int;
	declare newRank int;

	declare exit handler for 1442 begin end;

	-- if best project so far, set rank to 1
	select count(*)
	into better
	from Schedule
	where
		Score > newScore and
		JudgeID = newJudgeID;

	if better = 0 then
		set newRank = 1;

	-- else replace best project below it
	else
		-- get the rank of the worst project above it
		select min(Rank) + 1
		into newRank
		from Schedule
		where
			Score > newScore and
			JudgeID = oldJudgeID;
	end if;

	-- demote worse projects
	update Schedule
	set Rank = Rank + 1
	where Score < newScore and
	JudgeID = newJudgeID;

	-- update the scored project
	update Schedule
	set
		Score = newScore,
		Rank  = newRank
	where
		JudgeID   = newJudgeID and
		ProjectID = newProjectID;

end;
//
delimiter ;
