<!-- included by Admin.php -->

<?php include "admin_check.php" ?>

<title>Administrative Actions</title>
<div class="wrapper">
	<div class="main-f">
		<h1><strong>Administrative Actions</strong></h1>
		<div class="admin-action">	

				<form name = "admin" action = "Admin.php?view=administrator" method = "post">
					<button type = "submit">Add a new Admin</button>
				</form>

				<form name = "school" action = "Admin.php?view=school" method = "post">
					<button type = "submit">Add a new School</button>
				</form>

				<form name = "county" action = "Admin.php?view=county" method = "post">
					<button type = "submit">Add a new County</button>
				</form>

				<form name = "project" action = "Admin.php?view=project" method = "post">
					<button type = "submit">Add a new Project</button>
				</form>

				<form name = "student" action = "Admin.php?view=student" method = "post">
					<button type = "submit">Add a new Student</button>
				</form>

				<form name = "category" action = "Admin.php?view=category" method = "post">
					<button type = "submit">Add a new Category</button>
				</form>

				<form name = "grade" action = "Admin.php?view=grade" method = "post">
					<button type = "submit">Add a new Grade</button>
				</form>

				<form name = "session" action = "Admin.php?view=session" method = "post">

					<button type = "submit">Add a new Judge Session</button>
				</form>

				<form name = "booth" action = "Admin.php?view=booth" method = "post">
					<button type = "submit">Add a new Booth</button>
				</form>

				<form name = "judge" action = "Admin.php?view=judge" method = "post">
					<button type = "submit">Check Judge In Or Out</button>
				</form>

				<form name = "ranking" action = "Admin.php?view=ranking" method = "post">
					<button type = "submit">View Project Rankings</button>
				</form>

		</div>

	</div>
</div>
