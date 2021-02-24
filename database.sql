use casawils_db;

drop table if exists Administrator;

drop table if exists Schedule;
drop table if exists Session;
drop table if exists Judge;
drop table if exists Degree;

drop table if exists Student;
drop table if exists Grade;
drop table if exists School;
drop table if exists County;

drop table if exists Project;
drop table if exists Booth;
drop table if exists Category;

source SQL/Category.sql;
show warnings;

source SQL/Booth.sql;
show warnings;

source SQL/Project.sql;
show warnings;

source SQL/County.sql;
show warnings;

source SQL/School.sql;
show warnings;

source SQL/Grade.sql;
show warnings;

source SQL/Student.sql;

source SQL/Degree.sql;
show warnings;

source SQL/Judge.sql;
show warnings;

source SQL/Session.sql;
show warnings;

source SQL/Schedule.sql;
show warnings;

source SQL/Ranking.sql;
show warnings;

source SQL/Administrator.sql;
show warnings;

