			select BoothNum, CategoryName, ProjectNum, Title, Abstract
			from Project, Booth, Category
			where
				Project.BoothID    = Booth.BoothID AND
				Project.CategoryID = Category.CategoryID AND
				ProjectID          = 1;
