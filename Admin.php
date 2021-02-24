<!--

	Admin.php

	Main administrator page. Previously Administrator.php before that name was used
	for a file defining the class Administrator, which extends Entity.

-->

<?php

	require_once "header.php";
	require_once "footer.php";

	//check whether admin is logged in
	if (isset ($_SESSION ['Administrator']))
	{
		//display correct view
		if (isset ($_GET ['view']))
		{
			if ($_GET ['view'] == "actions")
				include "actions.php";
			elseif ($_GET ['view'] == "checkin")
				include "checkin.php";
			else
				include "view.php";
		}
		else
			include "actions.php";

/*
		//display the file associated with the chosen action
		$filename = $_GET ['view'] . ".php";
		include $filename;
*/
	}

	//if the admin is not logged in yet, make them log in
	else
	{
		$_SESSION ['user_type'] = "Administrator";
		include "login1.php";
	}

?>
