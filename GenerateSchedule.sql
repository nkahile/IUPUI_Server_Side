delimiter //

create or replace procedure GetScheduleCost()
begin
	

create or replace procedure GenerateSchedule()
begin
	declare judge_num int;
	declare num_judges int;
	declare day date;
	declare last_day date;
	declare project int;

	/* for each day */
	select CONVERT(date, MIN(StartTime)) into day from Session;
	select CONVERT(date, MAX(StartTime)) into last_day from Session;
	select MIN(ProjectID) into project from Project;
	while day <= last_day
	DO
		/* select the day's projects into a temp table */
		create temporary table if not exists projects
		select ProjectID
			into projects
			from Project
			where
				ProjectID > project AND
				ProjectID < project + (select count(*) from Booth)
		;


		select SessionID, ProjectID, JudgeID
		into Schedule
		from Session, Project, Judge
		where 

		/* for each Judge	*/
		select MIN(JudgeID) from Judge into judge_num;
		select count(*) from Judge into num_judges;
		while judge_num < num_judges
