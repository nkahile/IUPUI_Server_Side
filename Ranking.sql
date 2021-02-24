use casawils_db;

create or replace view Ranking
as
	select
		ProjectNum as RankingID,
		ProjectNum,
		Title,
		AVG(Rank) as AvgRank
	from
		Schedule,
		Project
	where Schedule.ProjectID = Project.ProjectID
	group by ProjectNum
	order by AvgRank;
